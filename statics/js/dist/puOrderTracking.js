define(["jquery", "print"], function(a, b, c) {
	function d() {
		Business.getSearchList(), Business.filterSupplier(), Business.filterGoods(), Business.moreFilterEvent(), k("#date,#supplier,#goods,#billNum,#deliveryDate,#filter,#status-wrap").show(), k("#billNum label").text("订单号"), k("#filter label").text("供应商类别"), k("#conditions-trigger").trigger("click"), k("#filter-fromDate").val(l.beginDate || ""), k("#filter-toDate").val(l.endDate || ""), k("#filter-fromDeliveryDate").val(l.fromDeliveryDate || ""), k("#filter-toDeliveryDate").val(l.toDeliveryDate || ""), k("#filter-supplier input").val(l.customerNo || ""), k("#filter-goods input").val(l.goodsNo || ""), l.beginDate && l.endDate && (k("#selected-period").text(l.beginDate + "至" + l.endDate), k("div.grid-subtitle").text("日期: " + l.beginDate + " 至 " + l.endDate)), k("#filter-fromDate, #filter-toDate, #filter-fromDeliveryDate, #filter-toDeliveryDate").datepicker();
		var a = k("#status-wrap").cssCheckbox();
		$_matchCon = k("#matchCon"), $_matchCon.placeholder();
		var b = Business.categoryCombo(k("#catorage"), {
			editable: !1,
			extraListHtml: "",
			addOptions: {
				value: -1,
				text: "选择供应商类别"
			},
			defaultSelected: 0,
			trigger: !0,
			width: 112
		}, "supplytype");
		k("#filter-submit").on("click", function(c) {
			c.preventDefault();
			var d = k("#filter-fromDate").val(),
				e = k("#filter-toDate").val(),
				f = k("#filter-fromDeliveryDate").val(),
				g = k("#filter-toDeliveryDate").val(),
				h = "请输入单号查询" === $_matchCon.val() ? "" : k.trim($_matchCon.val()),
				i = b ? b.getValue() : -1;
			return d && e && new Date(d).getTime() > new Date(e).getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (l = {
				beginDate: d,
				endDate: e,
				beginDeliveryDate: f,
				endDeliveryDate: g,
				customerNo: k("#filter-supplier input").val() || "",
				goodsNo: k("#filter-goods input").val() || "",
				billNo: h,
				categoryId: i,
				status: a.chkVal().join()
			}, k("#selected-period").text(d + "至" + e), k("div.grid-subtitle").text("日期: " + d + " 至 " + e), j(), void k("#filter-menu").removeClass("ui-btn-menu-cur"))
		}), k("#filter-reset").on("click", function(b) {
			b.preventDefault(), k("#filter-fromDate").val(l.beginDate), k("#filter-toDate").val(l.endDate), k("#filter-fromDeliveryDate").val(l.fromDeliveryDate), k("#filter-toDeliveryDate").val(l.toDeliveryDate), k("#filter-supplier input").val(""), k("#filter-goods input").val(""), a.chkNot()
		})
	}
	function e() {
		k("#refresh").on("click", function(a) {
			a.preventDefault(), k("#filter-submit").click()
		}), k("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("PURCHASEORDER_PRINT") && k("div.ui-print").printTable()
		}), k("#btn-export").click(function(a) {
			a.preventDefault(), Business.verifyRight("PURCHASEORDER_EXPORT") && Business.getFile("../report/purchaseOrder.do?action=detailExporter", l)
		}), k("#config").show().click(function(a) {
			n.config()
		})
	}
	function f() {
		var a = k(window).height() - k(".grid-wrap").offset().top - 65 - 70,
			b = [{
				name: "invNo",
				label: "商品编号",
				frozen: !0,
				width: 80,
				sortable: !0
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
				width: 60
			}, {
				name: "unit",
				label: "单位",
				width: 50,
				align: "center"
			}, {
				name: "date",
				label: "订单日期",
				width: 80,
				align: "center"
			}, {
				name: "billNo",
				label: "采购订单编号",
				width: 120,
				align: "center",
				sortable: !0
			}, {
				name: "billId",
				label: "采购订单ID",
				width: 0,
				hidden: !0
			}, {
				name: "buName",
				label: "供应商",
				width: 150,
				sortable: !0
			}, {
				name: "status",
				label: "状态",
				width: 60
			}, {
				name: "qty",
				label: "数量",
				width: 80,
				align: "right"
			}, {
				name: "amount",
				label: "采购额",
				width: 100,
				align: "right"
			}, {
				name: "unQty",
				label: "未入库数量",
				width: 80,
				align: "right"
			}, {
				name: "deliveryDate",
				label: "预计交货日期",
				width: 80,
				align: "center",
				sortable: !0
			}, {
				name: "inDate",
				label: "入库日期",
				width: 80,
				align: "center"
			}, {
				name: "description",
				label: "备注",
				width: 180,
				align: "center"
			}],
			c = "local",
			d = "#";
		l.autoSearch && (c = "json", d = m), n.gridReg("grid", b), b = n.conf.grids.grid.colModel, k("#grid").jqGrid({
			url: d,
			postData: l,
			datatype: c,
			autowidth: !0,
			height: a,
			gridview: !0,
			colModel: b,
			cmTemplate: {
				title: !1,
				sortable: !1
			},
			page: 1,
			sortname: "date",
			sortorder: "desc",
			rowNum: 3e3,
			viewrecords: !0,
			shrinkToFit: !1,
			forceFit: !0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				userdata: "data.userdata",
				repeatitems: !1,
				id: "0"
			},
			ondblClickRow: function(a) {
				var b = k("#grid").getRowData(a).billId;
				b && parent.tab.addTabItem({
					tabid: "purchase-purchaseOrder",
					text: "采购订单",
					url: "../purchase/purchaseOrder?id=" + b
				})
			},
			loadComplete: function(a) {
				var b;
				if (a && a.data) {
					var c = a.data.rows.length;
					b = c ? 31 * c : 1
				}
				g(b)
			},
			gridComplete: function() {
				var a = k("#grid").find('td[aria-describedby="grid_invNo"]');
				a.each(function(a) {
					var b = k(this);
					"&nbsp;" === b.html() && b.parent().addClass("fb")
				})
			},
			resizeStop: function(a, b) {
				n.setGridWidthByIndex(a, b + 1, "grid")
			}
		}), l.autoSearch ? (k(".no-query").remove(), k(".ui-print").show()) : k(".ui-print").hide()
	}
	function g(a) {
		a && (g.h = a);
		var b = h(),
			c = k(window).height() - k(".grid-wrap").offset().top - 65 - 70,
			d = (i(), k("#grid"));
		k("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		}), d.jqGrid("setGridHeight", c), d.jqGrid("setGridWidth", b)
	}
	function h() {
		return k(window).width() - (h.offsetLeft || (h.offsetLeft = k("#grid-wrap").offset().left)) - 36 - 20
	}
	function i() {
		return k(window).height() - (i.offsetTop || (i.offsetTop = k("#grid").offset().top)) - 36 - 16
	}
	function j() {
		k(".no-query").remove(), k(".ui-print").show(), k("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: l,
			url: m
		}).trigger("reloadGrid")
	}
	var k = a("jquery"),
		l = (parent.SYSTEM, k.extend({
			beginDate: "",
			endDate: "",
			fromDeliveryDate: "",
			toDeliveryDate: "",
			customerNo: "",
			goodsNo: "",
			status: ""
		}, Public.urlParam()));
	category = null, customCombo = null;
	var m = "../report/purchaseOrder.do?action=detail";
	a("print");
	var n = Public.mod_PageConfig.init("puOrderTracking");
	d(), e(), f();
	var o;
	k(window).on("resize", function(a) {
		o || (o = setTimeout(function() {
			g(), o = null
		}, 50))
	})
});