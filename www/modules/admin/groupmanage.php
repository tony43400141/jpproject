<?php
if(!defined('IN_PT')) exit('Access Denied');
	_check_define();
	// 检测权限
	_check_rights(11,1);

	// 权限配置文件
	include(INC_DIR . 'rights_config.php');

	// 创建分组对象
	include(CLASS_DIR . 'group.class.php');
	$group_obj = new UserGroup();

	switch ($do){
		case 'saveadd':															// 添加分组
			// 分组名
			$groupname = trim($groupname);

			// 分组名为空
			if ( !$groupname )
				showmessage($ops['gnameempty']);

			!isset($grights) && $grights = array();
			// 新增
			$group_obj->add_group($groupname, $grights);

			showmessage($ops['addgroupsuccess'], '?action=groupmanage&do=list');

			break;
		case 'edit':															// 修改分组
			$group_array = $group_obj->get_one_group($id);
			// var_dump($group_array);
			// 获取分组信息
			$tpl->assign('result', $group_array);

			break;
		case 'saveedit':														// 保存修改

			// 分组名
			$groupname = trim($groupname);

			// 分组名为空
			if ( !$groupname )
				showmessage($ops['gnameempty']);

			!isset($grights) && $grights = array();

			// 修改
			$group_obj->edit_group($gid, $groupname, $grights);

			showmessage($ops['editgroupsuccess'], '?action=groupmanage&do=list');

			break;
		case 'delete':															// 删除分组

			// 该分组下有用户无法删除
			if( $group_obj->group_has_users($id) )
				showmessage($ops['hasusers']);

			// 删除
			$group_obj->delete_group($id);

			showmessage($ops['deletegroupsuccess'], '?action=groupmanage&do=list');

			break;
		default:																// 查询所有分组信息
			// 获取数据
			$tpl->assign('result', $group_obj->get_groups());
	}

	$tpl->assign('do', 		$do);
	$tpl->assign('rights', 	$ACCOUNT_RIGHTS);

	unset($ACCOUNT_RIGHTS);

	$tpl->display($temple_folder.'/groupmanage.html');
?>