<?php
/****
 *
 *	系统配置信息管理
 *
****/
_check_define();

// 检测权限
_check_rights(1,1);

require(CLASS_DIR . 'sys_config.class.php');
require(CLASS_DIR . 'cache.class.php');
$sys_config = new Sys_config();

// 获取用户分组信息
include(CLASS_DIR . 'group.class.php');
$group_obj = new UserGroup();
$tpl->assign('groups', $group_obj->get_group_pair());
if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 系统配置信息列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(14,1);
	$type = 'edit';
	$id = 1;
	$result = $sys_config->getSys_configResult($id);
	$tpl->assign('result', $result);
	$tpl->assign('type', $type);
	$tpl->assign('id', $id);
	$tpl->display($temple_folder.'/sys_config/add.html');
}
/*------------------------------------------------------ */
//-- 系统配置信息添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(14,1);
	$type = empty($type) ? '' : intval($type);
	if($type == 'edit'){
		$result = $sys_config->getSys_configResult($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	$tpl->display($temple_folder.'/sys_config/add.html');
}
/*------------------------------------------------------ */
//-- 系统配置信息保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(14,1);
	
	$post_array = array(
					'key_words' => $key_words, 
					'is_server' => $is_server, 
					'des_server' => $des_server,
					'is_register' => $is_register, 
					'des_register' => $des_register,
					'is_pay' => $is_pay,
					'sys_title' => $sys_title,
					'sys_desc' => $sys_desc,
					'statistics' => $statistics
				);
	$sys_config->insertSys_config($post_array);
	
	//生成缓存
	$cache = new File_Cache();
	$cache->cache_data = $post_array;
	$cache->array_name = 'sys_config';
	$cache->file_path = CACHE_DIR .'sys_config.php';
	$cache->create_cache_file();
	
	showmessage($ops['insertSys_configSuccess'], '?action=sys_config&do=list');
}
/*------------------------------------------------------ */
//-- 系统配置信息保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(14,1);
	$post_array = array(
					'key_words' => $key_words, 
					'is_server' => $is_server, 
					'des_server' => $des_server,
					'is_register' => $is_register, 
					'des_register' => $des_register,
					'is_pay' => $is_pay,
					'sys_title' => $sys_title,
					'sys_desc' => $sys_desc,
					'statistics' => $statistics
				);
	$id_array = array(
		'sys_config_id' => $id
	);
	$sys_config->editSys_config($post_array, $id_array);
	
	
	//生成缓存
	$cache = new File_Cache();
	$cache->cache_data = $post_array;
	$cache->array_name = 'sys_config';
	$cache->file_path = CACHE_DIR .'sys_config.php';
	$cache->create_cache_file();
	
	showmessage($ops['editSys_configSuccess'], '?action=sys_config&do=list');
}
/*------------------------------------------------------ */
//-- 系统配置信息删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(14,1);
	$result = $sys_config->delSys_config($id);
	showmessage($ops['delSys_configSuccess'], '?action=sys_config&do=list');
}
/*------------------------------------------------------ */
//-- 系统配置信息分类列表
/*------------------------------------------------------ */
elseif($do == 'category'){
	// 检测权限
	_check_rights(14,1);
	$baselink = '?action=sys_config&do=category';
	if(!isset($order_name)){ $order_name=''; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $sys_config->getSys_configCategory($baselink,$order_name,$order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/sys_config/category_list.html');
}
/*------------------------------------------------------ */
//-- 系统配置信息分类添加 编辑
/*------------------------------------------------------ */
elseif($do == 'category_add'){
	// 检测权限
	_check_rights(14,1);
	$type = empty($type) ? '' : intval($type);
	if($type == 'edit'){
		$result = $sys_config->getSys_configCategoryResult($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	
	$tpl->display($temple_folder.'/sys_config/category_add.html');
}
/*------------------------------------------------------ */
//-- 系统配置信息分类保存
/*------------------------------------------------------ */
elseif($do == 'category_insert'){
	// 检测权限
	_check_rights(14,1);
	$sys_config->insertSys_configCategory($mc_name, $mc_desc, $status);
	showmessage($ops['insertSys_configCategorySuccess'], '?action=sys_config&do=category');
}
/*------------------------------------------------------ */
//-- 系统配置信息分类保存
/*------------------------------------------------------ */
elseif($do == 'category_update'){
	// 检测权限
	_check_rights(14,1);
	$sys_config->editSys_configCategory($id, $mc_name, $mc_desc);
	showmessage($ops['editSys_configCategorySuccess'], '?action=sys_config&do=category');
}
/*------------------------------------------------------ */
//-- 系统配置信息分类删除
/*------------------------------------------------------ */
elseif($do == 'category_del'){
	// 检测权限
	_check_rights(14,1);
	$result = $sys_config->delSys_configCategory($id);
	showmessage($ops['delSys_configCategorySuccess'], '?action=sys_config&do=category');
}

/*------------------------------------------------------ */
//-- 系统配置信息列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/sys_config_list.html');
}

?>
