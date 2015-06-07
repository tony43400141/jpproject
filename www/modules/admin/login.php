<?php
	_check_define();

	if( isset($loginin) && $loginin == 'login' ) {						// 登录验证

		// 验证码有误
		if( $_SESSION['auth_num'] != $CheckCode )
			showmessage('验证码错误', $referer);

		// 登录判断
		require(CLASS_DIR . 'admin.class.php');
		$admin = new Admin();
		$result = $admin->Login($UserName, $Password);

		if(!$result) {
			showmessage('登陆失败', $referer);
		} else {
			if(is_numeric($result['admin_user']))
			{
				$SESSIONID = 'yyx_'.$result['admin_user'];
			}
			else
			{
				$SESSIONID = $result['admin_user'];
			}
			
			$_SESSION[$SESSIONID][SESSION_NAME.'_admin']   	 = $result['admin_user'];
			$_SESSION[$SESSIONID][SESSION_NAME.'_adminID'] 	 = $result['admin_id'];
			$_SESSION[$SESSIONID][SESSION_NAME.'_adminrights'] = $result['rights'];
			$CookieTime = 3*3600;
			setcookie('admin_SESSIONID', $SESSIONID,time()+$CookieTime);
			
			
			if(isset($result['rights']['6']['60'])){
				$_SESSION[$SESSIONID]['session_finance'] = $result['rights']['6']['60'];
			}else{
				$_SESSION[$SESSIONID]['session_finance'] = 0;
			}
		}
		showmessage('登陆成功', "?action=index");
	} else {
		$tpl->display($temple_folder.'/login.html');
	}
?>