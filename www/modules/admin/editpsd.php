<?php
if(!defined('IN_PT')) exit('Access Denied');
	_check_define();
	// 检测权限
	_check_rights(151,15);

	// 权限配置文件
	include(INC_DIR . 'rights_config.php');

	// 创建分组对象
	include(CLASS_DIR . 'group.class.php');
	$group_obj = new UserGroup();
	if(!isset($do)){ $do = 'edit';}
	switch ($do){
		case 'edit':				
			$tpl->assign('username', 	$_SESSION[$SESSIONID][SESSION_NAME.'_admin']);
			$tpl->display($temple_folder.'/editpsd.html');
			break;
		case 'saveedit':														// 保存修改
			if($PwdConfirm != $Password){
				showmessage($ops['editerrpsdsuccess'], '?action=editpsd&do=edit');
			}else{
				$return = $group_obj->edit_psd($_SESSION[$SESSIONID][SESSION_NAME.'_adminID'],$oldpassword,$Password);
				if($return == 1){
					showmessage($ops['editpsdsuccess'], '?action=editpsd&do=edit');
				}else{
					showmessage($ops['editpsdsuccess1'], '?action=editpsd&do=edit');
				}
			}
			break;
		default:																// 查询所有分组信息
			// 获取数据
			$tpl->assign('result', $group_obj->get_groups());
	}

	// $tpl->assign('do', 		$do);
	// $tpl->assign('rights', 	$ACCOUNT_RIGHTS);

	// unset($ACCOUNT_RIGHTS);

	
?>