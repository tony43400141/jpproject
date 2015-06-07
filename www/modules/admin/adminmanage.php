<?php
if(!defined('IN_PT')) exit('Access Denied');
	_check_define();

	// 检测权限
	_check_rights(1);

	require(CLASS_DIR . 'admin.class.php');
	$admin = new Admin();
	$result = $admin->showAdmin();

	// 获取用户分组信息
	include(CLASS_DIR . 'group.class.php');
	$group_obj = new UserGroup();
	$tpl->assign('groups', $group_obj->get_group_pair());

	if( isset($do) ){
		switch ($do){
			case 'saveadd':															// 添加管理员

				$admin->addAdmin($username,$Password,$group);
				showmessage($ops['addadmin'], '?action=adminmanage');
				break;
			case 'edit':															// 点击修改,显示出修改表单
				$res = $admin->showAdmin($id);
				$tpl->assign('res',$res);
				break;
			case 'delete':															// 删除账号

				$admin->deleteAdmin($id);
				showmessage($ops['deladmin'], '?action=adminmanage');
				break;
			case 'editpwd':															// 修改账号信息

				$admin->updateAdmin($id, $Password, $group);
				showmessage($ops['editadmin'], '?action=adminmanage');
				break;
		}

		$tpl->assign('do', $do);
	}

	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/adminmanage.html');
?>
