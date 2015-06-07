<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	作品管理
 *
****/
_check_define();
// 检测权限
_check_rights(3,3);

require(CLASS_DIR . 'gamea.class.php');
$gamea = new Gamea();

if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 作品列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(3001,3);
	$is_m = isset($is_m) ? $is_m : -1;
	$is_display = isset($is_display) ? $is_display : -1;
	$keyword = isset($keyword) ? $keyword : '';
	$baselink = '?action=gamea&do=list';
	if(!isset($order_name)){ $order_name='ga_id'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$baselink.="&keyword=".$keyword."&is_display=".$is_display."&is_m=".$is_m;
	$result = $gamea->getGameaList($baselink,$order_name,$order_by,$keyword,$is_m,$is_display);
	$tpl->assign('keyword', $keyword);
	$tpl->assign('is_m', $is_m);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamea/list.html');
}
/*------------------------------------------------------ */
//-- 作品添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(3001,3);
	$type = empty($type) ? '' : intval($type);
	$tpl->assign('ga_content' ,	ued('','content'));
	if($type == 'edit'){
		$result = $gamea->getGameaResult($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
		$tpl->assign('ga_content' ,ued(stripslashes($result[0]['ga_content']),'content'));
	}

	$tpl->display($temple_folder.'/gamea/add.html');
}
/*------------------------------------------------------ */
//-- 作品保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(3001,3);
	require(CLASS_DIR . 'bigupload.class.php');
	$pic = '';
	if(!empty($_FILES['img']['name'])){
		$filepath = 'upfiles/gamea/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'img');
		$type = $upload->image($width,$height,0,$_FILES);
		$pic = $upload->newfilename;
	}
	$post_array = array(
					'ga_title' => trim($title), 
					'ga_content' => 0, 
					'ga_sum'=>trim($ga_sum),
					'ga_pic' => $pic, 
					'is_order' => intval($is_order),
					'is_m' => intval($is_m),
					'is_display' => 1,
					'ga_url'=>trim($g_url),
					'add_time'=>time(),
					'update_time'=>time(),
				);
	$gamea->addGamea($post_array);
	showmessage($ops['opsuccess'], '?action=gamea&do=list');
}
/*------------------------------------------------------ */
//-- 作品保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(3003,3);
	require(CLASS_DIR . 'bigupload.class.php');
	$pic = '';
	if(!empty($_FILES['img']['name'])){
		$filepath = 'upfiles/gamea/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'img');
		$type = $upload->image($width,$height,0,$_FILES);
		$pic = $upload->newfilename;
	}
	$post_array = array(
					'ga_title' => trim($title), 
					'ga_content' => 0,
					'ga_sum'=>trim($ga_sum),
					'is_order' => intval($is_order),
					'is_m' => intval($is_m),
					'is_display' => 1,
					'ga_url'=>trim($g_url),
					'update_time'=>time(),
				);
	if(!empty($pic))
	{
		$post_array['ga_pic'] = $pic;
	}
	$gamea->updateGamea($post_array,'','ga_id='.$id);
	
	showmessage('修改成功', '?action=gamea&do=list');
}
/*------------------------------------------------------ */
//-- 作品删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(3003,3);
	$result = $gamea->delGamea($id);
	showmessage('删除成功', '?action=gamea&do=list');
}
/*------------------------------------------------------ */
//-- 作品列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamea/list.html');
}

?>
