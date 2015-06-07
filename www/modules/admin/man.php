<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	游戏管理
 *
****/
_check_define();

// 检测权限
_check_rights(6,6);


require(CLASS_DIR . 'man.class.php');
$man = new Man();

if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 游戏列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(6001,6);
	$baselink = '?action=ad&do=list';
	$keyword = isset($keyword) ? $keyword : '';
	$baselink.="&keyword=".$keyword;
	if(!isset($order_name)){ $order_name='a.add_time'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $man->getManList($baselink,$order_name,$order_by,$keyword,$c_id);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->assign('keyword',$keyword);
	$tpl->display($temple_folder.'/man/list.html');
}
/*------------------------------------------------------ */
//-- 游戏添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(6002,6);
	$type = empty($type) ? '' : intval($type);
	$tpl->assign('m_desc' ,	ued('','content'));
	if($type == 'edit'){
		$result = $man->getManListById($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
		$tpl->assign('m_desc' ,ued(stripslashes($result[0]['m_desc']),'content'));
	}
	$tpl->display($temple_folder.'/man/add.html');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(6002,6);
	require(CLASS_DIR . 'bigupload.class.php');
	
	$m_pic = '';
	if(!empty($_FILES['m_pic']['name'])){
	
		$filepath = 'upfiles/ad/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'m_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$m_pic = $upload->newfilename;
	}
	$post_array = array(
					'm_title' => trim($m_title), 
					'm_pic' =>$m_pic, 
					'm_order' =>intval($m_order),
					'm_desc' =>trim($content),
					'add_time' =>time(),
					'update_time' =>time(),
				);
	$man->addMan($post_array,'table');
	showmessage('添加成功！！', '?action=man&do=list');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(6003,6);
	require(CLASS_DIR . 'bigupload.class.php');
	$m_pic = '';
	if(!empty($_FILES['m_pic']['name'])){
		$filepath = 'upfiles/ad/';
		
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'m_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$m_pic = $upload->newfilename;
	}
	$post_array = array(
					'm_title' => trim($m_title), 
					'm_order' =>intval($m_order),
					'm_desc' =>trim($content),
					'update_time' =>time(),
				);
	if(!empty($m_pic))
	{
		$post_array['m_pic'] = $m_pic;
	}
	$man->updateMan($post_array,'table','m_id='.$id);
	showmessage('修改成功', '?action=man&do=list');
}
/*------------------------------------------------------ */
//-- 游戏删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(6003,6);
	$result = $man->del($id);
	echo true;
}
/*------------------------------------------------------ */
//-- 游戏分类列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamecat_list.html');
}
?>
