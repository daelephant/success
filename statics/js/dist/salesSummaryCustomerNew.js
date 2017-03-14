define(["jquery", "print"], function(a) {
	function b() {
		Business.getSearchList(), Business.filterCustomer(), Business.filterGoods(), Business.filterStorage(), Business.moreFilterEvent(), i("#date,#customer,#goods,#storage,#filter,#chk-wrap").show(), i("#filter label").text("接收类别"), i("#chk-wrap .chk:eq(1)").hide(), i("#conditions-trigger").trigger("click"), i("#filter-fromDate").val(k.beginDate || ""), i("#filter-toDate").val(k.endDate || ""), i("#filter-customer input").val(k.customerNo || ""), i("#filter-goods input").val(k.goodsNo || ""), i("#filter-storage input").val(k.storageNo || ""), k.beginDate && k.endDate && (i("#selected-period").text(k.beginDate + "至" + k.endDate), i("div.grid-subtitle").text("日期: " + k.beginDate + " 至 " + k.endDate)), i("#filter-fromDate, #filter-toDate").datepicker(), Public.dateCheck(), j.rights.SAREPORTBU_COST || j.isAdmin ? (i("#chk-wrap").show(), "1" === k.profit && i("#chk-wrap input").attr("checked", !0)) : i("#chk-wrap").hide(), chkboxes = i("#chk-wrap").cssCheckbox();
		var a = Business.categoryCombo(i("#catorage"), {
			editable: !1,
			extraListHtml: "",
			addOptions: {
				value: -1,
				text: "选择接收单位类别"
			},
			defaultSelected: 0,
			trigger: !0,
			width: 112
		}, "customertype");
		i("#filter-submit").on("click", function(b) {
			b.preventDefault();
			var c = i("#filter-fromDate").val(),
				d = i("#filter-toDate").val(),
				e = a ? a.getValue() : -1;
			if (c && d && new Date(c).getTime() > new Date(d).getTime()) return void parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			});
			k = {
				beginDate: c,
				endDate: d,
				customerNo: i("#filter-customer input").val() || "",
				goodsNo: i("#filter-goods input").val() || "",
				storageNo: i("#filter-storage input").val() || "",
				profit: "",
				categoryId: e
			}, i("#selected-period").text(c + "至" + d), i("div.grid-subtitle").text("日期: " + c + " 至 " + d), chkVals = chkboxes.chkVal();
			for (var f = 0, g = chkVals.length; g > f; f++) k[chkVals[f]] = 1;
			var j = k.profit;
			h(j), i("#filter-menu").removeClass("ui-btn-menu-cur")
		}), i("#filter-reset").on("click", function(a) {
			a.preventDefault(), i("#filter-fromDate").val(k.beginDate), i("#filter-toDate").val(k.endDate), i("#filter-customer input").val(""), i("#filter-goods input").val(""), i("#filter-storage input").val(""), k.customerNo = "", k.goodsNo = "", k.storageNo = ""
		})
	}
	function c() {
		i("#refresh").on("click", function(a) {
			a.preventDefault(), i("#filter-submit").click()
		}), i("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("SAREPORTBU_PRINT") && i("div.ui-print").printTable()
		}), i("#btn-export").click(function(a) {
			a.preventDefault(), Business.verifyRight("SAREPORTBU_EXPORT") && Business.getFile(l, k)
		}), i("#config").show().click(function() {
			n.config()
		})
	}
	function d() {
		var a = !1,
			b = !1,
			c = !1,
			d = !1;
		j.isAdmin !== !1 || j.rights.AMOUNT_COSTAMOUNT || (a = !0), j.isAdmin !== !1 || j.rights.AMOUNT_OUTAMOUNT || (b = !0, d = !0), j.isAdmin !== !1 || j.rights.AMOUNT_INAMOUNT || (c = !0), 0 === j.taxRequiredCheck && (d = !0);
		var f = !0;
		1 == k.profit && (f = !1);
		var g = [{
			name: "assistName",
			label: "接收类别",
			width: 80,
			align: "center"
		}, {
			name: "buName",
			label: "接收单位",
			width: 80,
			align: "center"
		}, {
			name: "invNo",
			label: "商品编号",
			width: 80,
			align: "center"
		}, {
			name: "invName",
			label: "商品名称",
			width: 200,
			align: "center"
		}, {
			name: "spec",
			label: "规格型号",
			width: 60,
			align: "center"
		}, {
			name: "unit",
			label: "单位",
			width: 100,
			align: "center"
		}, {
			name: "location",
			label: "仓库",
			width: 80,
			align: "center"
		}, {
			name: "qty",
			label: "数量",
			width: 60,
			align: "right"
		}, {
			name: "unitPrice",
			label: "单价",
			width: 60,
			align: "right",
			hidden: b
		}, {
			name: "amount",
			label: "出库收入",
			width: 60,
			align: "right",
			hidden: b
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
			name: "unitCost",
			label: "单位成本",
			width: 60,
			align: "right",
			hidden: f
		}, {
			name: "cost",
			label: "出库成本",
			width: 60,
			align: "right",
			hidden: f
		}, {
			name: "saleProfit",
			label: "出库毛利",
			width: 60,
			align: "right",
			hidden: f
		}, {
			name: "salepPofitRate",
			label: "毛利率",
			width: 60,
			align: "right",
			hidden: f
		}, {
			name: "buNo",
			label: "",
			width: 0,
			hidden: !0
		}, {
			name: "locationNo",
			label: "",
			width: 0,
			hidden: !0
		}],
			h = "local",
			l = "#";
		k.autoSearch && (h = "json", l = m), n.gridReg("grid", g), g = n.conf.grids.grid.colModel, i("#grid").jqGrid({
			url: l,
			postData: k,
			datatype: h,
			autowidth: !0,
			gridview: !0,
			colModel: g,
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
				if (Business.verifyRight("SAREPORTDETAIL_QUERY")) {
					var b = i("#grid").getRowData(a),
						c = b.buNo,
						d = b.invNo,
						e = b.locationNo;
					c || "小计" !== b.location || (c = i("#grid").getRowData(a - 1).buNo), parent.tab.addTabItem({
						tabid: "report-salesDetail",
						text: "出库明细表",
						url: "../report/sales_detail?autoSearch=true&beginDate=" + k.beginDate + "&endDate=" + k.endDate + "&customerNo=" + c + "&goodsNo=" + d + "&storageNo=" + e + "&profit=" + k.profit
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
		i(".no-query").remove(), i(".ui-print").show(), "undefined" != typeof a && (i("#grid").jqGrid(a ? "showCol" : "hideCol", ["unitCost", "cost", "saleProfit", "salepPofitRate"]), e()), i("#grid").clearGridData(!0), i("#grid").jqGrid("setGridParam", {
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
			storageNo: "",
			profit: ""
		}, Public.urlParam()),
		l = "../report/salesDetail.do?action=customerExporter",
		m = "../report/salesDetail.do?action=customer";
	a("print");
	var n = Public.mod_PageConfig.init("salesSummaryCustomerNew");
	b(), c(), d();
	var o;
	i(window).on("resize", function() {
		o || (o = setTimeout(function() {
			e(), o = null
		}, 50))
	})
});