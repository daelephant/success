define(["jquery", "print"], function(a) {
	function b() {
		Business.getSearchList(), Business.filterSupplier(), Business.filterGoods(), Business.filterStorage(), Business.moreFilterEvent(), i("#date,#supplier,#goods,#storage,#remarks,#billNum,#filter").show(), i("#billNum label").text("单据编号"), i("#filter label").text("供应商类别"), i("#conditions-trigger").trigger("click"), i("#filter-fromDate").val(k.beginDate || ""), i("#filter-toDate").val(k.endDate || ""), i("#filter-supplier input").val(k.customerNo || ""), i("#filter-goods input").val(k.goodsNo || ""), i("#filter-storage input").val(k.storageNo || ""), k.beginDate && k.endDate && (i("#selected-period").text(k.beginDate + "至" + k.endDate), i("div.grid-subtitle").text("日期: " + k.beginDate + " 至 " + k.endDate)), i("#filter-fromDate, #filter-toDate").datepicker();
		var a = i("#matchCon"),
			b = i("#remarkCon");
		a.placeholder(), b.placeholder();
		var c = Business.categoryCombo(i("#catorage"), {
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
		i("#filter-submit").on("click", function(d) {
			d.preventDefault();
			var e = i("#filter-fromDate").val(),
				f = i("#filter-toDate").val(),
				g = "请输入单号查询" === a.val() ? "" : i.trim(a.val()),
				j = "请输入备注查询" === b.val() ? "" : i.trim(a.val()),
				l = c ? c.getValue() : -1;
			return e && f && new Date(e).getTime() > new Date(f).getTime() ? void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}) : (k = {
				beginDate: e,
				endDate: f,
				customerNo: i("#filter-supplier input").val() || "",
				goodsNo: i("#filter-goods input").val() || "",
				storageNo: i("#filter-storage input").val() || "",
				remarkCon: j,
				billNo: g,
				categoryId: l
			}, i("#selected-period").text(e + "至" + f), i("div.grid-subtitle").text("日期: " + e + " 至 " + f), h(), void i("#filter-menu").removeClass("ui-btn-menu-cur"))
		}), i("#filter-reset").on("click", function(a) {
			a.preventDefault(), i("#filter-fromDate").val(k.beginDate), i("#filter-toDate").val(k.endDate), i("#filter-supplier input").val(""), i("#filter-goods input").val(""), i("#filter-storage input").val(""), k.customerNo = "", k.goodsNo = "", k.storageNo = ""
		})
	}
	function c() {
		i("#refresh").on("click", function(a) {
			a.preventDefault(), i("#filter-submit").click()
		}), i("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("PUREOORTDETAIL_PRINT") && i("div.ui-print").printTable()
		}), i("#btn-export").click(function(a) {
			a.preventDefault(), Business.verifyRight("PUREOORTDETAIL_EXPORT") && Business.getFile(l, k)
		}), i("#config").show().click(function() {
			n.config()
		})
	}
	function d() {
		var a = !1,
			b = !1,
			c = !1,
			d = !1;
		j.isAdmin !== !1 || j.rights.AMOUNT_COSTAMOUNT || (a = !0), j.isAdmin !== !1 || j.rights.AMOUNT_OUTAMOUNT || (b = !0), j.isAdmin !== !1 || j.rights.AMOUNT_INAMOUNT || (c = !0, d = !0), 0 === j.taxRequiredCheck && (d = !0);
		var f = [{
			name: "date",
			label: "采购日期",
			width: 80,
			align: "center"
		}, {
			name: "billNo",
			label: "入库单据号",
			width: 200,
			align: "center"
		}, {
			name: "transType",
			label: "业务类别",
			width: 60,
			align: "center"
		}, {
			name: "buName",
			label: "供应商",
			width: 100,
			align: "center"
		}, {
			name: "invNo",
			label: "商品编号",
			width: 80,
			align: "center"
		}, {
			name: "invName",
			label: "商品名称",
			width: 120,
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
			name: "location",
			label: "仓库",
			width: 100,
			align: "center"
		}, {
			name: "qty",
			label: "数量",
			width: 100,
			align: "right",
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.qtyPlaces)
			}
		}, {
			name: "unitPrice",
			label: "单价",
			width: 100,
			align: "right",
			hidden: c,
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.pricePlaces)
			}
		}, {
			name: "amount",
			label: "采购金额",
			width: 100,
			align: "right",
			hidden: c,
			formatter: "currency",
			formatoptions: {
				thousandsSeparator: ",",
				decimalPlaces: Number(j.amountPlaces)
			}
		}, {
			name: "tax",
			label: "税额",
			width: 100,
			align: "right",
			hidden: d
		}, {
			name: "taxAmount",
			label: "价税合计",
			width: 100,
			align: "right",
			hidden: d
		}, {
			name: "description",
			label: "设备编号",
			width: 180,
			align: "center"
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
			g = "local",
			h = "#";
		k.autoSearch && (g = "json", h = m), n.gridReg("grid", f), f = n.conf.grids.grid.colModel, i("#grid").jqGrid({
			url: h,
			postData: k,
			datatype: g,
			autowidth: !0,
			gridview: !0,
			colModel: f,
			cmTemplate: {
				sortable: !1,
				title: !1
			},
			page: 1,
			sortname: "date",
			sortorder: "desc",
			rowNum: 1e6,
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
				if (Business.verifyRight("PU_QUERY")) {
					{
						var b = i("#grid").getRowData(a),
							c = b.billId;
						b.billType
					}
					parent.tab.addTabItem({
						tabid: "purchase-purchase",
						text: "入库单",
						url: "../purchase/purchase?id=" + c
					})
				}
			},
			loadComplete: function(a) {
				var b;
				if (a && a.data) {
					var c = a.data.list.length;
					b = c ? 31 * c : 1
				}
				e(b)
			},
			gridComplete: function() {
				i("#grid").footerData("set", {
					location: "合计:"
				}), i("table.ui-jqgrid-ftable").find('td[aria-describedby="grid_location"]').prevUntil().css("border-right-color", "#fff")
			},
			resizeStop: function(a, b) {
				n.setGridWidthByIndex(a, b + 1, "grid")
			}
		}), k.autoSearch ? (i(".no-query").remove(), i(".ui-print").show()) : i(".ui-print").hide()
	}
	function e(a) {
		a && (e.h = a);
		var b = f(),
			c = e.h,
			d = g(),
			h = i("#grid");
		c > d && (c = d), b < h.width() && (c += 17), i("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		}), h.jqGrid("setGridHeight", c), h.jqGrid("setGridWidth", b, !1)
	}
	function f() {
		return i(window).width() - i("#grid-wrap").offset().left - 36 - 20
	}
	function g() {
		return i(window).height() - i("#grid").offset().top - 36 - 16
	}
	function h() {
		i(".no-query").remove(), i(".ui-print").show(), i("#grid").clearGridData(!0), i("#grid").jqGrid("setGridParam", {
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
			customerNo: "",
			goodsNo: "",
			storageNo: ""
		}, Public.urlParam()),
		l = "../report/puDetail.do?action=detailExporter",
		m = "../report/puDetail.do?action=detail";
	a("print");
	var n = Public.mod_PageConfig.init("puDetailNew");
	b(), c(), d();
	var o;
	i(window).on("resize", function() {
		o || (o = setTimeout(function() {
			e(), o = null
		}, 50))
	})
});