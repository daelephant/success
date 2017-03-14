var queryConditions = {
	matchCon: ""
},
	SYSTEM = system = parent.SYSTEM,
	hiddenAmount = !1,
	billRequiredCheck = system.billRequiredCheck,
	urlParam = Public.urlParam();
queryConditions.transType = "150502" === urlParam.transType ? "150502" : "150501";
var THISPAGE = {
	init: function() {
		SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0), this.mod_PageConfig = Public.mod_PageConfig.init("150502" != urlParam.transType ? "purchaseList" : "purchaseListBack"), this.initDom(), this.loadGrid(), this.addEvent()
	},
	initDom: function() {
		this.$_matchCon = $("#matchCon"), this.$_beginDate = $("#beginDate").val(system.beginDate), this.$_endDate = $("#endDate").val(system.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
	},
	loadGrid: function() {
		function a(a, b, c) {
			var d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a><a class="ui-icon ui-icon-trash" title="删除"></a></div>';
			return d
		}
		var b = Public.setGrid();
		queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val();
		var c = "150501" == queryConditions.transType ? "付" : "退",
			d = [{
				name: "operating",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: a,
				align: "center",
				sortable: !1
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
			},
			//  {
			// 	name: "amount",
			// 	label: "优惠后金额1",
			// 	index: "amount",
			// 	hidden: hiddenAmount,
			// 	width: 100,
			// 	align: "right",
			// 	formatter: "currency"
			// }, {
			// 	name: "rpAmount",
			// 	label: "已" + c + "款",
			// 	index: "rpAmount",
			// 	hidden: hiddenAmount,
			// 	width: 100,
			// 	align: "right",
			// 	formatter: "currency"
			// }, {
			// 	name: "hxStateCode",
			// 	label: c + "款状态",
			// 	index: "hxStateCode",
			// 	width: 80,
			// 	fixed: !0,
			// 	align: "center",
			// 	title: !0,
			// 	classes: "ui-ellipsis",
			// 	formatter: function(a) {
			// 		switch (a) {
			// 		case 0:
			// 			return "未" + c + "款";
			// 		case 1:
			// 			return "部分" + c + "款";
			// 		case 2:
			// 			return "全部" + c + "款";
			// 		default:
			// 			return "&#160"
			// 		}
			// 	}
			// }, 
			{
				name: "userName",
				label: "制单人",
				index: "userName",
				width: 80,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
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
				name: "description",
				label: "备注",
				index: "description",
				width: 200,
				sortable: !1
			}, {
				name: "disEditable",
				label: "不可编辑",
				label: "",
				index: "disEditable",
				hidden: !0
			}];
		this.mod_PageConfig.gridReg("grid", d), d = this.mod_PageConfig.conf.grids.grid.colModel, $("#grid").jqGrid({
			url: "../scm/invPu.do?action=list",
			postData: queryConditions,
			datatype: "json",
			autowidth: !0,
			height: b.h,
			altRows: !0,
			gridview: !0,
			multiselect: !0,
			colModel: d,
			cmTemplate: {
				sortable: !0,
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
				if (billRequiredCheck) for (var b = a.data.rows, c = 0; c < b.length; c++) {
					var d = b[c];
					d.checked || $("#" + d.id).addClass("gray")
				}
				"150502" == queryConditions.transType && $("#grid").find(".jqgrow").addClass("red")
			},
			loadError: function() {},
			ondblClickRow: function(a) {
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
			url: "../scm/invPu.do?action=list",
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
				d = 1 == c.disEditable ? "&disEditable=true" : "",
				e = ($("#grid").jqGrid("getDataIDs"), "入库单"),
				f = "purchase-purchase";
			"150502" == queryConditions.transType ? (e = "采购退货单", f = "purchase-purchaseBack", parent.cacheList.purchaseBackId = $("#grid").jqGrid("getDataIDs")) : parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs"), parent.tab.addTabItem({
				tabid: f,
				text: e,
				url: "../purchase/index?id=" + b + "&flag=list" + d + "&transType=" + queryConditions.transType
			})
		}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
			if (a.preventDefault(), Business.verifyRight("PU_DELETE")) {
				var b = "入库单";
				"150502" == queryConditions.transType && (b = "采购退货单");
				var c = $(this).parent().data("id");
				$.dialog.confirm("您确定要删除该" + b + "吗？", function() {
					Public.ajaxPost("../scm/invPu.do?action=delete", {
						id: c
					}, function(a) {
						if (200 === a.status && a.msg && a.msg.length) {
							var c = "<p>操作成功！</p>";
							for (var d in a.msg)"function" != typeof a.msg[d] && (d = a.msg[d], c += '<p class="' + (1 == d.isSuccess ? "" : "red") + '">' + b + "［" + d.id + "］删除" + (1 == d.isSuccess ? "成功！" : "失败：" + d.msg) + "</p>");
							parent.Public.tips({
								content: c
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
			if (!Business.verifyRight("PU_DELETE")) return void a.preventDefault();
			var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
				c = b.join();
			if (!c) return void parent.Public.tips({
				type: 2,
				content: "请先选择需要删除的项！"
			});
			var d = "入库单";
			"150502" == queryConditions.transType && (d = "采购退货单"), $.dialog.confirm("您确定要删除选中的" + d + "吗？", function() {
				Public.ajaxPost("../scm/invPu.do?action=delete", {
					id: c
				}, function(a) {
					if (200 === a.status && a.msg && a.msg.length) {
						var b = "<p>操作成功！</p>";
						for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">' + d + "［" + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
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
		}), $(".wrapper").on("click", "#print", function(a) {
			a.preventDefault(), Business.verifyRight("PU_PRINT") && Public.print({
				title: "入库单列表",
				$grid: $("#grid"),
				pdf: "../scm/invPu.do?action=toPdf",
				billType: 10101,
				filterConditions: queryConditions
			})
		}), $(".wrapper").on("click", "#import", function(a) {
			var b = queryConditions.transType;
			a.preventDefault(), Business.verifyRight("PU_导入") && parent.$.dialog({
				width: 560,
				height: 300,
				title: "批量导入",
				content: "url:../purchase/import.jsp?type=pu&transType=" + b,
				lock: !0
			})
		}), $(".wrapper").on("click", "#export", function(a) {
			if (!Business.verifyRight("PU_EXPORT")) return void a.preventDefault();
			var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
				c = b.join(),
				d = c ? "&id=" + c : "";
			for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);
			var f = "../scm/invPu.do?action=exportInvPu" + d;
			$(this).attr("href", f)
		}), billRequiredCheck) {
			{
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block")
			}
			$(".wrapper").on("click", "#audit", function(a) {
				a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invPu.do?action=batchCheckInvPu", {
					id: c
				}, function(a) {
					parent.Public.tips(200 === a.status ? {
						content: "审核成功！"
					} : {
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
				return c ? void Public.ajaxPost("../scm/invPu.do?action=rsBatchCheckInvPu", {
					id: c
				}, function(a) {
					parent.Public.tips(200 === a.status ? {
						content: "反审核成功！"
					} : {
						type: 1,
						content: a.msg
					}), $("#search").trigger("click")
				}) : void parent.Public.tips({
					type: 2,
					content: "请先选择需要反审核的项！"
				})
			})
		}
		$("#search").click(function() {
			queryConditions.matchCon = "请输入单据号或供应商或备注" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)
		}), $("#moreCon").click(function() {
			queryConditions.matchCon = a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), queryConditions.transType = "150502" === urlParam.transType ? "150502" : "150501", $.dialog({
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
						queryConditions = this.content.handle(), THISPAGE.reloadData(queryConditions), a.$_matchCon.val("" !== queryConditions.matchCon ? queryConditions.matchCon : "请输入单据号或供应商或备注"), a.$_beginDate.val(queryConditions.beginDate), a.$_endDate.val(queryConditions.endDate)
					}
				}, {
					name: "取消"
				}],
				resize: !1,
				content: "url:../purchase/purchase_search?type=purchase",
				data: queryConditions
			})
		}), $("#add").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("PU_ADD")) {
				var b = "入库单",
					c = "purchase-purchase";
				if ("150502" == queryConditions.transType) var b = "采购退货单",
					c = "purchase-purchaseBack";
				parent.tab.addTabItem({
					tabid: c,
					text: b,
					url: "../scm/invPu.do?action=initPur&transType=" + queryConditions.transType
				})
			}
		}), $(window).resize(function() {
			Public.resizeGrid()
		})
	}
};
$(function() {
	THISPAGE.init()
});
