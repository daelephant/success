<?php $this->load->view('header');?>
 
<script type="text/javascript">
var DOMAIN = document.domain;
var WDURL = "";
var SCHEME= "<?php echo sys_skin()?>";
try{
	document.domain = '<?php echo base_url()?>';
}catch(e){
}
//ctrl+F5 增加版本号来清空iframe的缓存的
$(document).keydown(function(event) {
	/* Act on the event */
	if(event.keyCode === 116 && event.ctrlKey){
		var defaultPage = Public.getDefaultPage();
		var href = defaultPage.location.href.split('?')[0] + '?';
		var params = Public.urlParam();
		params['version'] = Date.parse((new Date()));
		for(i in params){
			if(i && typeof i != 'function'){
				href += i + '=' + params[i] + '&';
			}
		}
		defaultPage.location.href = href;
		event.preventDefault();
	}
});
</script>

</head>

<body style="background:#FFF; ">
<div class="wrapper">
  <div class="mod-search-adv">
    <ul>
      <li>
        <label>搜索条件:</label>
        <input type="text" id="matchCon" class="ui-input ui-input-ph con" value="请输入单据号或供应商或备注">
      </li>
      <li>
        <label>日期:</label>
        <input type="text" id="beginDate" class="ui-input ui-datepicker-input">
        <i>至</i>
        <input type="text" id="endDate" class="ui-input ui-datepicker-input">
      </li>
      <li class="dn">
        <label>供应商:</label>
        <span id="purchase"></span>
        </li>
      <li>
        <label>订单状态:</label>
        <input type="checkbox" name="billStatus" value="0" class="vm">未入库
        <input type="checkbox" name="billStatus" value="1" class="vm">部分入库
        <input type="checkbox" name="billStatus" value="2" class="vm">全部入库
        <input type="checkbox" name="billStatus" value="3" class="vm">已关闭
        <!--<input type="checkbox" name="billStatus" value="0" class="vm">未关闭-->
      </li>
      <li>
        <label>付款状态:</label>
        <input type="checkbox" name="hxState" value="0" class="vm"><span>未付款</span>
        <input type="checkbox" name="hxState" value="1" class="vm"><span>部分付款</span>
        <input type="checkbox" name="hxState" value="2" class="vm"><span>全部付款</span>
      </li>
      <li id="check">
        <label>审核状态:</label>
        <span id="checked"></span>
      </li>
    </ul>
  </div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/advSearch.js?ver=20151110"></script>
</body>
</html>

 