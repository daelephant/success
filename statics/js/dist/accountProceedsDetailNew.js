define(["jquery", "print"], function(a, b, c) {
	function d() {
		Business.getSearchList(), Business.filterCustomer(), Business.moreFilterEvent(), k("#date,#filter,#customer,#chk-blank").show(), k("#filter label").text("接收类别"), k("#chk-blank i").text("不显示无欠款接收单位"), k("#conditions-trigger").trigger("click"), k("#filter-fromDate").val(m.beginDate || ""), k("#filter-toDate").val(m.endDate || ""), k("#filter-customer input").val(m.customerNo || ""), m.beginDate && m.endDate && k("div.grid-subtitle").text("日期: " + m.beginDate + " 至 " + m.endDate), k("#filter-fromDate, #filter-toDate").datepicker(), Public.dateCheck(), chkblank = k("#chk-blank").cssCheckbox();
		var a = Business.categoryCombo(k("#catorage"), {
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
		k("#filter-submit").on("click", function(b) {
			b.preventDefault();
			var c = k("#filter-fromDate").val(),
				d = k("#filter-toDate").val(),
				e = a ? a.getValue() : -1;
			return c && d && new Date(c).getTime() > new Date(d).getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (m = {
				beginDate: c,
				endDate: d,
				accountNo: k("#customerAuto").val() || "",
				categoryId: e,
				showBlank: chkblank.chkVal().length > 0 ? "1" : "0"
			}, k("div.grid-subtitle").text("日期: " + c + " 至 " + d), void j())
		})
	}
	function e() {
		k("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("RECEIPTDETAIL_PRINT") && k("div.ui-print").printTable()
		}), k("#btn-export").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("RECEIPTDETAIL_EXPORT")) {
				var b = {};
				for (var c in m) m[c] && (b[c] = m[c]);
				Business.getFile(n, b)
			}
		}), k("#config").show().click(function(a) {
			p.config()
		})
	}
	function f() {
		var a = !1,
			b = !1,
			c = !1;
		l.isAdmin !== !1 || l.rights.AMOUNT_COSTAMOUNT || (a = !0), l.isAdmin !== !1 || l.rights.AMOUNT_OUTAMOUNT || (b = !0), l.isAdmin !== !1 || l.rights.AMOUNT_INAMOUNT || (c = !0);
		var d = (l.serviceType, [{
			name: "buName",
			label: "接收单位",
			width: 150,
			align: "center"
		}, {
			name: "date",
			label: "单据日期",
			width: 100,
			align: "center"
		}, {
			name: "billNo",
			label: "单据编号",
			width: 110,
			align: "center"
		}, {
			name: "transType",
			label: "业务类型",
			width: 110,
			align: "center"
		}, {
			name: "income",
			label: "增加应收款",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(l.amountPlaces)
			}
		}, {
			name: "expenditure",
			label: "增加预收款",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(l.amountPlaces)
			}
		}, {
			name: "balance",
			label: "应收款余额",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(l.amountPlaces)
			}
		}, {
			name: "description",
			label: "备注",
			width: 210,
			align: "center"
		}, {
			name: "billId",
			label: "",
			width: 0,
			align: "center",
			hidden: !0
		}, {
			name: "billTypeNo",
			label: "",
			width: 0,
			align: "center",
			hidden: !0
		}]),
			e = "local",
			f = "#";
		m.autoSearch && (e = "json", f = o), p.gridReg("grid", d), d = p.conf.grids.grid.colModel, k("#grid").jqGrid({
			url: f,
			postData: m,
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
				root: "data.list",
				userdata: "data.total",
				repeatitems: !1,
				id: "0"
			},
			onCellSelect: function(a) {

				var b = k("#grid").getRowData(a),
					c = b.billId,
					d = b.billTypeNo.toUpperCase();
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
				case "PAYMENT":
					if (!Business.verifyRight("PAYMENT_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "money-payment",
						text: "付款单",
						url: "../money/payment?id=" + c
					});
					break;
				case "VERIFICA":
					if (!Business.verifyRight("VERIFICA_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "money-verifica",
						text: "核销单",
						url: "../money/verification?id=" + c
					});
					break;
				case "RECEIPT":
					if (!Business.verifyRight("RECEIPT_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "money-receipt",
						text: "收款单",
						url: "../money/receipt?id=" + c
					});
					break;
				case "QTSR":
					if (!Business.verifyRight("QTSR_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "money-otherIncome",
						text: "其它收入单",
						url: "../money/other_income?id=" + c
					});
					break;
				case "QTZC":
					if (!Business.verifyRight("QTZC_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "money-otherExpense",
						text: "其它支出单",
						url: "../money/other_expense?id=" + c
					})
				}
			},
			loadComplete: function(a) {
				var b;
				if (a && a.data) {
					var c = a.data.list.length;
					b = c ? 31 * c : 1
				}
				g(b)
			},
			gridComplete: function() {
				k("#grid").footerData("set", {
					transType: "合计:"
				})
			},
			resizeStop: function(a, b) {
				p.setGridWidthByIndex(a, b + 1, "grid")
			}
		}), m.autoSearch ? (k(".no-query").remove(), k(".ui-print").show()) : k(".ui-print").hide()
	}
	function g(a) {
		a && (g.h = a);
		var b = h(),
			c = g.h,
			d = i(),
			e = k("#grid");
		c > d && (c = d), b < e.width() && (c += 17), k("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		}), e.jqGrid("setGridHeight", c), e.jqGrid("setGridWidth", b, !1)
	}
	function h() {
		return k(window).width() - k("#grid-wrap").offset().left - 36 - 20
	}
	function i() {
		return k(window).height() - k("#grid").offset().top - 36 - 16
	}
	function j() {
		k(".no-query").remove(), k(".ui-print").show(), k("#grid").clearGridData(!0), k("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: m,
			url: o
		}).trigger("reloadGrid")
	}
	var k = a("jquery"),
		l = parent.SYSTEM,
		m = k.extend({
			beginDate: "",
			endDate: "",
			accountNo: ""
		}, Public.urlParam()),
		n = "../report/fundBalance.do?action=exporter",
		o = "../report/fundBalance.do?action=detail";
	a("print");
	var p = Public.mod_PageConfig.init("accountProceeDetailNew");
	d(), e(), f();
	var q;
	k(window).on("resize", function(a) {
		q || (q = setTimeout(function() {
			g(), q = null
		}, 50))
	})
});