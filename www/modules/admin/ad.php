<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	游戏管理
 *
****/
_check_define();

// 检测权限
_check_rights(9,9);


require(CLASS_DIR . 'ad.class.php');
$ad = new AD();

if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 游戏列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(9003,9);
	$baselink = '?action=ad&do=list';
	$keyword = isset($keyword) ? $keyword : '';
	$c_id = isset($c_id) ? $c_id : 0;
	$baselink.="&keyword=".$keyword."&c_id=".$c_id;
	if(!isset($order_name)){ $order_name='add_time'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $ad->getAdList($baselink,$order_name,$order_by,$keyword,$c_id);
	$cat = $ad->getCList();
	$tpl->assign('cat' , $cat['result']);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->assign('c_id',$c_id);
	$tpl->assign('keyword',$keyword);
	$tpl->display($temple_folder.'/ad/list.html');
}
/*------------------------------------------------------ */
//-- 游戏添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(9004,9);
	$ad_open_time = date('Y-m-d');
	if($type == 'edit'){
		$result = $ad->getAdListById($id);
		$ad_open_time = $result[0]['ad_open_time'];
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	$cat = $ad->getCList();
	$tpl->assign('ad_open_time',$ad_open_time);
	$tpl->assign('cat' , $cat['result']);
	$tpl->display($temple_folder.'/ad/add.html');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(9004,9);
	require(CLASS_DIR . 'bigupload.class.php');
	
	$ad_pic = '';
	if(!empty($_FILES['ad_pic']['name'])){
	
		$filepath = 'upfiles/ad/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'ad_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$ad_pic = $upload->newfilename;
	}
	$post_array = array(
					'ad_title' => $ad_title, 
					'ad_pic' =>$ad_pic, 
					'c_id' =>$c_id,
					'ad_web' =>$ad_web,
					'add_time' =>time(),
					'ad_open' => $ad_open,
					'update_time' =>time(),
					'ad_desc' =>$ad_desc,
					'ad_open_time'=>$ad_open_time,
				);
	$ad->addAd($post_array,'table');
	showmessage('添加成功！！', '?action=ad&do=list');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(9005,9);
	require(CLASS_DIR . 'bigupload.class.php');
	$ad_pic = '';
	if(!empty($_FILES['ad_pic']['name'])){
		$filepath = 'upfiles/ad/';
		
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'ad_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$ad_pic = $upload->newfilename;
	}
	$post_array = array(
					'ad_title' => $ad_title, 
					'ad_web' =>$ad_web,
					'c_id' =>$c_id,
					'ad_open' => $ad_open,
					'update_time' =>time(),
					'ad_desc' =>$ad_desc,
					'ad_open_time'=>$ad_open_time,
				);
	if(!empty($ad_pic))
	{
		$post_array['ad_pic'] = $ad_pic;
	}
	$ad->updateAd($post_array,'table','ad_id='.$id);
	showmessage('修改成功', '?action=ad&do=list');
}
/*------------------------------------------------------ */
//-- 游戏删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(9005,9);
	$result = $ad->del($id);
	echo true;
}

elseif($do == 'clist'){
	// 检测权限
	_check_rights(9001,9);
	$baselink = '?action=ad&do=clist';
	if(!isset($order_name)){ $order_name=''; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $ad->getCList($baselink,$order_name,$order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/ad/c_list.html');
}
/*------------------------------------------------------ */
//-- 游戏分类添加 编辑
/*------------------------------------------------------ */
elseif($do == 'c_add'){
	// 检测权限
	_check_rights(9002,9);
	if($type == 'edit'){
		$result = $ad->getCListById($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	$tpl->display($temple_folder.'/ad/c_add.html');
}
/*------------------------------------------------------ */
//-- 游戏分类保存
/*------------------------------------------------------ */
elseif($do == 'c_insert'){
	// 检测权限
	_check_rights(9002,9);
	$post_array = array(
					'c_name' =>$c_name, 
					'c_desc' =>addslashes($c_desc), 
				);
	$ad->addAd($post_array,'ctable');
	showmessage('添加成功', '?action=ad&do=clist');
}
/*------------------------------------------------------ */
//-- 游戏分类列表保存
/*------------------------------------------------------ */
elseif($do == 'c_update'){
	// 检测权限
	_check_rights(9002,9);
	$post_array = array(
					'c_name' =>$c_name, 
					'c_desc' =>addslashes($c_desc), 
				);
	$ad->updateAd($post_array,'ctable','c_id='.$id);
	showmessage('修改成功', '?action=ad&do=clist');
}
/*------------------------------------------------------ */
//-- 游戏分类删除
/*------------------------------------------------------ */
elseif($do == 'c_del'){
	// 检测权限
	_check_rights(9002,9);
	$result = $ad->cdel($id);
}
/*------------------------------------------------------ */
//-- 游戏分类列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamecat_list.html');
}
?>
