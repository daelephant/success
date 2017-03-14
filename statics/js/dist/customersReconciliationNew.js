define(["jquery", "print"], function(a) {
	function b() {
		i("#match").cssCheckbox(), chkblank = i("#chk-blank").cssCheckbox(), Business.moreFilterEvent(), i("#date,#saleCustomer,#match").show(), i("#saleCustomer label").text("销货单位"), i("#chk-blank i").text("不显示已收款商品明细"), i("#conditions-trigger").trigger("click"), "true" == k.showDetail && (i("#match").find("label").addClass("checked"), i("#match").find("input")[0].checked = !0), k.beginDate && k.endDate && i("div.grid-subtitle").text("日期: " + k.beginDate + "至" + k.endDate), i("#filter-fromDate").val(k.beginDate), i("#filter-toDate").val(k.endDate), i("#customerSale input").val(k.customerName), Business.customerCombo(i("#customerSale"), {
			width: 140,
			defaultSelected: 0,
			addOptions: {
				text: p,
				value: 0
			}
		}), Public.dateCheck();
		var a = new Pikaday({
			field: i("#filter-fromDate")[0]
		}),
			b = new Pikaday({
				field: i("#filter-toDate")[0]
			});
		i("#match").on("click", function() {
			i("#match span").hasClass("checked") ? i("#chk-blank").show() : i("#chk-blank").hide(), i(window).resize()
		}), i("#filter-submit").on("click", function(c) {
			c.preventDefault();
			var d = i("#customerSale input").val();
			if (d === p || "" === d) return void parent.Public.tips({
				type: 1,
				content: p
			});
			var e = i("#filter-fromDate").val(),
				f = i("#filter-toDate").val(),
				g = a.getDate(),
				j = b.getDate(),
				l = window.THISPAGE.$_customer.data("contactInfo").id || "",
				n = window.THISPAGE.$_customer.data("contactInfo").name || "",
				o = i("#match").find("input")[0].checked ? "true" : "false",
				q = i("#match").find("input")[0].checked ? !0 : !1;
			return g.getTime() > j.getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (k = {
				beginDate: e,
				endDate: f,
				customerId: l,
				customerName: n,
				showDetail: o,
				showBlank: chkblank.chkVal().length > 0 ? "1" : "0"
			}, Public.ajaxPost(m, k, function(a) {
				var b = a.data,
					c = b.name || "",
					d = b.telephone || "",
					g = b.mobile || "",
					h = b.province || "",
					j = b.city || "",
					k = b.county || "",
					l = b.deliveryAddress || "";
				c = "<i>联系人：" + c, d = "</i><i>电话：" + d + "/" + g, address = "</i></p><p>地址：" + h + j + k + l, date = "<i class='fr'>日期: " + e + " 至 " + f + "</i></p>", i("div.grid-subtitle").html("<p>接收单位：" + n + c + d + address + date)
			}), void h(q))
		})
	}
	function c() {
		i("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("CUSTOMERBALANCE_PRINT") && i("div.ui-print").printTable()
		}), i("#btn-export").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("CUSTOMERBALANCE_EXPORT")) {
				var b = {};
				for (var c in k) k[c] && (b[c] = k[c]);
				Business.getFile(l, b)
			}
		}), i("#customerSale").on("click", ".ui-icon-ellipsis", function() {
			if (i(this).data("hasInstance")) this.customerDialog.show().zindex();
			else {
				var a = i("#customerSale").prev().text(),
					b = "选择" + a;
				if ("供应商" === a || "出库单位" === a) var c = "url:../settings/select_customer?type=10&multiselect=false";
				else var c = "url:../settings/select_customer?multiselect=false";
				this.customerDialog = i.dialog({
					width: 775,
					height: 510,
					title: b,
					content: c,
					data: {
						isDelete: 2
					},
					lock: !0,
					ok: function() {
						return this.content.callback(), this.hide(), !1
					},
					cancel: function() {
						return this.hide(), !1
					}
				}), i(this).data("hasInstance", !0)
			}
		}), i("#config").show().click(function() {
			q.config()
		})
	}
	function d() {
		var a = !1,
			b = !1,
			c = !1;
		j.isAdmin !== !1 || j.rights.AMOUNT_COSTAMOUNT || (a = !0), j.isAdmin !== !1 || j.rights.AMOUNT_OUTAMOUNT || (b = !0), j.isAdmin !== !1 || j.rights.AMOUNT_INAMOUNT || (c = !0);
		var d = [{
			name: "date",
			label: "单据日期",
			width: 80,
			align: "center"
		}, {
			name: "billNo",
			label: "单据编号",
			width: 200,
			align: "center"
		}, {
			name: "transType",
			label: "业务类别",
			width: 60,
			align: "center"
		}, {
			name: "invNo",
			label: "商品编号",
			width: 50,
			align: "center"
		}, {
			name: "invName",
			label: "商品名称",
			width: 100,
			align: "center"
		}, {
			name: "spec",
			label: "规格型号",
			width: 120,
			align: "center"
		}, {
			name: "unit",
			label: "单位",
			width: 60,
			align: "center"
		}, {
			name: "qty",
			label: "数量",
			width: 80,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.qtyPlaces)
			}
		}, {
			name: "price",
			label: "单价",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.pricePlaces)
			}
		}, {
			name: "totalAmount",
			label: "出库金额",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.amountPlaces)
			}
		}, {
			name: "disAmount",
			label: "整单折扣额",
			width: 80,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.amountPlaces)
			}
		}, {
			name: "amount",
			label: "应收金额",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.amountPlaces)
			}
		}, {
			name: "rpAmount",
			label: "实际收款金额",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.amountPlaces)
			}
		}, {
			name: "inAmount",
			label: "应收款余额",
			width: 120,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.amountPlaces)
			}
		}, {
			name: "billId",
			label: "",
			width: 0,
			hidden: !0
		}, {
			name: "billType",
			label: "",
			width: 0,
			hidden: !0
		}],
			f = "local",
			g = "#";
		k.autoSearch && (f = "json", g = m), q.gridReg("grid", d), d = q.conf.grids.grid.colModel, i("#grid").jqGrid({
			url: g,
			postData: k,
			datatype: f,
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
				root: "data.list",
				userdata: "data.total",
				repeatitems: !1,
				id: "0"
			},
			onCellSelect: function(a) {
				var b = i("#grid").getRowData(a),
					c = b.billId,
					d = b.billType.toUpperCase();
				switch (d) {
				case "PUR":
					if (!Business.verifyRight("PU_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "purchase-purchase",
						text: "出库单",
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
					b = c ? 31 * c : "auto"
				}
				e(b)
			},
			gridComplete: function() {
				i("#grid").footerData("set", {
					transType: "合计:"
				})
			},
			resizeStop: function(a, b) {
				q.setGridWidthByIndex(a, b + 1, "grid")
			}
		}), k.autoSearch ? (i(".no-query").remove(), i(".ui-print").show()) : i(".ui-print").hide()
	}
	function e(a) {
		a && (e.h = a);
		var b = f(),
			c = e.h,
			d = g(),
			h = i("#grid");
		c > d && (c = d), b < h.width() && (c += 17), h.jqGrid("setGridWidth", b, !1), h.jqGrid("setGridHeight", c), i("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		})
	}
	function f() {
		return i(window).width() - (f.offsetLeft || (f.offsetLeft = i("#grid-wrap").offset().left)) - 36 - 20
	}
	function g() {
		return i(window).height() - (g.offsetTop || (g.offsetTop = i("#grid").offset().top)) - 36 - 16 - 24
	}
	function h(a) {
		i(".no-query").remove(), i(".ui-print").show(), "undefined" != typeof a && (i("#grid").jqGrid(a ? "showCol" : "hideCol", ["invNo", "invName", "spec", "unit", "qty", "price"]), e()), i("#grid").clearGridData(!0), i("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: k,
			url: m
		}).trigger("reloadGrid")
	}
	var i = a("jquery"),
		j = parent.SYSTEM,
		k = i.extend({
			beginDate: "",
			endDate: "",
			customerId: "",
			customerName: "",
			showDetail: ""
		}, Public.urlParam());
	Business.getSearchList();
	var l = "../report/customerBalance.do?action=exporter",
		m = "../report/customerBalance.do?action=detail",
		n = i("#customerSale"),
		o = (i("#match"), i("#match").find("input"), o || {});
	o.$_customer = n, this.THISPAGE = o;
	var p = "（请选择出库单位）";
	a("print");
	var q = Public.mod_PageConfig.init("customersReconciliationNew");
	b(), c(), d();
	var r;
	i(window).on("resize", function() {
		r || (r = setTimeout(function() {
			e(), r = null
		}, 50))
	})
});