<?php
/*
Plugin Name: 云签助手（分站）
Version: 1.3
Plugin URL: http://www.stus8.com/forum.php?mod=viewthread&tid=6531
Description: 多站点的最佳管理工具
Author: FYY
Author Email:fyy@l19l.com
Author URL: http://fyy.l19l.com
For: V3.8+
*/
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 

function fyy_assistant_setting_navi() {
	?>
	<li <?php if($_GET['plug'] == 'fyy_assistant') { echo 'class="active"'; } ?> ><a href="index.php?mod=admin:setplug&plug=fyy_assistant"><span class="glyphicon glyphicon-leaf"></span> 云签助手</a></li>
	<?php
}

function fyy_assistant_baiduid_1() {/*删除模式*/
	$key=option::xget('fyy_assistant','key');
	$murl=option::xget('fyy_assistant','murl');
	$post = new wcurl("{$murl}?pub_plugin=fyy_massistant&delus&key={$key}&stname=".SYSTEM_NAME.'&usname='.NAME);
	$a = $post->exec();
	if($a != 'ok1') { msg('与主站连接失败，请重试。<br/>若重复多次失败，请联系管理员！'); }
}

function fyy_assistant_baiduid_2() {/*绑定模式*/
	$key=option::xget('fyy_assistant','key');
	$murl=option::xget('fyy_assistant','murl');
	global $baidu_name,$bduss;
	$post = new wcurl("{$murl}?pub_plugin=fyy_massistant&bind&key={$key}&stname=".SYSTEM_NAME.'&usname='.NAME."&bduss={$bduss}&bdname={$baidu_name}&email=".EMAIL);
	$a = $post->exec();
	if($a != 'ok2'){
		if($a == 'found') { msg('您在另一站点中对此百度账号有过对此账号的绑定，<br/>或您的账号有过违规记录。<br/>如有疑问，请联系管理员！'); }
		else { msg('与主站连接失败，请重试。<br/>若重复多次失败，请联系管理员！'); }
	}
}

function fyy_assistant_baiduid_3() {/*解绑模式*/
	$key=option::xget('fyy_assistant','key');	
	$murl=option::xget('fyy_assistant','murl');
	global $m,$del;
	$bduss=$m->once_fetch_array("SELECT `bduss` FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `id` = '{$del}'");
	$bduss=$bduss['bduss'];
	$post = new wcurl("{$murl}?pub_plugin=fyy_massistant&unbind&key={$key}&bduss={$bduss}&usname=".NAME);
	$a = $post->exec();
	if($a != 'ok3'){ msg('与主站连接失败，请重试。<br/>若重复多次失败，请联系管理员！'); }
}

function fyy_assistant_navi() {
	echo '<li ';
	if(isset($_GET['pub_plugin']) && $_GET['pub_plugin'] == 'fyy_assistant') { echo 'class="active"'; }
	echo '><a href="index.php?pub_plugin=fyy_assistant"><span class="glyphicon glyphicon-question-sign"></span> 忘记站点</a></li>';
}

function fyy_assistant_delus() {
	$key=option::xget('fyy_assistant','key');
	foreach ($_POST['user'] as $uid) {
		$usname = $m->once_fetch_array("SELECT `name` FROM `".DB_NAME."`.`".DB_PREFIX."users` WHERE `id` = '{$uid}'");
		$post = new wcurl("{$murl}?pub_plugin=fyy_massistant&delus&key={$key}&stname=".SYSTEM_NAME."&usname={$usname}");
		$a = $post->exec();
	}
}

/*navi_3左侧第二栏; navi_8顶部*/
addAction('baiduid_set_1','fyy_assistant_baiduid_1');
addAction('baiduid_set_2','fyy_assistant_baiduid_2');
addAction('baiduid_set_3','fyy_assistant_baiduid_3');
addAction('admin_users_delete','fyy_assistant_delus');
addAction('admin_plugins','fyy_assistant');/*admin设置（插件管理）*/
addAction('navi_3','fyy_assistant_setting_navi');/*admin设置*/
addAction('navi_8','fyy_assistant_setting_navi');/*admin设置*/
addAction('navi_3','fyy_assistant_backstage');/*admin后台管理（上载中心）*/
addAction('navi_8','fyy_assistant_backstage');/*admin后台管理（上载中心）*/
addAction('navi_10','fyy_assistant_navi');
addAction('navi_11','fyy_assistant_navi');