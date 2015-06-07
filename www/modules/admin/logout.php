<?php
	_check_define();
	
	// unset($_SESSION[ROOT_NAME.'_admin'],$_SESSION[ROOT_NAME.'_adminID'], $_SESSION[ROOT_NAME.'_adminrights']);
	
	$SESSIONID = $_COOKIE['admin_SESSIONID'];
	unset($_SESSION[$SESSIONID][SESSION_NAME.'_admin'],$_SESSION[$SESSIONID][SESSION_NAME.'_adminID'], $_SESSION[$SESSIONID][SESSION_NAME.'_adminrights']);
	
	
	// $_SESSION[$SESSIONID][SESSION_NAME.'_admin']   	 = $result['admin_user'];
	// $_SESSION[$SESSIONID][SESSION_NAME.'_adminID'] 	 = $result['admin_id'];
	// $_SESSION[$SESSIONID][SESSION_NAME.'_adminrights'] = $result['rights'];
	
	setcookie('admin_user', '');
	setcookie('admin_id', '');
	setcookie('admin_password', '');
	setcookie('admin_SESSIONID', '');
	
	showmessage('退出成功!!', "?action=login");
?>