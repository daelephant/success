var queryConditions = {
	matchCon: ""
},
	SYSTEM = parent.SYSTEM,
	VERSION = 1,
	billRequiredCheck = 0,
	THISPAGE = {
		init: function(a) {
			this.mod_PageConfig = Public.mod_PageConfig.init("paymentList"), this.initDom(), this.loadGrid(), this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon"), this.$_beginDate = $("#beginDate").val(SYSTEM.beginDate), this.$_endDate = $("#endDate").val(SYSTEM.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
		},
		loadGrid: function() {
			function a(a, b, c) {
				var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
				return d
			}
			var b = $(window).height() - $(".grid-wrap").offset().top - 65,
				c = this,
				d = [{
					name: "operating",
					label: "操作",
					width: 60,
					fixed: !0,
					formatter: a,
					align: "center"
				}, {
					name: "billDate",
					label: "单据日期",
					index: "billDate",
					width: 100,
					align: "center"
				}, {
					name: "billNo",
					label: "单据编号",
					index: "billNo",
					width: 120,
					align: "center"
				}, {
					name: "contactName",
					label: "入库单位",
					index: "contactName",
					width: 200
				}, {
					name: "amount",
					label: "付款金额",
					index: "amount",
					width: 100,
					align: "right",
					formatter: "currency"
				}, {
					name: "checkName",
					label: "审核人",
					index: "checkName",
					width: 80,
					hidden: billRequiredCheck ? !1 : !0,
					fixed: !0,
					align: "center",
					title: !0,
					classes: "ui-ellipsis"
				}, {
					name: "userName",
					label: "制单人",
					index: "userName",
					width: 80,
					fixed: !0,
					align: "center",
					title: !0,
					classes: "ui-ellipsis"
				}];
			switch (VERSION) {
			case 1:
				break;
			case 2:
				d = d.concat([{
					name: "bDeAmount",
					label: "本次核销金额",
					index: "hxAmount",
					width: 100,
					align: "right",
					formatter: "currency"
				}, {
					name: "adjustRate",
					label: "整单折扣",
					index: "adjustRate",
					width: 100,
					align: "right",
					formatter: "currency"
				}, {
					name: "deAmount",
					label: "本次预付款",
					index: "deAmount",
					width: 100,
					align: "right",
					formatter: "currency"
				}])
			}
			d.push({
				name: "description",
				label: "备注",
				index: "description",
				width: 200,
				classes: "ui-ellipsis"
			}), queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val(), c.markRow = [], this.mod_PageConfig.gridReg("grid", d), d = this.mod_PageConfig.conf.grids.grid.colModel, $("#grid").jqGrid({
				url: "../scm/payment.do?action=list",
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: b,
				altRows: !0,
				rownumbers: !0,
				gridview: !0,
				colModel: d,
				cmTemplate: {
					sortable: !1,
					title: !1
				},
				multiselect: !0,
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				rowNum: 2e3,
				rowList: [300, 500, 1e3],
				scroll: 1,
				loadonce: !0,
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !0,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					repeatitems: !1,
					id: "id"
				},
				loadComplete: function(a) {
					var b = c.markRow.length;
					if (b > 0) for (var d = 0; b > d; d++) $("#" + c.markRow[d]).addClass("red")
				},
				loadError: function(a, b, c) {},
				ondblClickRow: function(a, b, c, d) {
					$("#" + a).find(".ui-icon-pencil").trigger("click")
				},
				resizeStop: function(a, b) {
					THISPAGE.mod_PageConfig.setGridWidthByIndex(a, b - 1, "grid")
				}
			}).navGrid("#page", {
				edit: !1,
				add: !1,
				del: !1,
				search: !1,
				refresh: !1
			}).navButtonAdd("#page", {
				caption: "",
				buttonicon: "ui-icon-config",
				onClickButton: function() {
					THISPAGE.mod_PageConfig.config()
				},
				position: "last"
			})
		},
		reloadData: function(a) {
			this.markRow = [], $("#grid").jqGrid("setGridParam", {
				url: "../scm/payment.do?action=list",
				datatype: "json",
				postData: a
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var a = this;
			if ($(".grid-wrap").on("click", ".ui-icon-pencil", function(a) {
				a.preventDefault();
				var b = $(this).parent().data("id");
				parent.tab.addTabItem({
					tabid: "money-payment",
					text: "付款单",
					url: "../money/payment?id=" + b
				});
				$("#grid").jqGrid("getDataIDs");
				parent.paymentListIds = $("#grid").jqGrid("getDataIDs")
			}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
				if (a.preventDefault(), Business.verifyRight("PAYMENT_DELETE")) {
					var b = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该付款单吗？", function() {
						Public.ajaxPost("../scm/payment.do?action=delete", {
							id: b
						}, function(a) {
							if (200 === a.status && a.msg && a.msg.length) {
								var b = "<p>操作成功！</p>";
								for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">付款单［' + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
								parent.Public.tips({
									content: b
								})
							} else parent.Public.tips({
								type: 1,
								content: a.msg
							});
							$("#search").trigger("click")
						})
					})
				}
			}), $(".wrapper").on("click", "#btn-batchDel", function(a) {
				if (!Business.verifyRight("PAYMENT_DELETE")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void $.dialog.confirm("您确定要删除选中的付款单吗？", function() {
					Public.ajaxPost("../scm/payment.do?action=delete", {
						id: c
					}, function(a) {
						if (200 === a.status && a.msg && a.msg.length) {
							var b = "<p>操作成功！</p>";
							for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">付款单［' + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
							parent.Public.tips({
								content: b
							})
						} else parent.Public.tips({
							type: 1,
							content: a.msg
						});
						$("#search").trigger("click")
					})
				}) : void parent.Public.tips({
					type: 2,
					content: "请先选择需要删除的项！"
				})
			}), $("#search").click(function() {
				queryConditions.matchCon = "请输入单据号或供应商或备注" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)
			}), $("#moreCon").click(function() {
				queryConditions.matchCon = a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), $.dialog({
					id: "moreCon",
					lock: !0,
					width: 480,
					height: 300,
					min: !1,
					max: !1,
					title: "高级搜索",
					button: [{
						name: "确定",
						focus: !0,
						callback: function() {
							queryConditions = this.content.handle(queryConditions), THISPAGE.reloadData(queryConditions), "" !== queryConditions.matchCon ? a.$_matchCon.val(queryConditions.matchCon) : a.$_matchCon.val("请输入单据号或供应商或备注"), a.$_beginDate.val(queryConditions.beginDate), a.$_endDate.val(queryConditions.endDate)
						}
					}, {
						name: "取消"
					}],
					resize: !1,
					content: "url:../money/money_search?type=money&diff=payment",
					data: queryConditions
				})
			}), $("#refresh").click(function() {
				THISPAGE.reloadData(queryConditions)
			}), $("#add").click(function(a) {
				a.preventDefault(), Business.verifyRight("PAYMENT_ADD") && parent.tab.addTabItem({
					tabid: "money-payment",
					text: "付款单",
					url: "../scm/payment.do?action=initPay"
				})
			}), billRequiredCheck) {
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block");
				$(".wrapper").on("click", "#audit", function(a) {
					if (a.preventDefault(), Business.verifyRight("PAYMENT_CHECK")) {
						var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
							c = b.join();
						return c ? void Public.ajaxPost("../scm/payment.do?action=batchCheckPayment", {
							id: c
						}, function(a) {
							200 === a.status ? parent.Public.tips({
								content: a.msg
							}) : parent.Public.tips({
								type: 1,
								content: a.msg
							}), $("#search").trigger("click")
						}) : void parent.Public.tips({
							type: 2,
							content: "请先选择需要审核的项！"
						})
					}
				}), $(".wrapper").on("click", "#reAudit", function(a) {
					if (a.preventDefault(), Business.verifyRight("PAYMENT_UNCHECK")) {
						var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
							c = b.join();
						return c ? void Public.ajaxPost("../scm/payment.do?action=rsbatchCheckPayment", {
							id: c
						}, function(a) {
							200 === a.status ? parent.Public.tips({
								content: a.msg
							}) : parent.Public.tips({
								type: 1,
								content: a.msg
							}), $("#search").trigger("click")
						}) : void parent.Public.tips({
							type: 2,
							content: "请先选择需要反审核的项！"
						})
					}
				})
			}
			$(".wrapper").on("click", "#print", function(a) {
				a.preventDefault(), Business.verifyRight("PAYMENT_PRINT") && Public.print({
					title: "付款单打印",
					$grid: $("#grid"),
					pdf: "../scm/payment.do?action=toPdf",
					billType: 10602,
					filterConditions: queryConditions
				})
			}), $("#export").click(function(a) {
				if (!Business.verifyRight("PAYMENT_EXPORT")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join(),
					d = c ? "&id=" + c : "";
				for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);
				var f = "../scm/payment.do?action=export" + d;
				$(this).attr("href", f)
			}), $(window).resize(function() {
				Public.resizeGrid()
			})
		}
	};
$(function() {
	THISPAGE.init()
});

 