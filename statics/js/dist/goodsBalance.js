define(["jquery", "print"], function(a, b, c) {
	function d() {
		Business.getSearchList(), Business.filterGoods(), Business.filterStorage(), Business.moreFilterEvent(), j("#date,#storage,#goodsfilter,#goods,#chk-stock,#chk-wrap").show(), j("#storage").insertBefore(j("#goods")), j("#date label").text("库存日期"), j("#filter-fromDate").addClass("dn").attr("readonly", !0).attr("disabled", !0), j("#date .todate").hide(), j("#filter-toDate").removeClass("ui-datepicker-input").addClass("ui-datepickerto-input"), j("#chk-wrap .chk:eq(0)").hide(), j("#btn-print").hide(), j("#conditions-trigger").trigger("click"), j("#filter-fromDate").attr("disabled", "disabled"), j("#filter-toDate").datepicker();
		var a = Public.urlParam();
		chkboxes = j("#chk-wrap").cssCheckbox(), chkstock = j("#chk-stock").cssCheckbox(), k.enableAssistingProp || j("#chk-wrap").hide(), a = {
			beginDate: l.startDate || a.beginDate,
			endDate: a.endDate,
			goods: a.goods || "",
			goodsNo: a.goodsNo || "",
			storage: a.storage || "",
			storageNo: a.storageNo || ""
		}, "1" === a.showSku && j('#chk-wrap input[name="showSku"]').attr("checked", !0), j("#filter-fromDate").val(a.beginDate || ""), j("#filter-toDate").val(a.endDate || ""), n = Public.categoryTree(j("#filterCat"), {
			width: 200
		})
	}
	function e() {
		j("#filter-submit").click(function(a) {
			a.preventDefault();
			var b = j("#filter-fromDate").val(),
				c = j("#filter-toDate").val();
			if (b && c && new Date(b).getTime() > new Date(c).getTime()) return parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			}), !1;
			m = {
				beginDate: b,
				endDate: c,
				goods: j("#filter-goods input").data("ids") || "",
				goodsNo: j("#filter-goods input").val() || "",
				storage: j("#filter-storage input").data("ids") || "",
				storageNo: j("#filter-storage input").val() || "",
				catId: n.getValue(),
				catName: n.getText(),
				showZero: chkstock.chkVal().length > 0 ? "1" : "0"
			}, chkVals = chkboxes.chkVal();
			for (var d = 0, e = chkVals.length; e > d; d++) m[chkVals[d]] = 1;
			var g = j.dialog.tips("正在查询，请稍候...", 1e3, "loading.gif", !0);
			Public.ajaxPost("../report/invBalance.do?action=detail", m, function(a) {
				200 === a.status ? (j(".no-query").remove(), j(".ui-print").show(), f(a.data), g.close(), j(".grid-subtitle").text(m.beginDate + "至" + c)) : (g.close(), parent.Public.tips({
					type: 1,
					content: msg
				}))
			})
		}), m.search && j("#filter-submit").trigger("click"), j("#btn-print").click(function(a) {
			a.preventDefault(), Business.verifyRight("InvBalanceReport_PRINT") && window.print()
		}), j("#btn-export").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("InvBalanceReport_EXPORT")) {
				var b = {};
				for (var c in m) m[c] && (b[c] = m[c]);
				Business.getFile("../report/invBalance.do?action=exporter", b)
			}
		}), j("#config").show().click(function(a) {
			p.config()
		})
	}
	function f(a) {
		j("#grid").jqGrid("GridUnload");
		for (var b = "auto", c = [{
			name: "invNo",
			label: "商品编号",
			width: 80
		}, {
			name: "invName",
			label: "商品名称",
			width: 200,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "spec",
			label: "规格型号",
			width: 60,
			align: "center"
		}, {
			name: "unit",
			label: "单位",
			width: 40,
			align: "center"
		}], d = a.colIndex, e = a.colNames, f = a.stoNames, g = [], k = "", l = 0, m = 4, n = d.length; n > m; m++) {
			var q = null;
			q = {
				name: d[m],
				label: e[m],
				width: 80,
				align: "right"
			}, c.push(q), d[m].split("_")[1] === k ? (g.pop(), g.push({
				startColumnName: d[m - 1],
				numberOfColumns: 2,
				titleText: f[l - 1]
			})) : (g.push({
				startColumnName: d[m],
				numberOfColumns: 1,
				titleText: f[l]
			}), l++), k = d[m].split("_")[1]
		}
		p.gridReg("grid", c), c = p.conf.grids.grid.colModel, j("#grid").jqGrid({
			ajaxGridOptions: {
				complete: function(a, b) {}
			},
			data: a.rows,
			datatype: "local",
			autowidth: !0,
			height: b,
			gridview: !0,
			colModel: c,
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
			userData: a.userdata,
			userDataOnFooter: !0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				userdata: "data.userdata",
				repeatitems: !1,
				id: "0"
			},
			ondblClickRow: function(a) {},
			loadComplete: function(a) {
				var b = o = a.records,
					c = i();
				b > Math.floor(c / 31).toFixed(0) && (j("#grid").jqGrid("setGridHeight", c), j("#grid").jqGrid("setGridWidth", h(), !1))
			},
			gridComplete: function() {},
			resizeStop: function(a, b) {
				p.setGridWidthByIndex(a, b + 1, "grid")
			}
		}).jqGrid("setGroupHeaders", {
			useColSpanStyle: !0,
			groupHeaders: g
		}).jqGrid("setFrozenColumns")
	}
	function g() {
		var a = h(),
			b = i(),
			c = j("#grid");
		o > Math.floor(b / 31).toFixed(0) ? c.jqGrid("setGridHeight", b) : c.jqGrid("setGridHeight", "auto"), c.jqGrid("setGridWidth", a, !1)
	}
	function h() {
		return j(window).width() - (h.offsetLeft || (h.offsetLeft = j("#grid-wrap").offset().left)) - 36 - 22
	}
	function i() {
		return j(window).height() - (i.offsetTop = j("#grid").offset().top) - 36 - 16
	}
	var j = a("jquery"),
		k = parent.SYSTEM,
		l = k,
		m = j.extend({
			beginDate: "",
			endDate: "",
			goodsNo: "",
			storageNo: "",
			showSku: "0"
		}, Public.urlParam()),
		n = null,
		o = 0;
	a("print");
	var p = Public.mod_PageConfig.init("goodsBalance");
	d(), e();
	var q;
	j(window).on("resize", function(a) {
		q || (q = setTimeout(function() {
			g(), q = null
		}, 50))
	})
});