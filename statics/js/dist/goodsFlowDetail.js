define(["jquery", "print"], function(a, b, c) {
	function d() {
		Business.getSearchList(), Business.filterGoods(), Business.filterStorage(), Business.moreFilterEvent(), l("#date,#storage,#goodsfilter,#goods").show(), l("#storage").insertBefore(l("#goods")), l("#btn-print").hide(), l("#conditions-trigger").trigger("click"), n.beginDate && n.endDate && l("div.grid-subtitle").text("日期: " + n.beginDate + "至" + n.endDate), l("#filter-fromDate").val(n.beginDate), l("#filter-toDate").val(n.endDate), l("#filter-goods input").val(n.goodsNo), l("#filter-storage input").val(n.storageNo), Public.dateCheck();
		var a = new Pikaday({
			field: l("#filter-fromDate")[0]
		}),
			b = new Pikaday({
				field: l("#filter-toDate")[0]
			}),
			c = Public.categoryTree(l("#filterCat"), {
				width: 200
			});
		chkboxes = l("#chk-wrap").cssCheckbox(), l("#filter-submit").on("click", function(d) {
			d.preventDefault();
			var e = l("#filter-fromDate").val(),
				f = l("#filter-toDate").val(),
				g = a.getDate(),
				h = b.getDate();
			if (g.getTime() > h.getTime()) return void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			});
			n = {
				beginDate: e,
				endDate: f,
				goodsNo: l("#filter-goods input").val() || "",
				storageNo: l("#filter-storage input").val() || "",
				catId: c.getValue(),
				catName: c.getText()
			}, l("#selected-period").text(e + "至" + f), l("div.grid-subtitle").text("日期: " + e + " 至 " + f), chkVals = chkboxes.chkVal();
			for (var i = 0, j = chkVals.length; j > i; i++) n[chkVals[i]] = 1;
			k()
		}), l("#filter-reset").on("click", function(a) {
			a.preventDefault(), l("#filter-fromDate").val(""), l("#filter-toDate").val(""), l("#filter-goods input").val(""), l("#filter-storage input").val("")
		})
	}
	function e() {
		var a = n.storage ? n.storage.split(",") : "",
			b = n.goods ? n.goods.split(",") : "",
			c = "";
		a && b ? c = "「您已选择了<b>" + a.length + "</b>个仓库，<b>" + b.length + "</b>个商品进行查询」" : a ? c = "「您已选择了<b>" + customer.length + "</b>个仓库进行查询」" : b && (c = "「您已选择了<b>" + b.length + "</b>个商品进行查询」"), l("#cur-search-tip").html(c)
	}
	function f() {
		l("#refresh").on("click", function(a) {
			a.preventDefault(), k()
		}), l("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("DeliverDetailReport_PRINT") && l("div.ui-print").printTable()
		}), l("#btn-export").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("DeliverDetailReport_EXPORT")) {
				var b = {};
				for (var c in n) n[c] && (b[c] = n[c]);
				Business.getFile("../report/deliverDetail.do?action=exporter", b)
			}
		}), l("#config").show().click(function(a) {
			o.config()
		})
	}
	function g() {
		var a = !1,
			b = !1,
			c = !1;
		m.isAdmin !== !1 || m.rights.AMOUNT_COSTAMOUNT || (a = !0), m.isAdmin !== !1 || m.rights.AMOUNT_OUTAMOUNT || (b = !0), m.isAdmin !== !1 || m.rights.AMOUNT_INAMOUNT || (c = !0);
		var d = [{
			name: "assistName",
			label: "商品类别",
			width: 80,
			align: "center"
		}, {
			name: "invNo",
			label: "商品编号",
			frozen: !0,
			width: 80
		}, {
			name: "invName",
			label: "商品名称",
			frozen: !0,
			width: 200,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "spec",
			label: "规格型号",
			frozen: !0,
			width: 60,
			align: "center"
		}, {
			name: "unit",
			label: "单位",
			frozen: !0,
			width: 50,
			fixed: !0,
			align: "center"
		}, {
			name: "date",
			label: "日期",
			frozen: !0,
			width: 80,
			fixed: !0,
			align: "center"
		}, {
			name: "billNo",
			label: "单据号",
			frozen: !0,
			width: 120,
			fixed: !0,
			align: "center"
		}, {
			name: "billId",
			label: "出库ID",
			width: 0,
			hidden: !0
		}, {
			name: "billType",
			label: "出库类型",
			width: 0,
			hidden: !0
		}, {
			name: "transType",
			label: "业务类别",
			width: 60,
			fixed: !0,
			align: "center"
		}, {
			name: "buName",
			label: "往来单位",
			width: 100,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "location",
			label: "仓库",
			width: 60,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "inqty",
			label: "数量",
			width: 80,
			fixed: !0,
			align: "right"
		}, {
			name: "inunitCost",
			label: "单位成本",
			width: 80,
			fixed: !0,
			hidden: c,
			align: "right"
		}, {
			name: "incost",
			label: "成本",
			width: 80,
			fixed: !0,
			hidden: c,
			align: "right"
		}, {
			name: "outqty",
			label: "数量",
			width: 80,
			fixed: !0,
			align: "right"
		}, {
			name: "outunitCost",
			label: "单位成本",
			width: 80,
			fixed: !0,
			hidden: b,
			align: "right"
		}, {
			name: "outcost",
			label: "成本",
			width: 80,
			fixed: !0,
			hidden: b,
			align: "right"
		}, 
		
		//{
//			name: "totalqty",
//			label: "数量",
//			width: 80,
//			fixed: !0,
//			align: "right"
//		}, {
//			name: "totalunitCost",
//			label: "单位成本",
//			width: 80,
//			fixed: !0,
//			hidden: a,
//			align: "right"
//		}, {
//			name: "totalcost",
//			label: "成本",
//			width: 80,
//			fixed: !0,
//			hidden: a,
//			align: "right"
//		}
		],
			e = "local",
			f = "#";
		n.autoSearch && (e = "json", f = "../report/deliverDetail.do?action=detail"), o.gridReg("grid", d), d = o.conf.grids.grid.colModel, l("#grid").jqGrid({
			url: f,
			postData: n,
			datatype: e,
			autowidth: !0,
			height: "auto",
			gridview: !0,
			colModel: d,
			cmTemplate: {
				sortable: !1,
				title: !1
			},
			page: 1,
			sortname: "date",
			sortorder: "desc",
			rowNum: 3e3,
			loadonce: !0,
			viewrecords: !0,
			shrinkToFit: !1,
			forceFit: !0,
			footerrow: !0,
			userDataOnFooter: !0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				userdata: "data.userdata",
				repeatitems: !1,
				id: "0"
			},
			ondblClickRow: function(a) {
				var b = l("#grid").getRowData(a),
					c = b.billId,
					d = b.billType;
				switch (d) {
				case "PUR":
					if (!Business.verifyRight("PU_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "purchase-purchase",
						text: "入库单",
						url: "../purchase/index?id=" + c
					});
					break;
				case "SALE":
					if (!Business.verifyRight("SA_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "sales-sales",
						text: "出库单",
						url: "../sales/index?id=" + c
					});
					break;
				case "TRANSFER":
					if (!Business.verifyRight("TF_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-transfers",
						text: "调拨单",
						url: "../storage/transfers?id=" + c
					});
					break;
				case "OI":
					if (!Business.verifyRight("IO_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-otherWarehouse",
						text: "其他入库",
						url: "../storage/other_warehouse?id=" + c
					});
					break;
				case "OO":
					if (!Business.verifyRight("OO_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-otherOutbound",
						text: "其他出库",
						url: "../storage/other_outbound?id=" + c
					});
					break;
				case "CADJ":
					if (!Business.verifyRight("CADJ_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-adjustment",
						text: "成本调整单",
						url: "../storage/adjustment?id=" + c
					});
					break;
				case "ZZD":
					if (!Business.verifyRight("ZZD_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-assemble",
						text: "组装单",
						url: "../storage/assemble?id=" + c
					});
					break;
				case "CXD":
					if (!Business.verifyRight("CXD_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-disassemble",
						text: "拆卸单",
						url: "../storage/disassemble?id=" + c
					})
				}
			},
			loadComplete: function(a) {
				var b;
				if (a && a.data) {
					var c = a.data.rows.length;
					b = c ? 31 * c : "auto"
				}
				h(b)
			},
			gridComplete: function() {
				l("#grid").footerData("set", {
					location: "合计:"
				}), l("table.ui-jqgrid-ftable").find('td[aria-describedby="grid_location"]').prevUntil().css("border-right-color", "#fff")
			},
			resizeStop: function(a, b) {
				o.setGridWidthByIndex(a, b + 1, "grid")
			}
		}).jqGrid("setGroupHeaders", {
			useColSpanStyle: !0,
			groupHeaders: [{
				startColumnName: "inqty",
				numberOfColumns: 3,
				titleText: "入库"
			}, {
				startColumnName: "outqty",
				numberOfColumns: 3,
				titleText: "出库"
			}, {
				startColumnName: "totalqty",
				numberOfColumns: 3,
				titleText: "结存"
			}]
		}).jqGrid("setFrozenColumns"), n.autoSearch ? (l(".no-query").remove(), l(".ui-print").show()) : l(".ui-print").hide()
	}
	function h(a) {
		a && (h.h = a);
		var b = i(),
			c = h.h,
			d = j(),
			e = l("#grid");
		c > d && (c = d), b < e.width() && (c += 17), e.jqGrid("setGridWidth", b, !1), e.jqGrid("setGridHeight", c), l("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		})
	}
	function i() {
		return l(window).width() - (i.offsetLeft || (i.offsetLeft = l("#grid-wrap").offset().left)) - 36 - 20
	}
	function j() {
		return l(window).height() - (j.offsetTop || (j.offsetTop = l("#grid").offset().top)) - 36 - 16 - 24
	}
	function k(a) {
		l(".no-query").remove(), l(".ui-print").show(), l("#grid").jqGrid("setGridParam", {
			datatype: "json",
			ajaxGridOptions: {
				type: "POST"
			},
			postData: n,
			url: "../report/deliverDetail.do?action=detail"
		}).trigger("reloadGrid")
	}
	var l = a("jquery"),
		m = parent.SYSTEM,
		n = l.extend({
			beginDate: "",
			endDate: "",
			goodsNo: "",
			storageNo: ""
		}, Public.urlParam());
	a("print");
	var o = Public.mod_PageConfig.init("goodsFlowDetail");
	d(), e(), f(), g();
	var p;
	l(window).on("resize", function(a) {
		p || (p = setTimeout(function() {
			h(), p = null
		}, 50))
	})
});