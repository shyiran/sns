<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo APPS_URL;?>/admin/_static/admin.css" rel="stylesheet" type="text/css">
<script>
/**
 * 全局变量
 */
var SITE_URL  = '<?php echo SITE_URL; ?>';
var THEME_URL = '__THEME__';
var APPNAME   = '<?php echo APP_NAME; ?>';
var UPLOAD_URL ='<?php echo UPLOAD_URL;?>';
var MID		  = '<?php echo $mid; ?>';
var UID		  = '<?php echo $uid; ?>';
var _CP       = '<?php echo C("COOKIE_PREFIX");?>';
// Js语言变量
var LANG = new Array();
</script>
<script type="text/javascript" src="__THEME__/js/jquery.js"></script>
<script type="text/javascript" src="__THEME__/js/core.js"></script>
<script src="__THEME__/js/module.js"></script>
<script src="__THEME__/js/common.js"></script>
<script src="__THEME__/js/module.common.js"></script>
<script src="__THEME__/js/module.weibo.js"></script>
<script type="text/javascript" src="<?php echo APPS_URL;?>/admin/_static/admin.js?t=11"></script>
<script type="text/javascript" src = "__THEME__/js/ui.core.js"></script>
<script type="text/javascript" src = "__THEME__/js/ui.draggable.js"></script>
<?php /* 非admin应用的后台js脚本统一写在  模板风格对应的app目录下的admin.js中*/
if(APP_NAME != 'admin' && file_exists(APP_PUBLIC_PATH.'/admin.js')){ ?>
<script type="text/javascript" src="<?php echo APP_PUBLIC_URL;?>/admin.js"></script>
<?php } ?>
<?php if(!empty($langJsList)) { ?>
<?php if(is_array($langJsList)): ?><?php $i = 0;?><?php $__LIST__ = $langJsList?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><script src="<?php echo ($vo); ?>"></script><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
<?php } ?>
</head>
<body>
<script type="text/javascript" src="__THEME__/js/tsjs.json2select.js"></script>
<div class="so_main"><div class="tit_tab">
<ul>
	<li><a href="<?php echo U('square/Admin/index');?>"<?php if((ACTION_NAME)  ==  "index"): ?>class="on"<?php endif; ?> >全局设置</a></li>
</ul>
</div>
<div class="form2">
<form method="post" action="<?php echo U('square/Admin/index');?>">
<dl class="lineD">
	<dt>频道分享列表：</dt>
	<dd><label><input name="channel" type="radio"
		value="1"<?php if(($setting['channel'])  ==  "1"): ?>checked<?php endif; ?>>开启</label>
	<label><input name="channel" type="radio" value="0"<?php if(($setting['channel'])  ==  "0"): ?>checked<?php endif; ?>>关闭</label></dd>
</dl>
<dl class="lineD">
	<dt>频道分享ID：支持8个</dt>
	<dd><input type="text" class="s-txt" style="width:350px;" name="channelid" value="<?php echo ($setting['channelid']); ?>"></dd
</dl>

<dl class="lineD">
	<dt>微吧列表：</dt>
	<dd><label><input name="weiba" type="radio"
		value="1"<?php if(($setting['weiba'])  ==  "1"): ?>checked<?php endif; ?>>是</label>
	<label><input name="weiba" type="radio" value="0"<?php if(($setting['weiba'])  ==  "0"): ?>checked<?php endif; ?>>否</label></dd>
</dl>

<dl class="lineD">
	<dt>微吧ID：支持6个</dt>
	<dd><input type="text" class="s-txt"  name="weibaid" style="width:350px;"
		value="<?php echo ($setting['weibaid']); ?>"></dd>
</dl>


<dl class="lineD">
	<dt>找人模块：</dt>
	<dd><label><input name="relateduser" type="radio"
		value="1"<?php if(($setting['relateduser'])  ==  "1"): ?>checked<?php endif; ?>>是</label>
	<label><input name="relateduser" type="radio" value="0"<?php if(($setting['relateduser'])  ==  "0"): ?>checked<?php endif; ?>>否</label></dd>
</dl>

<dl class="lineD">
	<dt>推荐认证用户ID:仅支持一个</dt>
	<dd><input type="text" class="s-txt" name="user_recommend_uid" style="width:350px;"
		value="<?php echo ($setting['user_recommend_uid']); ?>"></dd>
</dl>

<div class="page_btm"><input type="hidden" name="editSubmit"
	value="1"> <input type="submit" class="btn_b" value="确定" /></div>
</form>
</div>
</div>
<script>
	function doSwitch(i, name){
		if (i == 0) {
			$("."+name).css('display','none');
		} else if (i == 1) {
			$("."+name).css('display','block');
		}
	}
	doSwitch(<?php echo (intval($setting['uploadFile'])); ?>, 'uploadFile');
</script>
</body>
</html>