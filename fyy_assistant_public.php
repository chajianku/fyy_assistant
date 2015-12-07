<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); }
if(isset($_REQUEST['del']) ){
	$key=option::xget('fyy_assistant','key');
	if (!isset($_REQUEST['key']) || $_REQUEST['key'] != $key) { die('fuckyou'); }
	global $m;
	if(!empty($_REQUEST['bdname'])){
		$bdname=$_REQUEST['bdname'];
		$one=$m->once_fetch_array("SELECT `id`,`uid` FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `name` = '{$bdname}'");
		if(!empty($one)){
			echo 'done1';
			$uid=$one['uid'];
			$pid=$one['id'];
			$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `name` = '{$bdname}'");
			$t=$m->once_fetch_array("SELECT `t` FROM  `".DB_NAME."`.`".DB_PREFIX."users` WHERE `id` = '{$uid}'");
			$t=$t['t'];
			if(isset($t) && !empty($t)) { $m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX.$t."` WHERE `pid` = '{$pid}'"); }
		}
		else { echo '4041'; }
		die;
	}
	elseif(!empty($_REQUEST['usname'])){
		$usname=$_REQUEST['usname'];
		$one=$m->once_fetch_array("SELECT `t`,`id` FROM  `".DB_NAME."`.`".DB_PREFIX."users` WHERE `name` = '{$usname}'");
		if(!empty($one)){
			echo 'done2';
			$uid=$one['id'];
			$t=$one['t'];
			$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `uid` = '{$uid}'");
			$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX.$t."` WHERE `uid` = '{$uid}'");
		}
		else { echo '4042'; }
		die;
	}
}
else{
	loadhead();
	$murl=option::xget('fyy_assistant','murl');
?>
<div class="panel panel-primary" style="margin:5% 15% 5% 15%;">
	<div class="panel-heading">
		<h3 class="panel-title">忘记站点</h3>
	</div>   
	<div style="margin:0% 5% 5% 5%;"><h4>输入任意一个即可</h4>
		<form name="form" method="post" action="<?php echo $murl; ?>?pub_plugin=fyy_massistant&search&">
			<table class="table table-striped">
				<thead>
					<tr>
						<th style="width:30%"></th>
						<th style="width:70%"></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>百度ID<br/></td>
						<td><input type="text" class="form-control" name="bdname" id="bdname" placeholder=""/></td>
					</tr>
					<tr>
						<td>用户名或邮箱<br/></td>
						<td><input type="text" class="form-control" name="eou" id="eou" placeholder=""/></td>
					</tr>
				</tbody>
			</table></br>
			<button type="submit" class="btn btn-primary">查找</button><br/><br/>
		</form>
		<p>插件作者：<a href="http://fyy.l19l.com/">FYY</a> // 程序作者：<a href="http://zhizhe8.net" target="_blank">无名智者</a> & <a href="http://www.longtings.com/" target="_blank">mokeyjay</a></p>
    </div>
</div>
<?php
}
?>