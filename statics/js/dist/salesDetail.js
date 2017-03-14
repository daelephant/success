define(["jquery", "print"], function(a) {
	function b() {
		Business.getSearchList(), Business.filterCustomer(), Business.filterGoods(), Business.filterStorage(), Business.filterSaler(), Business.moreFilterEvent(), j("#date,#customer,#goods,#sales,#remarks,#billNum,#filter,#storage,#chk-wrap").show(), j("#billNum label").text("单据编号:"), j("#filter label").text("接收类别"), j("#chk-wrap .chk:eq(1)").hide(), j("#conditions-trigger").trigger("click"), j("#filter-fromDate").val(l.beginDate || ""), j("#filter-toDate").val(l.endDate || ""), j("#filter-customer input").val(l.customerNo || ""), j("#filter-goods input").val(l.goodsNo || ""), j("#filter-storage input").val(l.storageNo || ""), j("#filter-saler input").val(l.salesId || ""), l.beginDate && l.endDate && (j("#selected-period").text(l.beginDate + "至" + l.endDate), j("div.grid-subtitle").text("日期: " + l.beginDate + " 至 " + l.endDate)), j("#filter-fromDate, #filter-toDate").datepicker(), Public.dateCheck();
		var a = parent.SYSTEM;
		a.rights.SAREPORTDETAIL_COST || a.isAdmin ? (j("#chk-wrap").show(), "1" === l.profit && j("#chk-wrap input").attr("checked", !0)) : j("#chk-wrap").hide();
		var b = j("#chk-wrap").cssCheckbox(),
			c = (j("#status-wrap").cssCheckbox(), j("#matchCon")),
			d = j("#remarkCon");
		c.placeholder(), d.placeholder();
		var e = Business.categoryCombo(j("#catorage"), {
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
		j("#filter-submit").on("click", function(a) {
			a.preventDefault();
			var f = j("#filter-fromDate").val(),
				g = j("#filter-toDate").val(),
				h = e ? e.getValue() : -1,
				k = "请输入单号查询" === c.val() ? "" : j.trim(c.val()),
				m = "请输入备注查询" === d.val() ? "" : j.trim(c.val());
			return f && g && new Date(f).getTime() > new Date(g).getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (l = {
				beginDate: f,
				endDate: g,
				customerNo: j("#filter-customer input").val() || "",
				goodsNo: j("#filter-goods input").val() || "",
				storageNo: j("#filter-storage input").val() || "",
				salesId: j("#filter-saler input").val() || "",
				profit: b.chkVal().length > 0 ? "1" : "0",
				remarkCon: m,
				billNo: k,
				categoryId: h
			}, j("#selected-period").text(f + "至" + g), j("div.grid-subtitle").text("日期: " + f + " 至 " + g), void i(+l.profit))
		}), j("#filter-reset").on("click", function(a) {
			a.preventDefault(), j("#filter-fromDate").val(l.beginDate), j("#filter-toDate").val(l.endDate), j("#filter-customer input").val(""), j("#filter-goods input").val(""), j("#filter-storage input").val(""), j("#filter-saler input").val(""), b.chkNot()
		})
	}
	function c() {
		var a = l.customer ? l.customer.split(",") : "",
			b = l.goods ? l.goods.split(",") : "",
			c = "";
		a && b ? c = "「您已选择了<b>" + a.length + "</b>个接收单位，<b>" + b.length + "</b>个商品进行查询」" : a ? c = "「您已选择了<b>" + a.length + "</b>个接收单位进行查询」" : b && (c = "「您已选择了<b>" + b.length + "</b>个商品进行查询」"), j("#cur-search-tip").html(c)
	}
	function d() {
		j("#refresh").on("click", function(a) {
			a.preventDefault(), i()
		}), j("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("SAREPORTDETAIL_PRINT") && j("div.ui-print").printTable()
		}), j("#btn-export").click(function(a) {
			a.preventDefault(), Business.verifyRight("SAREPORTDETAIL_EXPORT") && Business.getFile("../report/salesDetail.do?action=detailExporter", l)
		}), j("#config").show().click(function() {
			m.config()
		})
	}
	function e() {
		var a = !1,
			b = !0,
			c = !1;
		k.isAdmin !== !1 || k.rights.AMOUNT_OUTAMOUNT || (a = !0, c = !0), "1" === l.profit && (b = !1), 0 === k.taxRequiredCheck && (c = !0);
		var d = [{
			name: "date",
			label: "出库日期",
			width: 80,
			fixed: !0,
			align: "center"
		}, {
			name: "billId",
			label: "出库ID",
			width: 0,
			hidden: !0
		}, {
			name: "billNo",
			label: "出库单据号",
			width: 110,
			fixed: !0,
			align: "center"
		}, {
			name: "transType",
			label: "业务类别",
			width: 60,
			fixed: !0,
			align: "center"
		}, {
			name: "salesName",
			label: "出库人员",
			width: 80
		}, {
			name: "buName",
			label: "接收单位",
			width: 150,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "invNo",
			label: "商品编号",
			width: 100
		}, {
			name: "invName",
			label: "商品名称",
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
			fixed: !0,
			align: "center"
		}, {
			name: "location",
			label: "仓库",
			width: 60,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "qty",
			label: "数量",
			width: 100,
			fixed: !0,
			align: "right"
		}, {
			name: "unitPrice",
			label: "单价",
			width: 100,
			fixed: !0,
			hidden: a,
			align: "right"
		}, {
			name: "amount",
			label: "出库收入",
			width: 100,
			fixed: !0,
			hidden: a,
			align: "right"
		}, {
			name: "tax",
			label: "税额",
			width: 100,
			align: "right",
			hidden: c
		}, {
			name: "taxAmount",
			label: "价税合计",
			width: 100,
			align: "right",
			hidden: c
		}, {
			name: "unitCost",
			label: "单位成本",
			width: 80,
			fixed: !0,
			hidden: b,
			align: "right"
		}, {
			name: "cost",
			label: "出库成本",
			width: 80,
			fixed: !0,
			hidden: b,
			align: "right"
		}, {
			name: "saleProfit",
			label: "出库毛利",
			width: 80,
			fixed: !0,
			hidden: b,
			align: "right"
		}, {
			name: "salepPofitRate",
			label: "毛利率",
			width: 80,
			fixed: !0,
			hidden: b,
			align: "right"
		}, {
			name: "description",
			label: "设备编号",
			width: 150
		}],
			e = "local",
			g = "#";
		l.autoSearch && (e = "json", g = "../report/salesDetail.do?action=detail"), m.gridReg("grid", d), d = m.conf.grids.grid.colModel, j("#grid").jqGrid({
			url: g,
			postData: l,
			datatype: e,
			autowidth: !0,
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
			cellLayout: 0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				userdata: "data.userdata",
				repeatitems: !1,
				id: "0"
			},
			ondblClickRow: function(a) {
				if (Business.verifyRight("SA_QUERY")) {
					var b = j("#grid").getRowData(a).billId;
					parent.tab.addTabItem({
						tabid: "sales-sales",
						text: "出库单",
						url: "../sales/index?id=" + b
					})
				}
			},
			loadComplete: function(a) {
				var b;
				if (a && a.data) {
					var c = a.data.rows.length;
					b = c ? 31 * c : 1
				}
				f(b)
			},
			gridComplete: function() {
				j("#grid").footerData("set", {
					location: "合计:"
				}), j("table.ui-jqgrid-ftable").find('td[aria-describedby="grid_location"]').prevUntil().css("border-right-color", "#fff")
			},
			resizeStop: function(a, b) {
				m.setGridWidthByIndex(a, b + 1, "grid")
			}
		}), l.autoSearch ? (j(".no-query").remove(), j(".ui-print").show()) : j(".ui-print").hide()
	}
	function f(a) {
		a && (f.h = a);
		var b = g(),
			c = f.h,
			d = h(),
			e = j("#grid");
		c > d && (c = d), b < e.width() && (c += 17), j("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		}), e.jqGrid("setGridHeight", c), e.jqGrid("setGridWidth", b, !1)
	}
	function g() {
		return j(window).width() - j("#grid-wrap").offset().left - 36 - 20
	}
	function h() {
		return j(window).height() - j("#grid").offset().top - 36 - 16
	}
	function i(a) {
		j(".no-query").remove(), j(".ui-print").show(), "number" == typeof a && (j("#grid").jqGrid(a ? "showCol" : "hideCol", ["unitCost", "cost", "saleProfit", "salepPofitRate"]), f(), j("#grid").clearGridData(!0)), j("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: l,
			url: "../report/salesDetail.do?action=detail"
		}).trigger("reloadGrid")
	}
	var j = a("jquery"),
		k = parent.SYSTEM,
		l = j.extend({
			beginDate: "",
			endDate: "",
			customerNo: "",
			goodsNo: "",
			storageNo: "",
			profit: "0",
			salesId: ""
		}, Public.urlParam());
	a("print");
	var m = Public.mod_PageConfig.init("salesDetail");
	b(), c(), d(), e();
	var n;
	j(window).on("resize", function() {
		n || (n = setTimeout(function() {
			f(), n = null
		}, 50))
	})
});