define(["jquery", "print"], function(a) {
	function b() {
		Business.getSearchList(), Business.filterCustomer(), Business.filterGoods(), Business.filterStorage(), Business.moreFilterEvent(), i("#date,#customer,#goods,#storage,#goodsfilter,#chk-wrap").show(), i("#goods").insertBefore(i("#customer")), i("#filter label").text("接收类别:"), i("#btn-print").hide(), i("#conditions-trigger").trigger("click"), i("#filter-fromDate").val(k.beginDate || ""), i("#filter-toDate").val(k.endDate || ""), i("#filter-customer input").val(k.customerNo || ""), i("#filter-goods input").val(k.goodsNo || ""), i("#filter-storage input").val(k.storageNo || ""), k.beginDate && k.endDate && (i("#selected-period").text(k.beginDate + "至" + k.endDate), i("div.grid-subtitle").text("日期: " + k.beginDate + " 至 " + k.endDate)), i("#filter-fromDate, #filter-toDate").datepicker(), Public.dateCheck(), "1" === k.profit && i('#chk-wrap input[name="profit"]').attr("checked", !0), "1" === k.showSku && i('#chk-wrap input[name="showSku"]').attr("checked", !0), parent.SYSTEM.enableAssistingProp || i('#chk-wrap input[name="showSku"]').parent().hide();
		var a = parent.SYSTEM;
		a.rights.SAREPORTINV_COST || a.isAdmin ? i('#chk-wrap input[name="profit"]').parent().show() : i('#chk-wrap input[name="profit"]').parent().hide();
		var b = i("#chk-wrap").show().cssCheckbox(),
			c = Public.categoryTree(i("#filterCat"), {
				width: 200
			});
		i("#filter-submit").on("click", function(a) {
			a.preventDefault();
			var d = i("#filter-fromDate").val(),
				e = i("#filter-toDate").val();
			if (d && e && new Date(d).getTime() > new Date(e).getTime()) return void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			});
			k = {
				beginDate: d,
				endDate: e,
				customerNo: i("#filter-customer input").val() || "",
				goodsNo: i("#filter-goods input").val() || "",
				storageNo: i("#filter-storage input").val() || "",
				profit: 0,
				showSku: 0,
				catId: c.getValue(),
				catName: c.getText()
			}, chkVals = b.chkVal();
			for (var f = 0, g = chkVals.length; g > f; f++) k[chkVals[f]] = 1;
			i("#selected-period").text(d + "至" + e), i("div.grid-subtitle").text("日期: " + d + " 至 " + e), h(+k.profit), i("#filter-menu").removeClass("ui-btn-menu-cur")
		}), i("#filter-reset").on("click", function(a) {
			a.preventDefault(), i("#filter-fromDate").val(k.beginDate), i("#filter-toDate").val(k.endDate), i("#filter-customer input").val(""), i("#filter-goods input").val(""), i("#filter-storage input").val(""), b.chkNot()
		})
	}
	function c() {
		i("#refresh").on("click", function(a) {
			a.preventDefault(), i("#filter-submit").trigger("click")
		}), i("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("SAREPORTINV_PRINT") && i("div.ui-print").printTable()
		}), i("#btn-export").click(function(a) {
			a.preventDefault(), Business.verifyRight("SAREPORTINV_EXPORT") && Business.getFile(m, k)
		}), i("#config").show().click(function() {
			n.config()
		})
	}
	function d() {
		var a = !1,
			b = !1;
		j.isAdmin !== !1 || j.rights.AMOUNT_OUTAMOUNT || (a = !0, b = !0), 0 === j.taxRequiredCheck && (b = !0);
		var c = [{
			name: "assistName",
			label: "商品类别",
			width: 80,
			align: "center"
		}, {
			name: "buNo",
			label: "接收单位编码",
			width: 0,
			hidden: !0
		}, {
			name: "invNo",
			label: "商品编号",
			width: 100
		}, {
			name: "locationNo",
			label: "仓库编码",
			width: 0,
			hidden: !0
		}, {
			name: "invName",
			label: "商品名称",
			width: 200,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "spec",
			label: "规格型号",
			width: 100
		}, {
			name: "unit",
			label: "单位",
			width: 80,
			fixed: !0,
			align: "center"
		}, {
			name: "location",
			label: "仓库",
			width: 100,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "qty",
			label: "数量",
			width: 100,
			align: "right",
			sortable: !0
		}, {
			name: "unitPrice",
			label: "单价",
			width: 100,
			hidden: a,
			align: "right"
		}, {
			name: "amount",
			label: "出库收入",
			width: 100,
			hidden: a,
			align: "right",
			sortable: !0
		}, {
			name: "tax",
			label: "税额",
			width: 100,
			align: "right",
			hidden: b
		}, {
			name: "taxAmount",
			label: "价税合计",
			width: 100,
			align: "right",
			hidden: b
		}, {
			name: "unitCost",
			label: "单位成本",
			width: 80,
			hidden: !0,
			align: "right"
		}, {
			name: "cost",
			label: "出库成本",
			width: 80,
			hidden: !0,
			align: "right"
		}, {
			name: "saleProfit",
			label: "出库毛利",
			width: 80,
			hidden: !0,
			align: "right",
			sortable: !0
		}, {
			name: "salepPofitRate",
			label: "毛利率",
			width: 80,
			hidden: !0,
			align: "right"
		}],
			d = "local",
			f = "#";
		k.autoSearch && (d = "json", f = l), n.gridReg("grid", c), c = n.conf.grids.grid.colModel, i("#grid").jqGrid({
			url: f,
			postData: k,
			datatype: d,
			autowidth: !0,
			gridview: !0,
			colModel: c,
			cmTemplate: {
				sortable: !1,
				title: !1
			},
			page: 1,
			sortname: "date",
			sortorder: "desc",
			rowNum: 1e6,
			loadonce: !1,
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
			onCellSelect: function(a) {
				if (Business.verifyRight("SAREPORTDETAIL_QUERY")) {
					var b = i("#grid").getRowData(a),
						c = b.buNo,
						d = b.invNo,
						e = b.locationNo;
					d && parent.tab.addTabItem({
						tabid: "report-salesDetail",
						text: "出库明细表",
						url: "../report/sales_detail?autoSearch=true&beginDate=" + k.beginDate + "&endDate=" + k.endDate + "&customerNo=" + c + "&goodsNo=" + d + "&storageNo=" + e + "&profit=" + k.profit + "&showSku=" + k.showSku
					})
				}
			},
			loadComplete: function(a) {
				var b, c = i("#grid").getGridParam("sortname"),
					d = i("#grid").getGridParam("sortorder");
				if (k.sidx = c, k.sord = d, a && a.data) {
					var f = a.data.rows.length;
					b = f ? 31 * f : 1
				}
				e(b)
			},
			gridComplete: function() {
				i("#grid").footerData("set", {
					location: "合计:"
				});
				var a = i("#grid").find('td[aria-describedby="grid_invNo"]');
				a.each(function() {
					var a = i(this);
					"&nbsp;" === a.html() && a.parent().addClass("fb")
				})
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
	function h(a) {
		i(".no-query").remove(), i(".ui-print").show(), "number" == typeof a && (i("#grid").jqGrid(a ? "showCol" : "hideCol", ["unitCost", "cost", "saleProfit", "salepPofitRate"]), e()), i("#grid").clearGridData(!0), i("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: k,
			url: l
		}).trigger("reloadGrid")
	}
	var i = a("jquery"),
		j = parent.SYSTEM,
		k = i.extend({
			beginDate: "",
			endDate: "",
			customerNo: "",
			goodsNo: "",
			storageNo: "",
			profit: 0,
			showSku: 0
		}, Public.urlParam()),
		l = "../report/salesDetail.do?action=inv",
		m = "../report/salesDetail.do?action=invExporter";
	a("print");
	var n = Public.mod_PageConfig.init("salesSummary");
	b(), c(), d();
	var o;
	i(window).on("resize", function() {
		o || (o = setTimeout(function() {
			e(), o = null
		}, 50))
	})
});