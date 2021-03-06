<?php if (!defined('THINK_PATH')) exit();?><?xml version="1.0" encoding="UTF-8"?>
<root>
<group name="config" info="系统配置">
<action type="editDetail" info="修改配置数据"><![CDATA[ 
修改了[<?php echo ($name); ?>]的配置数据。
]]></action>
<action type="editPagekey" info="修改页面配置项"><![CDATA[ 
修改了[<?php echo ($name); ?>]的页面配置项。
]]></action>
<action type="editSearchPagekey" info="修改搜索配置项"><![CDATA[ 
修改了[<?php echo ($name); ?>]的搜索配置项。
]]></action>
<action type="delFooter" info="删除页脚"><![CDATA[ 
删除了ID为<?php echo ($ids); ?>的页脚文章。
]]></action>
<action type="permissiongroup" info="修改权限组配置"><![CDATA[ 
修改了权限组配置。
]]></action>
<action type="addCreditType" info="添加积分类型"><![CDATA[ 
添加了新的积分类型:<?php echo ($CreditName); ?> (<?php echo ($CreditType); ?>);
]]></action>
<action type="delCreditType" info="删除积分类型"><![CDATA[ 
删除了积分类型:<?php echo ($CreditType); ?>;
]]></action>
<action type="setUserCredit" info="设定用户积分"><![CDATA[ 
设定用户积分。操作:<?php echo ($todo); ?>,积分类型:<?php echo ($creditType); ?>,用户ID:<?php echo ($uids); ?>,用户组:<?php echo ($userGroup); ?>,用户状态:<?php echo ($userStatus); ?>,数值:<?php echo ($nums); ?>;
]]></action>
<action type="savePer" info="修改权限配置"><![CDATA[ 
修改了[<?php echo ($app); ?> - <?php echo ($module); ?>]的权限配置。
]]></action>

</group>

<group name='content' info='内容管理'>
<action type="addArticle" info="添加公告"><![CDATA[ 
添加了公告[<?php echo ($title); ?>]。
]]></action>
<action type="delArticle" info="删除公告"><![CDATA[ 
删除了ID为<?php echo ($ids); ?>的公告。
]]></action>

</group>

<group name="system" info="系统管理">
<action type="cleanlog" info="清理知识"><![CDATA[ 
清理<?php echo ($date); ?>月前的知识。
]]></action>
<action type="dellog" info="删除知识"><![CDATA[ 
删除<?php echo ($nums); ?>知识记录，知识ID：<?php echo ($ids); ?>。
]]></action>
<action type="logsArchive" info="知识归档"><![CDATA[
归档知识,操作结果： <?php echo ($msg); ?>;
]]></action>
</group>

<group name="extends" info="扩展管理">
<action type="appManage" info="应用管理"><![CDATA[ 
<?php echo !empty($app_id) ? '编辑':'安装'; ?> 应用[<?php echo ($app_name); ?> - <?php echo ($app_alias); ?>];
]]></action>
<action type="appUninstall" info="卸载应用"><![CDATA[ 
卸载应用,ID:<?php echo ($app_id); ?>.
]]></action>
<action type="appStatus" info="卸载应用"><![CDATA[ 
修改应用状态,应用ID:<?php echo ($app_id); ?>,状态:<?php echo ($status); ?>。
]]></action>
<action type="editCreditNode" info="修改积分节点">
<![CDATA[ 
修改积分节点属性,应用:<?php echo ($appname); ?>,动作:<?php echo ($action); ?>,动作别名<?php echo ($info); ?>
]]>
</action>
<action type="delCreditNode" info="删除积分节点">
<![CDATA[ 
删除积分节点,知识信息:<?php echo ($log); ?>
]]>
</action>
<action type="addCreditNode" info="添加积分节点">
<![CDATA[ 
添加积分节点,应用:<?php echo ($appname); ?>,动作:<?php echo ($action); ?>,动作别名<?php echo ($info); ?>
]]>
</action>
<action type="editPermNode" info="修改权限节点">
<![CDATA[ 
修改权限节点属性,应用:<?php echo ($appname); ?>,动作:<?php echo ($action); ?>,动作别名<?php echo ($info); ?>
]]>
</action>
<action type="delPermNode" info="删除权限节点">
<![CDATA[ 
删除权限节点,知识信息:<?php echo ($log); ?>
]]>
</action>
<action type="addPermNode" info="添加权限节点">
<![CDATA[ 
添加权限节点,应用:<?php echo ($appname); ?>,模块:<?php echo ($module); ?> ,规则<?php echo ($rule); ?>(<?php echo ($ruleinfo); ?>)
]]>
</action>

<action type="editFeedNode" info="修改分享节点">
<![CDATA[ 
修改分享模板属性,应用:<?php echo ($appname); ?>,分享类型:<?php echo ($nodetype); ?>（<?php echo ($nodeinfo); ?>）
]]>
</action>
<action type="delFeedNode" info="删除分享节点">
<![CDATA[ 
删除分享模板。
]]>
</action>
<action type="addFeedNode" info="添加分享节点">
<![CDATA[ 
添加分享模板,应用:<?php echo ($appname); ?>,分享类型:<?php echo ($nodetype); ?>（<?php echo ($nodeinfo); ?>）
]]>
</action>

</group>

</root>