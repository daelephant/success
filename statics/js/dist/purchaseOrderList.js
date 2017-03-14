var queryConditions = {
	matchCon: ""
},
	SYSTEM = system = parent.SYSTEM,
	hiddenAmount = !1,
	billRequiredCheck = system.billRequiredCheck,
	THISPAGE = {
		init: function(a) {
			SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0), this.mod_PageConfig = Public.mod_PageConfig.init("purchaseOrderList"), this.initDom(), this.loadGrid(), this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon"), this.$_beginDate = $("#beginDate").val(system.beginDate), this.$_endDate = $("#endDate").val(system.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
		},
		loadGrid: function() {
			function a(a, b, c) {
				var d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a></a><a class="ui-icon ui-icon-trash" title="删除"></a></div>';
				return d
			}
			function b(a, b, c) {
				return 150501 === a ? "采购" : (d.markRow.push(c.id), "退货")
			}
			var c = Public.setGrid(),
				d = this;
			queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val(), d.markRow = [];
			var e = [{
				name: "operating",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: a,
				align: "center",
				sortable: !1
			}, {
				name: "billDate",
				label: "订单日期",
				index: "billDate",
				width: 100,
				align: "center"
			}, {
				name: "billNo",
				label: "订单编号",
				index: "billNo",
				width: 120,
				align: "center"
			}, {
				name: "transType",
				label: "业务类别",
				index: "transType",
				width: 100,
				formatter: b,
				align: "center"
			}, {
				name: "contactName",
				label: "供应商",
				index: "contactName",
				width: 200
			}, {
				name: "totalAmount",
				label: "采购金额",
				index: "totalAmount",
				hidden: hiddenAmount,
				width: 100,
				align: "right",
				formatter: "currency"
			}, {
				name: "totalQty",
				label: "数量",
				index: "totalQty",
				width: 80,
				align: "center"
			}, {
				name: "billStatusName",
				label: "订单状态",
				hidden: !0,
				index: "billStatusName",
				width: 100,
				align: "center"
			}, {
				name: "deliveryDate",
				label: "交货日期",
				index: "deliveryDate",
				width: 100,
				align: "center"
			}, {
				name: "userName",
				label: "制单人",
				index: "userName",
				width: 80,
				title: !0,
				align: "center",
				classes: "ui-ellipsis"
			}, {
				name: "checkName",
				label: "审核人",
				index: "checkName",
				width: 80,
				title: !0,
				hidden: billRequiredCheck ? !1 : !0,
				fixed: !0,
				align: "center",
				classes: "ui-ellipsis"
			}, {
				name: "description",
				label: "备注",
				index: "description",
				width: 200,
				sortable: !1,
				editable: !0
			}
			, {
				name: "disEditable",
				label: "不可编辑",
				index: "disEditable",
				hidden: !0
			}
			];
			this.mod_PageConfig.gridReg("grid", e), e = this.mod_PageConfig.conf.grids.grid.colModel, $("#grid").jqGrid({
				url: "../scm/invPo.do?action=list",
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: c.h,
				altRows: !0,
				gridview: !0,
				multiselect: !0,
				colModel: e,
				cmTemplate: {
					sortable: !1,
					title: !1
				},
				page: 1,
				pager: "#page",
				rowNum: 100,
				rowList: [100, 200, 500],
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !1,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					total: "data.total",
					repeatitems: !1,
					id: "id"
				},
				loadComplete: function(a) {
					var b = d.markRow.length;
					if (b > 0) for (var c = 0; b > c; c++) $("#" + d.markRow[c]).addClass("red")
				},
				loadError: function(a, b, c) {},
				ondblClickRow: function(a, b, c, d) {
					$("#" + a).find(".ui-icon-pencil").trigger("click")
				},
				resizeStop: function(a, b) {
					THISPAGE.mod_PageConfig.setGridWidthByIndex(a, b, "grid")
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
				url: "../scm/invPo.do?action=list",
				datatype: "json",
				postData: a
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var a = this;
			if ($(".grid-wrap").on("click", ".ui-icon-pencil", function(a) {
				a.preventDefault();
				var b = $(this).parent().data("id"),
					c = $("#grid").jqGrid("getRowData", b),
					d = 1 == c.disEditable ? "&disEditable=true" : "";
				parent.tab.addTabItem({
					tabid: "purchase-purchaseOrder",
					text: "采购订单",
					url: "../purchase/purchaseOrder?id=" + b + "&flag=list" + d
				});
				$("#grid").jqGrid("getDataIDs");
				parent.cacheList.purchaseOrderId = $("#grid").jqGrid("getDataIDs")
			}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
				if (a.preventDefault(), Business.verifyRight("PO_DELETE")) {
					var b = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该采购订单吗？", function() {
						Public.ajaxPost("../scm/invPo.do?action=delete", {
							id: b
						}, function(a) {
							if (200 === a.status && a.msg && a.msg.length) {
								var b = "<p>操作成功！</p>";
								for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">采购订单［' + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
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
				if (!Business.verifyRight("PO_DELETE")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void $.dialog.confirm("您确定要删除选中的采购订单吗？", function() {
					Public.ajaxPost("../scm/invPo.do?action=delete", {
						id: c
					}, function(a) {
						if (200 === a.status && a.msg && a.msg.length) {
							var b = "<p>操作成功！</p>";
							for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">采购订单［' + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
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
					content: "请选择需要删除的项！"
				})
			}), $(".wrapper").on("click", "#print", function(a) {
				a.preventDefault(), Business.verifyRight("PO_PRINT") && Public.print({
					title: "采购订单列表",
					$grid: $("#grid"),
					pdf: "../scm/invPo.do?action=toPdf",
					billType: 10301,
					filterConditions: queryConditions
				})
			}), $(".wrapper").on("click", "#import", function(a) {
				queryConditions.transType;
				a.preventDefault(), Business.verifyRight("PO_导入") && parent.$.dialog({
					width: 560,
					height: 300,
					title: "批量导入",
					content: "url:/purchase/import?type=po",
					lock: !0
				})
			}), $(".wrapper").on("click", "#export", function(a) {
				if (!Business.verifyRight("PO_EXPORT")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join(),
					d = c ? "&id=" + c : "";
				for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);
				var f = "../scm/invPo.do?action=exportInvPo" + d;
				$(this).attr("href", f)
			}), billRequiredCheck) {
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block");
				$(".wrapper").on("click", "#audit", function(a) {
					a.preventDefault();
					var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
						c = b.join();
					return c ? void Public.ajaxPost("../scm/invPo.do?action=batchCheckInvPo", {
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
				}), $(".wrapper").on("click", "#reAudit", function(a) {
					a.preventDefault();
					var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
						c = b.join();
					return c ? void Public.ajaxPost("../scm/invPo.do?action=rsBatchCheckInvPo", {
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
				})
			}
			$(".wrapper").on("click", "#close", function(a) {
				if (!Business.verifyRight("PO_CLOSE")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invPo.do?action=batchClose", {
					id: c
				}, function(a) {
					if (200 === a.status && a.msg && a.msg.length) {
						var b = "<p>操作成功！</p>";
						for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">订单［' + c.id + "］关闭" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
						parent.Public.tips({
							content: b
						})
					} else parent.Public.tips({
						type: 1,
						content: a.msg
					});
					$("#search").trigger("click")
				}) : void parent.Public.tips({
					type: 2,
					content: "请先选择需要关闭的项！"
				})
			}), $(".wrapper").on("click", "#open", function(a) {
				if (!Business.verifyRight("PO_UNCLOSE")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invPo.do?action=rebatchClose", {
					id: c
				}, function(a) {
					if (200 === a.status && a.msg && a.msg.length) {
						var b = "<p>操作成功！</p>";
						for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">订单［' + c.id + "］启用" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
						parent.Public.tips({
							content: b
						})
					} else parent.Public.tips({
						type: 1,
						content: a.msg
					});
					$("#search").trigger("click")
				}) : void parent.Public.tips({
					type: 2,
					content: "请先选择需要启用的项！"
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
							queryConditions = this.content.handle(), THISPAGE.reloadData(queryConditions), "" !== queryConditions.matchCon ? a.$_matchCon.val(queryConditions.matchCon) : a.$_matchCon.val("请输入单据号或供应商或备注"), a.$_beginDate.val(queryConditions.beginDate), a.$_endDate.val(queryConditions.endDate)
						}
					}, {
						name: "取消"
					}],
					resize: !1,
					content: "url:../purchase/purchase_search?type=purchase&page=purchaseOrderList",
					data: queryConditions
				})
			}), $("#add").click(function(a) {
				a.preventDefault(), Business.verifyRight("PO_ADD") && parent.tab.addTabItem({
					tabid: "purchase-purchaseOrder",
					text: "采购订单",
					url: "../scm/invPo.do?action=initPo"
				})
			}), $(window).resize(function() {
				Public.resizeGrid()
			})
		}
	};
$(function() {
	THISPAGE.init()
});

 