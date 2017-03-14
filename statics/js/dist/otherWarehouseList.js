var queryConditions = {
	matchCon: "",
	locationId: -1,
	transTypeId: -1
},
	hiddenAmount = !1,
	SYSTEM = system = parent.SYSTEM,
	billRequiredCheck = system.billRequiredCheck,
	THISPAGE = {
		init: function(a) {
			SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0), this.mod_PageConfig = Public.mod_PageConfig.init("otherWarehouseList"), this.initDom(), this.loadGrid(), this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon"), this.$_beginDate = $("#beginDate").val(system.beginDate), this.$_endDate = $("#endDate").val(system.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
		},
		loadGrid: function() {
			function a(a, b, c) {
				var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
				return d
			}
			function b(a, b, c) {
				var d;
				switch (a) {
				case 150701:
					d = "盘盈";
					break;
				case 150706:
					d = "其他入库"
				}
				return d
			}
			var c = Public.setGrid();
			queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val();
			var d = [{
				name: "operating",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: a,
				align: "center"
			}, {
				name: "billDate",
				label: "单据日期",
				width: 100,
				align: "center"
			}, {
				name: "billNo",
				label: "单据编号",
				width: 120,
				align: "center"
			}, {
				name: "transType",
				label: "业务类别",
				width: 100,
				formatter: b
			}, {
				name: "amount",
				label: "金额",
				hidden: hiddenAmount,
				width: 100,
				align: "right",
				formatter: "currency"
			}, {
				name: "contactName",
				label: "供应商",
				width: 200
			}, {
				name: "userName",
				label: "制单人",
				index: "userName",
				width: 80,
				fixed: !0,
				align: "center",
				title: !1
			}, {
				name: "checkName",
				label: "审核人",
				width: 80,
				hidden: billRequiredCheck ? !1 : !0,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
			}, {
				name: "description",
				label: "备注",
				width: 200
			}];
			this.mod_PageConfig.gridReg("grid", d), d = this.mod_PageConfig.conf.grids.grid.colModel, $("#grid").jqGrid({
				url: "../scm/invOi?action=listIn&type=in",
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: c.h,
				altRows: !0,
				gridview: !0,
				multiselect: !0,
				colModel: d,
				cmTemplate: {
					sortable: !1,
					title: !1
				},
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				rowNum: 100,
				rowList: [100, 200, 500],
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !0,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					repeatitems: !1,
					total: "data.total",
					id: "id"
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
			$("#grid").jqGrid("setGridParam", {
				url: "../scm/invOi?action=listIn&type=in",
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
					tabid: "storage-otherWarehouse",
					text: "其他入库",
					url: "../storage/other_warehouse?id=" + b
				});
				$("#grid").jqGrid("getDataIDs");
				parent.salesListIds = $("#grid").jqGrid("getDataIDs")
			}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
				if (a.preventDefault(), Business.verifyRight("IO_DELETE")) {
					var b = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该其他入库单吗？", function() {
						Public.ajaxPost("../scm/invOi?action=deleteIn", {
							id: b
						}, function(a) {
							if (200 === a.status && a.msg && a.msg.length) {
								var b = "<p>操作成功！</p>";
								for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">其他入库单［' + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
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
				if (!Business.verifyRight("IO_DELETE")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void $.dialog.confirm("您确定要删除选中的其他入库单吗？", function() {
					Public.ajaxPost("../scm/invOi?action=deleteIn", {
						id: c
					}, function(a) {
						if (200 === a.status && a.msg && a.msg.length) {
							var b = "<p>操作成功！</p>";
							for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">其他入库单［' + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
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
			}), billRequiredCheck) {
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block");
				$(".wrapper").on("click", "#audit", function(a) {
					if (a.preventDefault(), Business.verifyRight("IO_CHECK")) {
						var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
							c = b.join();
						return c ? void Public.ajaxPost("../scm/invOi?action=batchCheckInvOi", {
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
					if (a.preventDefault(), Business.verifyRight("IO_UNCHECK")) {
						var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
							c = b.join();
						return c ? void Public.ajaxPost("../scm/invOi?action=rsBatchCheckInvOi", {
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
			$("#search").click(function() {
				queryConditions.matchCon = "请输入单据号或供应商或备注" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), queryConditions.locationId = -1, queryConditions.transTypeId = -1, THISPAGE.reloadData(queryConditions)
			}), $("#moreCon").click(function() {
				queryConditions.matchCon = a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), $.dialog({
					id: "moreCon",
					width: 480,
					height: 330,
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
					content: "url:../storage/other_search?type=other&diff=warehouse",
					data: queryConditions
				})
			}), $("#add").click(function(a) {
				a.preventDefault(), Business.verifyRight("IO_ADD") && parent.tab.addTabItem({
					tabid: "storage-otherWarehouse",
					text: "其他入库",
					url: "../scm/invOi?action=initOi&type=in"
				})
			}), $(window).resize(function() {
				Public.resizeGrid()
			}), $(".wrapper").on("click", "#print", function(a) {
				if (!Business.verifyRight("IO_PRINT")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join(),
					d = c ? "&id=" + c : "",
					e = "../scm/invOi?action=toOiPdf&billType=IO" + d;
				$(this).attr("href", e)
			}), $(".wrapper").on("click", "#export", function(a) {
				if (!Business.verifyRight("IO_EXPORT")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join(),
					d = c ? "&id=" + c : "";
				for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);
				var f = "../scm/invOi?action=exportInvOi" + d;
				$(this).attr("href", f)
			})
		}
	};
$(function() {
	THISPAGE.init()
});
 
 