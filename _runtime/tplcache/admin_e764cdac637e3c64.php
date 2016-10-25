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
<!-- START TAB框 -->
  
<div class="tit_tab">
  <ul>
  <!-- <li><a href="<?php echo U('admin/Home/visitorCount',array('type'=>'today'));?>" <?php if(($type)  ==  "today"): ?>class = "on"<?php endif; ?>><?php echo L('PUBLIC_TODAY');?></a></li>
  <li><a href="<?php echo U('admin/Home/visitorCount',array('type'=>'yesterday'));?>" <?php if(($type)  ==  "yesterday"): ?>class = "on"<?php endif; ?>><?php echo L('PUBLIC_YESTERDAY');?></a></li> -->
  <li><a href="<?php echo U('admin/Home/visitorCount',array('type'=>'week'));?>" <?php if(($type)  ==  "week"): ?>class = "on"<?php endif; ?>><?php echo L('PUBLIC_LAST_SEVEN_DAYS');?></a></li>
  <li><a href="<?php echo U('admin/Home/visitorCount',array('type'=>'30d'));?>" <?php if(($type)  ==  "30d"): ?>class = "on"<?php endif; ?>><?php echo L('PUBLIC_LAST_THIRTY_DAYS');?></a></li>
  <li><a href="<?php echo U('admin/Home/visitorCount',array('type'=>'month'));?>" <?php if(($type)  ==  "month"): ?>class = "on"<?php endif; ?>><?php echo L('PUBLIC_THIS_MONTH');?></a></li>
  <li>
  <form method ='GET' action="<?php echo U('admin/Home/visitorCount');?>">
  <input type="hidden" name='app' value='admin'>
  <input type="hidden" name='mod' value='Home'>
  <input type="hidden" name='act' value='visitorCount'>
  <input style="margin-top:4px" type="text" readonly="readonly" onfocus="core.rcalendar(this,'Y-m-d');" value="<?php echo ($_GET["start_day"]); ?>" id="start_day" class="s-txt" name="start_day">
  -
   <input style="margin-top:4px" type="text" readonly="readonly" onfocus="core.rcalendar(this,'Y-m-d');" value="<?php echo ($_GET["end_day"]); ?>" id="end_day" class="s-txt" name="end_day">
   <input style="margin-top:4px" type="submit" value="<?php echo L('PUBLIC_SYSTEM_FIND');?>" class="btn_b">
  </form>
  </li>
  </ul>
</div>

<!-- END TAB框 -->

  
  <!-- START LIST -->
  <div class="list" id='list' style="clear:both;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th><?php echo L('PUBLIC_STATISTICAL_TIME');?></th>
    <th class="line_l"><?php echo L('PUBLIC_VIEWS_PV');?></th>
    <th class="line_l"><?php echo L('PUBLIC_INDEPENDENT_VISITORS');?></th>
    <th class="line_l"><?php echo L('PUBLIC_PER_CAPITA_VIEWS');?></th>
    <th class="line_l"><?php echo L('PUBLIC_MAXIMUM_ONLINE_SAME_TIME');?></th>
    <th class="line_l"><?php echo L('PUBLIC_MAXIMUM_ONLINE_TIME');?></th>
  </tr>
  <?php if(empty($data)){ ?>
  <tr><td colspan='100' align="center"><?php echo L('SSC_NO_RELATE_DATA');?></td></tr>
  <?php }else{ ?>
 
  <?php $value[0][] = $value[1][] = $value[2][] = 0; ?> 
  <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php $user = $vo['total_users']+$vo['total_guests'];
    $value[0][] = $vo['total_pageviews'];
    $value[1][] = $user;
    $value[2][] = round($vo['total_pageviews']/$user,2);
    $ticks[] = $vo['day']; ?>
  <tr overstyle="on" >
  <td><?php echo ($vo["day"]); ?></td>
  <td><?php echo ($vo["total_pageviews"]); ?></td>
  <td><?php echo ($user); ?></td>
  <td><?php echo round($vo['total_pageviews']/$user,2); ?></td>
  <td><?php echo ($vo["most_online_users"]); ?></td>
  <td> <?php echo date('H:i',$vo['most_online_time']-1800);?> - <?php echo date('H:i',$vo['most_online_time']);?></td>
  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  <?php } ?>

  </table>
  </div>
  <!-- END LIST -->
  <?php if($type !='today' && $type !='yesterday'){ ?>
  <div >
    <?php echo W('Plot',array('tpl'=>'zx','type'=>'zx','value'=>$value,'title'=>$type,'ticks'=>$ticks));?>
  </div> 
  <?php } ?>
  
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