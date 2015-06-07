<?php
	if(!defined('IN_PT')) exit('Access Denied');
	$lifeTime = 3 * 3600;
	session_set_cookie_params($lifeTime);
	session_start();
	require('./inc/common.inc.php');

	$search_arr = array("/ union /i","/ select /i","/ update /i","/ outfile /i","/ or /i");
	$replace_arr = array('&nbsp;union&nbsp;','&nbsp;select&nbsp;','&nbsp;update&nbsp;','&nbsp;outfile&nbsp;','&nbsp;or&nbsp;');
	$_POST = strip_sql($_POST);   //对请求的数据进行检测
	$_GET = strip_sql($_GET);
	unset($search_arr, $replace_arr);

	if(!get_magic_quotes_gpc()){
		$_POST = new_addslashes($_POST);
		$_GET = new_addslashes($_GET);
		$_COOKIE = new_addslashes($_COOKIE);
	}
	
	@extract($_POST, EXTR_OVERWRITE);
	@extract($_GET, EXTR_OVERWRITE);
	$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : getYMD();   //取从哪个页面跳转过来的 referer
	$pagesize = 25;

	
	$referer =  isset($_SERVER['HTTP_REFERER']) ?  $_SERVER['HTTP_REFERER'] : '';
	;
	
?>