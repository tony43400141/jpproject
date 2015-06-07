<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	视频管理
 *
****/
_check_define();

// 检测权限
_check_rights(7,7);

require(CLASS_DIR . 'gamecat.class.php');
$gamecat = new GameCat();
$gamecat->table = TABLE_NEWSCAT;
if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 视频列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(72,7);
	$baselink = '?action=gamecat&do=list';
	if(!isset($order_name)){ $order_name=''; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $gamecat->get_Gamecat($baselink,$order_name,$order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/news/newscat.html');
}
/*------------------------------------------------------ */
//-- 视频添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(72,7);
	$type = empty($type) ? '' : intval($type);
	$id = isset($id) ? $id : 0;
	$pid = isset($pid) ? $pid : 0;
	if($type == 'edit'){
		$result = $gamecat->get_OneGamecat($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	$tpl->assign('table_arr' , selectTree($pid,$id,$gamecat->table));
	$tpl->display($temple_folder.'/news/cat_add.html');
}
/*------------------------------------------------------ */
//-- 视频保存
/*------------------------------------------------------ */
elseif($do == 'cat_insert'){
	// 检测权限
	_check_rights(72,7);
	
	$game_pic = '';
	if(!empty($_FILES['game_pic']['name'])){
		require(CLASS_DIR . 'bigupload.class.php');
		$filepath = 'upfiles/gamecat/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'game_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$game_pic = $upload->newfilename;
	}
	
	$game_havechild = 0;
	$game_path = 0;
	$game_num = 0;
	$game_havefather = 0;
	
	$sql = get_cname($gamecat->table);
	eval("\$sql=\"$sql\";");
	$gamecat->add_Gamecat($sql , $game_pid);
	showmessage($ops['insertGameSuccess'], '?action=news&do=list');
}
/*------------------------------------------------------ */
//-- 视频保存
/*------------------------------------------------------ */
elseif($do == 'cat_update'){
	// 检测权限
	_check_rights(72,7);
	$game_pic = '';
	if(!empty($_FILES['game_pic']['name'])){
		require(CLASS_DIR . 'bigupload.class.php');
		$filepath = 'upfiles/gamecat/';
		
		$width = 142;
		$height = 136;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'game_pic');
		$type = $upload->image($width,$height,0,$_FILES);
		$game_pic = $upload->newfilename;
	}
	$post_array = array(
					'game_name' => $game_name, 
					'game_pic' => $game_pic, 
					'game_title' => $game_title,
					'game_keyword' => $game_keyword, 
					'game_desc' => $game_desc,
					'game_pid' => $game_pid,
					'game_open' => $game_open,
					'game_url'  => $game_url,
				);
	$gamecat->editGamecat($post_array, $id);
	showmessage($ops['editGamecatSuccess'], '?action=news&do=list');
}
/*------------------------------------------------------ */
//-- 游戏删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(72,7);
	$result = $gamecat->delGamecat($id);
	if($result)
		showmessage($ops['delGamecatSuccess'], '?action=news&do=list');
	else {
		showmessage($ops['delGamecatError'], '?action=news&do=list');
		exit;
	}
}
/*------------------------------------------------------ */
//-- 视频列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamecat_list.html');
}

?>
