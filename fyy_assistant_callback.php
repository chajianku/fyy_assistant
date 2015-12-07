<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); }

function callback_init() {
	$s=array('murl'=>'','key'=>'');
	option::pset('fyy_assistant',$s);
}

function callback_remove() {
	option::pdel('fyy_assistant');
}
?>