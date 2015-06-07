<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	作品管理
 *
****/
_check_define();
// 检测权限
_check_rights(2,2);

require(CLASS_DIR . 'news.class.php');
$news = new news();
if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 作品列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(2001,2);
	$is_display = isset($is_display) ? $is_display : -1;
	$keyword = isset($keyword) ? $keyword : '';
	$baselink = '?action=news&do=list';
	if(!isset($order_name)){ $order_name='n_id'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$baselink.="&keyword=".$keyword."&is_display=".$is_display;
	$result = $news->getnewsList($baselink,$order_name,$order_by,$keyword,$is_display);
	
	$tpl->assign('keyword', $keyword);
	$tpl->assign('is_display', $is_display);
	
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/news/list.html');
}
/*------------------------------------------------------ */
//-- 作品添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(2001,2);
	$type = empty($type) ? '' : intval($type);
	$tpl->assign('n_content' ,	ued('','content'));
	if($type == 'edit'){
		$result = $news->getNewsResult($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
		$tpl->assign('n_content' ,ued(stripslashes($result[0]['n_content']),'content'));
	}

	$tpl->display($temple_folder.'/news/add.html');
}
/*------------------------------------------------------ */
//-- 作品保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(2001,2);
	require(CLASS_DIR . 'bigupload.class.php');
	$pic = '';
	if(!empty($_FILES['img']['name'])){
		$filepath = 'upfiles/news/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$width = 142;
		$height = 126;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'img');
		$type = $upload->image($width,$height,0,$_FILES);
		$pic = $upload->newfilename;
	}
	$post_array = array(
					'n_title' => trim($title), 
					'n_content' => $content, 
					'n_pic' => $pic,
					'n_sum'=>trim($n_sum), 
					'is_order' => intval($uis_order),
					'is_display' => intval($is_display),
					'add_time'=>time(),
					'update_time'=>time(),
				);
	$news->addnews($post_array);
	showmessage($ops['opsuccess'], '?action=news&do=list');
}
/*------------------------------------------------------ */
//-- 作品保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(2002,2);
	require(CLASS_DIR . 'bigupload.class.php');
	$pic = '';
	if(!empty($_FILES['img']['name'])){
		$filepath = 'upfiles/news/';
		if(is_dir($filepath) == false){
			mkdir($filepath,0777);
		}
		$width = 142;
		$height = 126;
		$upload = new resizeimage();
		$upload->destination_folder = $filepath;
		$upload->uploadImage($_FILES,'img');
		$type = $upload->image($width,$height,0,$_FILES);
		$pic = $upload->newfilename;
	}
	$post_array = array(
					'n_title' => trim($title), 
					'n_content' => $content,
					'n_sum'=>trim($n_sum),
					'is_order' => intval($is_order),
					'is_display' => intval($is_display),
					'update_time'=>time(),
				);
	if(!empty($pic))
	{
		$post_array['n_pic'] = $pic;
	}
	$news->updatenews($post_array,'','n_id='.$id);
	
	showmessage('修改成功', '?action=news&do=list');
}
/*------------------------------------------------------ */
//-- 作品删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(2002,2);
	$result = $news->delnews($id);
	showmessage('删除成功', '?action=news&do=list');
}
/*------------------------------------------------------ */
//-- 作品列表
/*------------------------------------------------------ */
else{
	$tpl->assign('result', $result);

	$tpl->display($temple_folder.'/news/list.html');
}

?>
