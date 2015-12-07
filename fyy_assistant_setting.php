<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 
$s = option::pget('fyy_assistant');
	if(empty($s['murl']) && $_REQUEST['begin'] != 'assistant'){
		if(!empty($_REQUEST['begin']) && $_REQUEST['begin'] != 'assistant'){
			echo '</br><div class="alert alert-danger"><h4>密码错误</h4>如果您不知道开启密码，请先在总站安装云签助手（总站）</div>';
		}
		?>
			<div class="jumbotron">
				<h1>Hello, master!</h1>
				<h2>你的云签助手（分站）已经安装完毕</h2>
				<br/>
				<p>请填写开启密码进入设置</p>
				<br/>
				<form action="index.php?mod=admin:setplug&plug=fyy_assistant" method="post">
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">开启密码</span>
					<input type="password" name="begin" class="form-control" required/>
				</div>
					<br/><button type="submit" class="btn btn-primary">提交</button>
				</form>
			</div>
		<?php
		die;
	}
?>

<ul class="nav nav-tabs" role="tablist">
	<li<?php if(isset($_REQUEST['plug']) && $_REQUEST['plug']=="fyy_assistant" && $_REQUEST['p']==''){echo ' class="active"';}?>><a href="index.php?mod=admin:setplug&plug=fyy_assistant">设置</a></li>
	<li<?php if(isset($_REQUEST['p']) && $_REQUEST['p']=='2'){echo ' class="active"';}?>><a href="index.php?mod=admin:setplug&plug=fyy_assistant&p=2">上载处</a></li>
</ul>

<?php
if(isset($_REQUEST['plug']) && $_REQUEST['plug']=="fyy_assistant" && $_REQUEST['p']==''){
	if (isset($_GET['ok'])) { echo '</br><div class="alert alert-success">设置保存成功</div>'; }
	elseif (isset($_REQUEST['begin'])) { echo '</br><div class="alert alert-warning"><h4>提示：</h4>使用本插件必须把设置填写完整、无误</br><strong>总站地址：</strong>安装了云签助手（总站）的站点的地址。注意一定要形式规范</br><strong>key：</strong>您在总站填什么，这里就填什么</div>'; }
?>
	<h3>云签助手 - 设置</h3>
	1.管理员要删除某绑定，必须使用总站管理中心的删除用户功能！<br/>
	　<font color="red">不允许从数据库或其他插件删除绑定！</font></br>
	2.记录百度用户名功能<font color="red">必须开启！</font>，否则将导致无法绑定！</br>
	<form action="setting.php?mod=plugin:fyy_assistant" method="post">
	</br>基础设置
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width:30%"></th>
				<th style="width:70%"></th>
			</tr>
		</thead>
		<tbody>
		<!--
			<tr>
				<td>总开关</td>
				<td><input type="checkbox" name="on" <?php if($s['on'] == 1) echo 'checked="checked"'; ?> value='1'><?php if($s['on'] == 1) echo ' 总开关目前已开启'; else echo ' <b>本插件目前处于停用状态</b>';?></td>
			</tr>
		-->
			<tr>
				<td>总站地址<br/>形如http://www.stus8.com/</td>
				<td><input type="url" value="<?php echo $s['murl'] ?>" name="murl" class="form-control" required/></td>
			</tr>
			<tr>
				<td>key<br/>支持任意长度的数字和字母</td>
				<td><input type="text" value="<?php echo $s['key'] ?>" name="key" class="form-control" required/></td>
			</tr>
		</tbody>
	</table>
	</br><button type="submit" class="btn btn-success">保存设定</button>
	</form>
<?php
}
elseif(isset($_REQUEST['p']) && $_REQUEST['p']=='2') {
	if (isset($_GET['shangchuan'])) {
		$key=option::xget('fyy_assistant','key');
		$murl=option::xget('fyy_assistant','murl');
		$post1 = new wcurl("{$murl}?pub_plugin=fyy_massistant&shangchuan&key={$key}&stname=".SYSTEM_NAME."&sturl=".SYSTEM_URL);
		$a1 = $post1->exec();
		if($a1 == 'can'){/*只可在总数据库不存在该站点信息时使用，防止重复上传*/
			/*$post3 = new wcurl("{$murl}?pub_plugin=fyy_massistant&addst&key={$key}&stname=".SYSTEM_NAME."&sturl=".SYSTEM_URL);
			$a3 = $post3->exec();*/
			global $m;
			$result=$m->query("SELECT * FROM `".DB_NAME."`.`".DB_PREFIX."baiduid`");
			sleep(0.3);
			$allrow = $result->num_rows;
			$onrow = 0;
			while($onrow < $allrow) {
				$one = $m->fetch_array($result);
				$bdname = $one['name'];
				if(empty($bdname)){/*name字段为空*/
					$pid=$one['id'];
					$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `id` = '{$pid}'");
					$uid=$one['uid'];
					$t=$m->once_fetch_array("SELECT `t` FROM  `".DB_NAME."`.`".DB_PREFIX."users` WHERE  `id` = '{$uid}'");
					if(empty($t)){
						$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `bduss` = '{$bduss}'");
					}
					else{
						$t=$t['t'];
						$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX.$t."` WHERE `pid` = '{$pid}'");
					}
				}
				else{
					$bduss = $one['bduss'];
					$uid = $one['uid'];
					$info = $m->once_fetch_array("SELECT `name`,`email` FROM `".DB_NAME."`.`".DB_PREFIX."users` WHERE `id` = '{$uid}'");
					$usname = $info['name'];
					$email = $info['email'];
					$post2 = new wcurl("{$murl}?pub_plugin=fyy_massistant&bind&key={$key}&stname=".SYSTEM_NAME."&usname={$usname}&bduss={$bduss}&bdname={$bdname}&email={$email}");
					$a2 = $post2->exec();
					sleep(0.015);
					if($a2 == 'found'){/*重复绑定*/
						$pid=$one['id'];
						$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `id` = '{$pid}'");/* AND `bduss` = '{$bduss}'*/
						$uid=$one['uid'];
						$t=$m->once_fetch_array("SELECT `t` FROM  `".DB_NAME."`.`".DB_PREFIX."users` WHERE  `id` = '{$uid}'");
						if(empty($t)){
							$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."baiduid` WHERE `bduss` = '{$bduss}'");
						}
						else{
							$t=$t['t'];
							$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX.$t."` WHERE `pid` = '{$pid}'");
						}
					}
				}
				$onrow++;
				sleep(0.02);
			}
		echo '</br><div class="alert alert-success">成功处理（上传或删除）了'."{$onrow}条绑定信息</div>";
		}
		else { echo '</br><div class="alert alert-danger">总站已存在本站数据 或 站点名称重复，禁止上传！</div>'; }
	}
?>
	<h3>上传处</h3></br>
	<h4>注意：</h4>
	1.所有站点第一次使用本插件，都需要执行一次“上传”</br>
	2.如果您的用户量较大，上传也许会进行较长时间，请耐心等待</br>
	</br>
	<strong><h4>警告：</h4>
	所有name字段为空（bduss未记录或失效）的绑定<font color="red">将会被删除</font></br>
	这意味着V3.8前的绑定<font color="blue">[如果未手动<a href="http://www.stus8.com/forum.php?mod=viewthread&tid=6468" target="_blank">刷新百度用户名</a>]</font>将被认为是失效而删除</strong></br>
	</br>
	<h4>介绍：</h4>
	本功能同时会处理重复绑定的用户（在所有站点中会保留一个）</br></br>
	<a href="<?php echo SYSTEM_URL; ?>index.php?mod=admin:setplug&plug=fyy_assistant&p=2&shangchuan" class="btn btn-primary">上传</a></br>
<?php
}
