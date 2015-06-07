<?php
	define('IN_PT', true);
	ini_set('date.timezone','Asia/Shanghai');
	header("Content-type:text/html;charset=utf-8");
	error_reporting(0);
	require('./inc/admin_config.class.php');

	// 默认的文件
	!isset($action) && $action = 'login';

	// 非法请求
	preg_match("/^[0-9A-Za-z_]+$/", $action) or showmessage('Invalid Request.');
	
	if($action != 'static') {
		if(isset($_COOKIE['admin_SESSIONID'])){
			$SESSIONID = $_COOKIE['admin_SESSIONID'];
		}else{
			$SESSIONID = '';
		}
		
		if($action != 'login' && !isset($_SESSION[$SESSIONID][SESSION_NAME.'_admin']))
			showmessage("请先登入网站", '?action=login');
	}
	
	$temple_folder = 'admin';
	$tpl->assign('temple_folder', $temple_folder);
	$filepath = ROOT_DIR.'modules/'.$temple_folder.'/'.$action.'.php';
	
	// 加载对应的功能文件
	if( file_exists($filepath) )
		include($filepath);
	else
		showmessage($ops['nofile'], '');
?>