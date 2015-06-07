<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	视频管理
 *
****/
_check_define();

// 检测权限
_check_rights(2,2);

require(CLASS_DIR . 'portalpic.class.php');
$portalpic = new Portalpic();

// 获取用户分组信息
include(CLASS_DIR . 'group.class.php');
$group_obj = new UserGroup();
$tpl->assign('groups', $group_obj->get_group_pair());
if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 视频列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(22,2);
	$baselink = '?action=portalpic&do=list';
	if(!isset($order_name)){ $order_name=''; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $portalpic->getPortalpic($baselink,$order_name,$order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/portalpic/list.html');
}
/*------------------------------------------------------ */
//-- 视频添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(22,2);
	$type = empty($type) ? '' : intval($type);
	$cat_list = $portalpic->getPortalpicCategoryList();
	$tpl->assign('cat_list', $cat_list);
	$cat_count = $portalpic->getPortalpicCategoryCount();
	$tpl->assign('cat_count', $cat_count);
	$tpl->assign('portalpic_content' ,	fck('','portalpic_content'));
	if($type == 'edit'){
		$result = $portalpic->getPortalpicResult($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
		$tpl->assign('portalpic_content' ,	fck(stripslashes($result[0]['portalpic_content']),'portalpic_content'));
	}
	$tpl->display($temple_folder.'/portalpic/add.html');
}
/*------------------------------------------------------ */
//-- 视频保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(22,2);
	
	$pic = '';
	if(!empty($_FILES['portalpic_pic']['name'])){
		require(CLASS_DIR . 'bigupload.class.php');
		$filepath = 'upfiles/portalpic/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$year = date("Y",time());
		$month = date("m",time());
		$day = date("d",time());
		$filepath = $filepath.$year.'/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$filepath = $filepath.$month.'/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'portalpic_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$pic = $upload->newfilename;
	}
	$post_array = array(
					'category_name' => $category_name, 
					'portalpic_title' => $portalpic_title, 
					'portalpic_order' => $portalpic_order,
					'portalpic_pic' => $pic, 
					'portalpic_content' => addslashes($portalpic_content),
					'status' => $status
				);
	$portalpic->insertPortalpic($post_array);
	showmessage($ops['insertPortalpicSuccess'], '?action=portalpic&do=list');
}
/*------------------------------------------------------ */
//-- 视频保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(22,2);
	$pic = '';
	if(!empty($_FILES['portalpic_pic']['name'])){
		require(CLASS_DIR . 'bigupload.class.php');
		$filepath = 'upfiles/portalpic/';
		$year = date("Y",time());
		$month = date("m",time());
		$day = date("d",time());
		$filepath = $filepath.$year.'/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$filepath = $filepath.$month.'/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'portalpic_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$pic = $upload->newfilename;
	}
	$post_array = array(
					'category_name' => $category_name, 
					'portalpic_title' => $portalpic_title, 
					'portalpic_order' => $portalpic_order,
					'portalpic_pic' => $pic, 
					'portalpic_content' => addslashes($portalpic_content),
					'status' => $status
				);
	$portalpic->editPortalpic($post_array, $id);
	showmessage($ops['editPortalpicSuccess'], '?action=portalpic&do=list');
}
/*------------------------------------------------------ */
//-- 视频删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(22,$game);
	$result = $portalpic->delPortalpic($id);
	showmessage($ops['delPortalpicSuccess'], '?action=portalpic&do=list');
}
/*------------------------------------------------------ */
//-- 视频分类列表
/*------------------------------------------------------ */
elseif($do == 'category'){
	// 检测权限
	_check_rights(21,2);
	$baselink = '?action=portalpic&do=category';
	if(!isset($order_name)){ $order_name=''; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $portalpic->getPortalpicCategory($baselink,$order_name,$order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/portalpic/category_list.html');
}
/*------------------------------------------------------ */
//-- 视频分类添加 编辑
/*------------------------------------------------------ */
elseif($do == 'category_add'){
	// 检测权限
	_check_rights(21,2);
	$type = empty($type) ? '' : intval($type);
	if($type == 'edit'){
		$result = $portalpic->getPortalpicCategoryResult($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	
	$tpl->display($temple_folder.'/portalpic/category_add.html');
}
/*------------------------------------------------------ */
//-- 视频分类保存
/*------------------------------------------------------ */
elseif($do == 'category_insert'){
	// 检测权限
	_check_rights(21,2);
	$portalpic->insertPortalpicCategory($category_name, $category_desc);
	showmessage($ops['insertPortalpicCategorySuccess'], '?action=portalpic&do=category');
}
/*------------------------------------------------------ */
//-- 视频分类保存
/*------------------------------------------------------ */
elseif($do == 'category_update'){
	// 检测权限
	_check_rights(21,2);
	$portalpic->editPortalpicCategory($id, $category_name, $category_desc);
	showmessage($ops['editPortalpicCategorySuccess'], '?action=portalpic&do=category');
}
/*------------------------------------------------------ */
//-- 视频分类删除
/*------------------------------------------------------ */
elseif($do == 'category_del'){
	// 检测权限
	_check_rights(21,2);
	$result = $portalpic->delPortalpicCategory($id);
	showmessage($ops['delPortalpicCategorySuccess'], '?action=portalpic&do=category');
}

/*------------------------------------------------------ */
//-- 视频列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/portalpic_list.html');
}

?>
