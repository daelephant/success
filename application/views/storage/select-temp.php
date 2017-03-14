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

<style>
#matchCon { width: 120px; }
.ui-jqgrid-bdiv .textbox{height:100%;}
</style>
</head>

<body>
<div class="container" style="margin:20px;">
  <div class="mod-search m0 cf">
     <ul class="ul-inline">
       <li>
         <label>模板名称:</label>
         <input type="text" id="matchCon" class="ui-input ui-input-ph">
       </li>
       <li>
         <label>组合件:</label>
         <input type="text" id="group" class="ui-input" />
       </li>
       <li>
         <label>子件包含:</label>
         <input type="text" id="children" class="ui-input" />
       </li>
       <li><a class="ui-btn" id="search">查询</a></li>
     </ul>
  </div>
  <div class="grid-wrap">
    <table id="grid">
    </table>
    <div id="page"></div>
  </div>
</div>
<script src="<?php echo base_url()?>/statics/js/dist/selectTemp.js?ver=20151105"></script>
 
</body>
</html>
 