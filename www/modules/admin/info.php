<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	游戏管理
 *
****/
_check_define();

// 检测权限
_check_rights(4,4);


require(CLASS_DIR . 'info.class.php');
$info = new Info();

$cat = array(array('c_id'=>'zp','c_name'=>'招聘信息'),array('c_id'=>'wr','c_name'=>'网站使用条款'));
$scat = array('zp'=>'招聘信息','wr'=>'网站使用条款');
if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 游戏列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(4001,4);
	$baselink = '?action=info&do=list';
	if(!isset($order_name)){ $order_name='add_time'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $info->getInfoList($baselink,$order_name,$order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->assign('cat',$scat);
	$tpl->display($temple_folder.'/info/list.html');
}
/*------------------------------------------------------ */
//-- 游戏添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(4002,4);
	$tpl->assign('i_content' ,	ued('','content'));
	if($type == 'edit'){
		$result = $info->getInfoById($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
		$tpl->assign('i_content' ,ued(stripslashes($result[0]['i_content']),'content'));
	}
	
	$tpl->assign('cat',$cat);
	$tpl->display($temple_folder.'/info/add.html');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(4002,4);
	$post_array = array(
					'i_title' => trim($title), 
					'i_content' => $content, 
					'i_c' => $i_c,
					'add_time'=>time(),
					'update_time'=>time(),
				);
	$info->addInfo($post_array,'table');
	showmessage('添加成功！！', '?action=info&do=list');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(4003,4);
	$post_array = array(
					'i_title' => trim($title), 
					'i_content' => $content, 
					'i_c' => $i_c,
					'update_time'=>time(),
				);
	$info->updateInfo($post_array,'table','i_id='.$id);
	showmessage('修改成功', '?action=info&do=list');
}
/*------------------------------------------------------ */
//-- 游戏删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(4003,4);
	$result = $info->del($id);
	echo true;
}
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamecat_list.html');
}
?>
