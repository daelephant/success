define(["jquery", "print"], function(a, b, c) {
	function d() {
		Business.getSearchList(), Business.filterCustomer(), Business.filterGoods(), Business.filterSaler(), Business.moreFilterEvent(), k("#date,#customer,#goods,#sales,#billNum,#deliveryDate,#filter,#status-wrap").show(), k("#billNum label").text("订单号"), k("#filter label").text("接收类别"), k(".chk i:eq(0)").text("未出库"), k(".chk i:eq(1)").text("部分出库"), k(".chk i:eq(2)").text("已出库"), k("#conditions-trigger").trigger("click"), k("#filter-fromDate").val(l.beginDate || ""), k("#filter-toDate").val(l.endDate || ""), k("#filter-fromDeliveryDate").val(l.fromDeliveryDate || ""), k("#filter-toDeliveryDate").val(l.toDeliveryDate || ""), k("#filter-customer input").val(l.customerNo || ""), k("#filter-goods input").val(l.goodsNo || ""), l.beginDate && l.endDate && (k("#selected-period").text(l.beginDate + "至" + l.endDate), k("div.grid-subtitle").text("日期: " + l.beginDate + " 至 " + l.endDate)), k("#filter-fromDate, #filter-toDate, #filter-fromDeliveryDate, #filter-toDeliveryDate").datepicker();
		var a = k("#status-wrap").cssCheckbox(),
			b = k("#matchCon");
		b.placeholder();
		var c = Business.categoryCombo(k("#catorage"), {
			editable: !1,
			extraListHtml: "",
			addOptions: {
				value: -1,
				text: "选择接收类别"
			},
			defaultSelected: 0,
			trigger: !0,
			width: 112
		}, "customertype");
		k("#filter-submit").on("click", function(d) {
			d.preventDefault();
			var e = k("#filter-fromDate").val(),
				f = k("#filter-toDate").val(),
				g = k("#filter-fromDeliveryDate").val(),
				h = k("#filter-toDeliveryDate").val(),
				i = "请输入单号查询" === b.val() ? "" : k.trim(b.val()),
				m = c ? c.getValue() : -1;
			return e && f && new Date(e).getTime() > new Date(f).getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (l = {
				beginDate: e,
				endDate: f,
				beginDeliveryDate: g,
				endDeliveryDate: h,
				customerNo: k("#filter-customer input").val() || "",
				goodsNo: k("#filter-goods input").val() || "",
				status: a.chkVal().join(),
				salesId: k("#filter-saler input").val() || "",
				billNo: i,
				categoryId: m
			}, k("#selected-period").text(e + "至" + f), k("div.grid-subtitle").text("日期: " + e + " 至 " + f), j(), void k("#filter-menu").removeClass("ui-btn-menu-cur"))
		}), k("#filter-reset").on("click", function(b) {
			b.preventDefault(), k("#filter-fromDate").val(l.beginDate), k("#filter-toDate").val(l.endDate), k("#filter-fromDeliveryDate").val(l.fromDeliveryDate), k("#filter-toDeliveryDate").val(l.toDeliveryDate), k("#filter-customer input").val(""), k("#filter-goods input").val(""), k("#filter-saler input").val(""), a.chkNot()
		})
	}
	function e() {
		k("#refresh").on("click", function(a) {
			a.preventDefault(), k("#filter-submit").click()
		}), 
		k("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("SALESORDER_PRINT") && k("div.ui-print").printTable()
		}), 
		k("#btn-export").click(function(a) {
			a.preventDefault(), Business.verifyRight("SALESORDER_EXPORT") && Business.getFile(m, l)
		}), k("#config").show().click(function(a) {
			o.config()
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
				label: "出库订单编号",
				width: 120,
				align: "center",
				sortable: !0
			}, {
				name: "billId",
				label: "出库订单ID",
				width: 0,
				hidden: !0
			}, {
				name: "salesName",
				label: "出库人员",
				width: 80,
				sortable: !0
			}, {
				name: "buName",
				label: "接收单位",
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
				label: "出库额",
				width: 100,
				align: "right"
			}, {
				name: "unQty",
				label: "未出库数量",
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
				label: "出库日期",
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
		l.autoSearch && (c = "json", d = n), o.gridReg("grid", b), b = o.conf.grids.grid.colModel, k("#grid").jqGrid({
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
			loadonce: !1,
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
					tabid: "sales-salesOrder",
					text: "出库订单",
					url: "../sales/salesOrder?id=" + b
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
				o.setGridWidthByIndex(a, b + 1, "grid")
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
			url: n
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
		}, Public.urlParam())),
		m = "../report/salesOrder.do?action=detailExporter",
		n = "../report/salesOrder.do?action=detail";
	a("print");
	var o = Public.mod_PageConfig.init("salesOrderTracking");
	d(), e(), f();
	var p;
	k(window).on("resize", function(a) {
		p || (p = setTimeout(function() {
			g(), p = null
		}, 50))
	})
});