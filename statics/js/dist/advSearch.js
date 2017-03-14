var queryConditions = {
	matchCon: ""
},
	api = frameElement.api,
	handle, urlParam = Public.urlParam(),
	billRequiredCheck = parent.parent.SYSTEM.billRequiredCheck,
	THISPAGE = {
		init: function() {
			this.initDom(), this.addEvent()
		},
		initDom: function() {
			var a = api.data;
			switch (this.$_matchCon = $("#matchCon"), this.$_beginDate = $("#beginDate").val(a.beginDate), this.$_endDate = $("#endDate").val(a.endDate), this.$_checked = $("#checked"), billRequiredCheck || $("#check").hide(), "请输入单据号或供应商或备注" === a.matchCon || "请输入单据号或客户名或备注" === a.matchCon ? (this.$_matchCon.addClass("ui-input-ph"), this.$_matchCon.placeholder()) : (this.$_matchCon.removeClass("ui-input-ph"), this.$_matchCon.val(a.matchCon)), ("warehouse" === urlParam.diff || "payment" === urlParam.diff) && (this.$_matchCon.addClass("ui-input-ph"), this.$_matchCon.val(a.matchCon), this.$_matchCon.focus(function() {
				"请输入单据号或供应商或备注" == $.trim(this.value) && (this.value = ""), $(this).removeClass("ui-input-ph")
			}).blur(function() {
				var a = $.trim(this.value);
				("" == a || "请输入单据号或供应商或备注" == a) && $(this).addClass("ui-input-ph"), "" == a && $(this).val("请输入单据号或供应商或备注")
			})), this.$_beginDate.datepicker(), this.$_endDate.datepicker(), this.checkedCombo = this.$_checked.combo({
				data: function() {
					return [{
						name: "未审核",
						id: 1
					}, {
						name: "已审核",
						id: 2
					}]
				},
				width: 120,
				height: 300,
				text: "name",
				value: "id",
				defaultSelected: 0,
				cache: !1,
				emptyOptions: !0
			}).getCombo(), urlParam.type) {
			case "purchase":
				"purchaseOrderList" == urlParam.page ? $("ul li:eq(4)").hide() : ($("ul li:eq(3)").hide(), "150502" === a.transType && ($("input[name='hxState']:eq(0)").next().text("未退款"), $("input[name='hxState']:eq(1)").next().text("部分退款"), $("input[name='hxState']:eq(2)").next().text("全部退款")));
				break;
			case "sales":
				"salesOrderList" === urlParam.page ? $("ul li:eq(4)").hide() : ($("ul li:eq(3)").hide(), "150602" === a.transType && ($("input[name='hxState']:eq(0)").next().text("未退款"), $("input[name='hxState']:eq(1)").next().text("部分退款"), $("input[name='hxState']:eq(2)").next().text("全部退款"))), this.salesCombo = Business.salesCombo($("#sales"), {
					defaultSelected: 0,
					extraListHtml: ""
				});
				break;
			case "transfers":
				this.outStorageCombo = $("#storageA").combo({
					data: function() {
						return parent.parent.SYSTEM.storageInfo
					},
					text: "name",
					value: "id",
					width: 112,
					defaultSelected: 0,
					emptyOptions: !0,
					cache: !1
				}).getCombo(), -1 !== a.outLocationId && this.outStorageCombo.selectByValue(a.outLocationId), this.inStorageCombo = $("#storageB").combo({
					data: function() {
						return parent.parent.SYSTEM.storageInfo
					},
					text: "name",
					value: "id",
					width: 112,
					defaultSelected: 0,
					emptyOptions: !0,
					cache: !1
				}).getCombo(), -1 !== a.inLocationId && this.inStorageCombo.selectByValue(a.inLocationId);
				break;
			case "other":
				if (this.storageCombo = $("#storageA").combo({
					data: function() {
						return parent.parent.SYSTEM.storageInfo
					},
					text: "name",
					value: "id",
					width: 112,
					defaultSelected: 0,
					addOptions: {
						text: "(所有)",
						value: -1
					},
					cache: !1
				}).getCombo(), -1 !== a.locationId && this.storageCombo.selectByValue(a.locationId), "outbound" === urlParam.diff) var b = "../scm/invOi.do?action=queryTransType&type=out";
				else var b = "../scm/invOi.do?action=queryTransType&type=in";
				this.transTypeCombo = $("#transType").combo({
					data: b,
					ajaxOptions: {
						formatData: function(a) {
							return a.data.items
						}
					},
					text: "name",
					value: "id",
					width: 112,
					defaultSelected: 0,
					addOptions: {
						text: "(所有)",
						value: -1
					},
					cache: !1
				}).getCombo(), -1 !== a.transTypeId && this.transTypeCombo.selectByValue(a.transTypeId)
			}
		},
		addEvent: function() {},
		handle: function(a) {
			var b = new Array,
				c = new Array;
			switch ($('input[name="hxState"]').each(function() {
				$(this).is(":checked") && b.push($(this).val())
			}), $('input[name="billStatus"]').each(function() {
				$(this).is(":checked") && c.push($(this).val())
			}), this.$_hxState = b.join(","), this.$_billStatus = c.join(","), a = a || {}, a.matchCon = "请输入单据号或客户名或备注" === THISPAGE.$_matchCon.val() ? "" : THISPAGE.$_matchCon.val(), a.beginDate = THISPAGE.$_beginDate.val(), a.endDate = THISPAGE.$_endDate.val(), a.hxState = this.$_hxState ? this.$_hxState : "", a.billStatus = this.$_billStatus ? this.$_billStatus : "", THISPAGE.checkedCombo && (a.checked = THISPAGE.checkedCombo.getValue() ? THISPAGE.checkedCombo.getValue() - 1 : "-1"), urlParam.type) {
			case "sales":
				a.salesId = THISPAGE.salesCombo.getValue();
				break;
			case "purchase":
				a.matchCon = "请输入单据号或供应商或备注" === THISPAGE.$_matchCon.val() ? "" : THISPAGE.$_matchCon.val();
				break;
			case "transfers":
				a.outLocationId = THISPAGE.outStorageCombo.getValue(), a.inLocationId = THISPAGE.inStorageCombo.getValue();
				break;
			case "other":
				"warehouse" === urlParam.diff && (a.matchCon = "请输入单据号或供应商或备注" === THISPAGE.$_matchCon.val() ? "" : THISPAGE.$_matchCon.val()), a.locationId = THISPAGE.storageCombo.getValue(), a.transTypeId = THISPAGE.transTypeCombo.getValue();
				break;
			case "money":
				"payment" === urlParam.diff && (a.matchCon = "请输入单据号或供应商或备注" === THISPAGE.$_matchCon.val() ? "" : THISPAGE.$_matchCon.val())
			}
			return a
		}
	};
THISPAGE.init(), handle = THISPAGE.handle;
 