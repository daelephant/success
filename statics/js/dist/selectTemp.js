function callback() {
	if (api.data && api.data.callback && "function" == typeof api.data.callback) {
		var a = $("#grid").jqGrid("getGridParam", "selrow");
		api.data.callback(a, api)
	}
}
var curRow, curCol, api = frameElement.api,
	queryConditions = {
		skey: ""
	},
	SYSTEM = parent.SYSTEM,
	qtyPlaces = Number(parent.SYSTEM.qtyPlaces),
	pricePlaces = Number(parent.SYSTEM.pricePlaces),
	amountPlaces = Number(parent.SYSTEM.amountPlaces),
	THISPAGE = {
		init: function(a) {
			this.data = api.data, this.initDom(), this.loadGrid(), this.addEvent(), this.initButton()
		},
		initButton: function() {
			var a = ["确定", "取消"];
			api.button({
				id: "confirm",
				name: a[0],
				focus: !0,
				callback: function() {
					return callback(), !1
				}
			}, {
				id: "cancel",
				name: a[1]
			})
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon"), this.$_matchCon.placeholder()
		},
		loadGrid: function() {
			var a = this.data.url,
				b = ($(window).height() - $(".grid-wrap").offset().top - 84, function(a, b, c) {
					var d = a.join('<p class="line" />');
					return d
				});
			$("#grid").jqGrid({
				url: a,
				postData: queryConditions,
				datatype: "json",
				width: 724,
				height: 354,
				altRows: !0,
				colModel: [{
					name: "operate",
					label: "操作",
					width: 30,
					fixed: !0,
					formatter: function(a, b, c) {
						var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
						return d
					}
				}, {
					name: "templateName",
					label: "模板名称"
				}, {
					name: "good",
					label: "组合件",
					width: 160,
					title: !0,
					classes: "ui-ellipsis"
				}, {
					name: "qty",
					label: "组合件数量",
					width: 70,
					title: !1
				}, {
					name: "goods",
					label: "子件",
					formatter: b,
					width: 160,
					fixed: !0,
					align: "center",
					title: !0,
					classes: "ui-ellipsis"
				}, {
					name: "qtys",
					label: "子件数量",
					width: 60,
					formatter: b,
					classes: "ui-ellipsis"
				}],
				cmTemplate: {
					sortable: !1,
					title: !1
				},
				page: 1,
				pager: "#page",
				rowNum: 2e3,
				rowList: [300, 500, 1e3],
				scroll: 1,
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !1,
				rownumbers: !0,
				cellsubmit: "clientArray",
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					total: "data.total",
					repeatitems: !1,
					id: "id"
				},
				loadError: function(a, b, c) {},
				ondblClickRow: function(a, b, c, d) {
					callback()
				},
				afterSaveCell: function(a, b, c, d, e) {}
			})
		},
		reloadData: function(a) {
			var b = this.data.url;
			$("#grid").jqGrid("setGridParam", {
				url: b,
				datatype: "json",
				postData: a
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var a = this;
			$("#search").click(function() {
				queryConditions.templateName = a.$_matchCon.val(), queryConditions.parent = $("#group").val(), queryConditions.children = $("#children").val(), THISPAGE.reloadData(queryConditions)
			}), $("#refresh").click(function() {
				THISPAGE.reloadData(queryConditions)
			}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
				a.preventDefault();
				var b = $(this).parent().data("id");
				$.dialog.confirm("您确定要删除该模板吗？", function() {
					Public.ajaxGet("../scm/invTemplate/delete?action=delete", {
						id: b
					}, function(a) {
						200 === a.status ? ($("#grid").jqGrid("delRowData", b), parent.Public.tips({
							content: "删除成功！"
						})) : parent.Public.tips({
							type: 1,
							content: a.msg
						})
					})
				})
			}), $(document).bind("click.cancel", function(a) {
				null !== curRow && null !== curCol && ($("#grid").jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
			})
		}
	};
THISPAGE.init();