<?php
if(!defined('IN_PT')) exit('Access Denied');
	_check_define();

	// 左侧菜单配置文件
	require_once(INC_DIR . 'menus_config.php');
	$SESSIONID = $_COOKIE['admin_SESSIONID'];
	
	$tpl->assign("username", 	$_SESSION[$SESSIONID][SESSION_NAME.'_admin']);
	$tpl->assign("menus", 		$menus);
	$tpl->assign("rights", 		$_SESSION[$SESSIONID][SESSION_NAME.'_adminrights']);
	// var_dump($menus);
	// var_dump($_SESSION[SESSION_NAME.'_adminrights']);
	unset($menus);

	$tpl->display($temple_folder.'/left.html');
?>