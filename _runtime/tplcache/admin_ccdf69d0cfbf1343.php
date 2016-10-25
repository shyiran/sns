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
  <div class="page_tit"><span onclick="admin.fold('page_config')"><?php echo L('PUBLIC_PAGE_CONFIGURATION');?></span><?php echo ($pageTitle); ?> </div>
  	<!-- START PAGE_CONFIG -->
  	<div id='page_config' class = "form2 list" >
  		<div class="hd"><?php echo L('PUBLIC_CHECH_IS');?></div>
  		<form action="<?php echo U('admin/Index/savePageConfig');?>" method="POST">
  		<input type="hidden" name='pageKey' value="<?php echo ($pageKey); ?>" />
  		<input type="hidden" name='pageTitle' value="<?php echo ($pageTitle); ?>" />
  		<table width="100%" cellpadding="0" cellspacing="0" border="0">
  			<tr>
  				<th><?php echo L('PUBLIC_SYSTEM_FIELD');?></th>
  				<th class="line_l"><?php echo L('PUBLIC_ADMIN_TITLE');?></th>
  				<th class="line_l"><?php echo L('PUBLIC_ADMIN_TYPE');?></th>
  				<th class="line_l"><?php echo L('PUBLIC_ADMIN_MODIFY');?></th>
  				<th class="line_l"><?php echo L('PUBLIC_ADMIN_INFO');?></th>
  				<th class="line_l"><?php echo L('PUBLIC_ADMIN_NOTHING');?></th>
  			</tr>
  			<?php if(is_array($pageKeyList)): ?><?php $i = 0;?><?php $__LIST__ = $pageKeyList?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$pk): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php $key =$pk; $keyType = $pageKeyData['key_type'][$key]; ?>
  			<tr overstyle="on">
  				<td> <input type="hidden" name='key[]' value='<?php echo ($pk); ?>'> <?php echo ($pk); ?></td>
  				<td><input type="text" name='key_name[]' value='<?php echo ($pageKeyData['key_name'][$key]); ?>' /></td>
  				<td><select name='key_type[]'>
  					<option value='text' <?php if(($keyType)  ==  "text"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_INPUT_TEXT');?></option>
  					<option value='password' <?php if(($keyType)  ==  "password"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_INPUT_PASSWORD');?></option>
  					<option value='select' <?php if(($keyType)  ==  "select"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_SELECT');?></option>
  					<option value='radio' <?php if(($keyType)  ==  "radio"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_RADIO');?></option>
  					<option value='checkbox' <?php if(($keyType)  ==  "checkbox"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_CHAECKBOX');?></option>
  					<option value='date' <?php if(($keyType)  ==  "date"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_DATE');?></option>
  					<option value='textarea' <?php if(($keyType)  ==  "textarea"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_TEXTAREA');?></option>
  					<option value='word' <?php if(($keyType)  ==  "word"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_WORD');?></option>
  					<option value='hidden' <?php if(($keyType)  ==  "hidden"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_INPUT_HIDDEN');?></option>
  					<option value='file' <?php if(($keyType)  ==  "file"): ?>selected="selected"<?php endif; ?>><?php echo L('PUBLIC_INPUT_FILE');?></option>
  				</select></td>
  				<td><input type='text' name='key_default[]' value='<?php echo ($pageKeyData['key_default'][$key]); ?>'></td>
  				<td><input type='text' name='key_tishi[]' value='<?php echo ($pageKeyData['key_tishi'][$key]); ?>'></td>
  				<td><input type='text' name='key_javascript[]' value='<?php echo ($pageKeyData['key_javascript'][$key]); ?>'></td>
  			
  			</tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  		</table>
  		<div class="page_btm">
	      <input type="submit" class="btn_b" value="<?php echo L('PUBLIC_CONFIRM');?>" />
	    </div>
	    </form>
  	</div>
  	<!-- END PAGE_CONFIG -->
  
   <!-- START TAB框 -->
  <?php if(!empty($pageTab)): ?>
  <div class="tit_tab">
    <ul>
    <?php !$_REQUEST['tabHash'] && $_REQUEST['tabHash'] =  $pageTab[0]['tabHash']; ?>
    <?php if(is_array($pageTab)): ?><?php $i = 0;?><?php $__LIST__ = $pageTab?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$t): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li><a href="<?php echo ($t["url"]); ?>&tabHash=<?php echo ($t["tabHash"]); ?>" <?php if($t['tabHash'] == $_REQUEST['tabHash']){ echo 'class="on"';} ?>><?php echo ($t["title"]); ?></a></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </ul>
  </div>
  <?php endif; ?>
  <!-- END TAB框 -->
  
  <!-- START ADD_FORM -->
  <?php if($pageKeyData=='null'){ ?>
  		<?php echo L('PUBLIC_PLEASE');?><span onclick="admin.fold('page_config')"><?php echo L('PUBLIC_PAGE_CONFIGURATION');?></span>
  <?php }else{ ?>
  
   <form method="POST" action="<?php echo ($savePostUrl); ?>" <?php if(($onsubmit)  !=  ""): ?>onsubmit = "return <?php echo ($onsubmit); ?>;"<?php endif; ?>>
  	<input type="hidden" name='systemdata_list' value="<?php echo ($systemdata_list); ?>" class="s-txt" />
  	<input type="hidden" name='systemdata_key' value="<?php echo ($systemdata_key); ?>"  class="s-txt"/>  
  	<input type="hidden" name='pageTitle' value="<?php echo ($pageTitle); ?>"  class="s-txt"/>
  	<div class="form2">
  	<?php if(is_array($pageKeyList)): ?><?php $i = 0;?><?php $__LIST__ = $pageKeyList?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$pk): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php $key = $pk;
  	$defaultS = isset($detailData[$pk]) ? $detailData[$pk] : $pageKeyData['key_default'][$key]; 
  	$event = $pageKeyData['key_javascript'][$key];
  	$keyName = $pageKeyData['key_name'][$key] ? $pageKeyData['key_name'][$key] :$pk; 
  	$keyType = $pageKeyData['key_type'][$key] ? $pageKeyData['key_type'][$key] :'text';
  	
  	if($keyType != 'hidden'):/*非隐藏域*/ ?>
    <dl class="lineD">
      <dt> <?php if(in_array($pk,$notEmpty)){ ?><font color="red"> * </font><?php } ?><?php echo ($keyName); ?>：</dt>
      <dd>
    <?php endif; /*非隐藏域*/ ?>  
      	<?php if($keyType == 'text'): ?>
      	<input name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" type="text" value="<?php echo ($defaultS); ?>" <?php if(($event)  !=  ""): ?>onfocus = "<?php echo ($event); ?>"<?php endif; ?> class="s-txt" style='width:200px'>
      	<?php endif; ?>
        
        <?php if($keyType == 'password'): ?>
      	<input name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" type="password" value="<?php echo ($defaultS); ?>" <?php if(($event)  !=  ""): ?>onfocus = "<?php echo ($event); ?>"<?php endif; ?> class="s-txt" style='width:200px'>
      	<?php endif; ?>
      	
      	<?php if($keyType == 'select'): ?>
      	<select name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" <?php if(($event)  !=  ""): ?>onchange = "<?php echo ($event); ?>"<?php endif; ?>>
      		<?php foreach($opt[$pk] as $sk=>$sv): ?>
      			<option value="<?php echo ($sk); ?>" <?php if($sk == $defaultS): ?> selected="selected" <?php endif; ?>><?php echo ($sv); ?></option>
      		<?php endforeach; ?>
      	</select>
      	<?php endif; ?>
      	
      	<?php if($keyType == 'radio'):
      		$nums = count($opt[$pk]);
      		$tempi = 1;
      		foreach($opt[$pk] as $sk=>$sv):
      		  $br = $nums >=6  && $tempi%3==0 ? '<br/>':'';
      		  $tempi++; ?>
        <label><input type="radio" name="<?php echo ($pk); ?>" value='<?php echo ($sk); ?>' <?php if($sk == $defaultS): ?> checked="checked"<?php endif; ?> <?php if(($event)  !=  ""): ?>onfocus = "<?php echo ($event); ?>"<?php endif; ?> ><?php echo ($sv); ?> </label> <?php echo ($br); ?>           		
      	<?php endforeach; endif; ?>
      	
      	
      	<?php /* checkBox 默认值是以","隔开的字符串,表示可以选多个  */
      	if($keyType == 'checkbox'):
      		$defaultS = !is_array($defaultS) ? explode(',',trim($defaultS,',')) : $defaultS;
      		foreach($opt[$pk] as $sk=>$sv): ?>	
      	<label><input type="checkbox" name="<?php echo ($pk); ?>[]" value='<?php echo ($sk); ?>' <?php if(in_array($sk,$defaultS)): ?> checked="checked"<?php endif; ?> <?php if(($event)  !=  ""): ?>onfocus = "<?php echo ($event); ?>"<?php endif; ?> ><?php echo ($sv); ?> </label>
      	<?php endforeach; endif; ?>
      	
      	
      	<?php /* 日期插件 */
      	if($keyType == 'date'): ?>
      	<input name="<?php echo ($pk); ?>" type="text" class="text" id="form_<?php echo ($pk); ?>" value='<?php echo ($defaultS); ?>' onfocus="core.rcalendar(this,'full');" readonly="readonly" style="width:200px;" class="s-txt"/>
      	<?php endif; ?>
      	
      	<?php if($keyType == 'textarea'): ?>
      	<textarea  name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" rows="6" cols="60" <?php if(($event)  !=  ""): ?>onfocus = "<?php echo ($event); ?>"<?php endif; ?>><?php echo ($defaultS); ?></textarea>
      	<?php endif; ?>
      	
      	<?php if($keyType == 'word'): ?>
      	<input name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" type="hidden" value="<?php echo ($defaultS); ?>" class="s-txt"> <?php echo ($defaultS); ?>
      	<?php endif; ?>
      	
      	<?php if($keyType == 'hidden'): ?>
      	<input name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" type="hidden" value="<?php echo ($defaultS); ?>" class="s-txt">
      	<?php endif; ?>
      	
      	<?php if($keyType == 'file'): ?>
      	<input name="<?php echo ($pk); ?>" id="form_<?php echo ($pk); ?>" type="text" value="<?php echo ($defaultS); ?>" <?php if(($event)  !=  ""): ?>onchange = "<?php echo ($event); ?>"<?php endif; ?> class="s-txt" style='width:200px'>
      	<?php endif; ?>
      	
        <?php if(!empty($pageKeyData['key_tishi'][$key])){ ?>
        <p><?php echo ($pageKeyData['key_tishi'][$key]); ?></p>
        <?php } ?>
       
    <?php if($keyType != 'hidden'): /*非隐藏域*/ ?> 
    </dl>
    <?php endif; /*隐藏域*/ ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    
    
    <div class="page_btm">
      <input type="submit" class="btn_b" value="<?php echo L('PUBLIC_ADD');?>" />
    </div>
  	</div>
  	</form>
  	
   <?php } ?>
   <!-- END ADD_FORM -->

   <!-- START ListTree -->
     <div class="list" id='list'>
        <table width='100%' cellpadding='0' cellspacing='0' border='0'>
          <tr>
          	<th><?php echo ($pageKeyData['key_name'][$field['id']]); ?></th>
          	<th width="70%" class="line_l"><?php echo ($pageKeyData['key_name'][$field['name']]); ?></th>
          	<!--<th class="line_l"><?php echo ($pageKeyData['key_name'][$field['sort']]); ?></th>-->
          	<th><?php echo ($pageKeyData['key_name']['DOACTION']); ?></th>
        </tr>
        <?php echo showTree($tree,$field,$_func);?>
        </table>
    </div>
   <!-- END ListTree -->
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