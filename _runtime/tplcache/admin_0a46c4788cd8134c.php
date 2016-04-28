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
<div id="container" class="so_main">
  <div class="page_tit"><?php echo L('PUBLIC_MAILTITLE_ADMIN');?> - <?php echo ($nodeInfo["nodeinfo"]); ?></div>
  <!-- START TAB框 -->
  <?php if(!empty($pageTab)): ?>
  <div class="tit_tab">
    <ul>
      <?php !$_REQUEST['tabHash'] && $_REQUEST['tabHash'] =  $pageTab[0]['tabHash']; ?>
      <?php if(is_array($pageTab)): ?><?php $i = 0;?><?php $__LIST__ = $pageTab?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$t): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li><a href="<?php echo ($t["url"]); ?>&tabHash=<?php echo ($t["tabHash"]); ?>" 
          <?php if($t['tabHash'] == $_REQUEST['tabHash']){ echo 'class="on"';} ?>
          ><?php echo ($t["title"]); ?></a></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </ul>
  </div>
  <?php endif; ?>
  <!-- END TAB框 -->
  <div class="list">
    <form action="<?php echo U('admin/Config/doAddNotifyTpl');?>" method='POST'>
      <input type="hidden" name="tabhash" value="<?php echo ($_REQUEST['redirectTabHash']); ?>"/>
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td width="100px">节点名称：</td>
          <td><input name='node' /></td>
        </tr>
        <tr>
          <td width="100px">节点描述：</td>
          <td><input name='nodeinfo' size="30" /></td>
        </tr>
        <tr>
          <td width="100px">应用名：</td>
          <td><input name='appname' /></td>
        </tr>
        <tr>
          <td width="100px">标题语言包KEY：</td>
          <td><input name='title_key' size="50" />
          <br/>增加节点后程序会自动把改KEY增加到语言包里，切记在语言配置里找到该KEY并为其增加中文、英文、繁体的内容</td>
        </tr>
        <tr>
          <td width="100px">内容语言包KEY：</td>
          <td><input name='content_key' size="50" />
          <br/>增加节点后程序会自动把改KEY增加到语言包里，切记在语言配置里找到该KEY并为其增加中文、英文、繁体的内容</td>
        </tr>
        <tr>
          <td width="100px">发送类型：</td>
          <td><label>
            <input type="checkbox" name="send_email" value="1" />
            发送邮件
            <input type="checkbox" name="send_message" value="1" />
            发送系统消息</label></td>
        </tr>
        <tr>
          <td width="100px">节点类型：</td>
          <td><label>
            <input type="radio" name="type" value="1" />
            用户节点</label>
            <label>
            <input type="radio" name="type" value="2" />
            管理员节点</label></td>
        </tr>
      </table>
      <div class="page_btm">
        <input class="btn_b" value="<?php echo L('PUBLIC_SAVE');?>" name='sub' type="submit">
      </div>
    </form>
  </div>
</div>
<?php if(!empty($onload)){ ?>
<script type="text/javascript">
/**
 * 初始化对象
 */
//表格样式
$(document).ready(function(){
    <?php foreach($onload as $v){ echo $v,';';} ?>
});
</script>
<?php } ?>

<?php if(ACTION_NAME == 'feed'): ?>
<script type="text/javascript">
core.loadFile(THEME_URL+'/js/plugins/core.weibo.js', function () {
	setTimeout(function () {
        // 重写方法
        core.weibo.showBigImage = function (a, b) {
            var $parent = $('#tr' + a).find('div[model-node="feed_list"]');
            $parent.find('div').each(function () {
                var relVal = $(this).attr('rel');
                if (relVal == 'small') {
                    $(this).hide();
                } else if (relVal == 'big') {
                    $(this).show();
                }
            });
        };
	}, 1000);
});
</script>
<?php endif; ?>

</body>
</html>