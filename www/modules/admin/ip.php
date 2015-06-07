<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	游戏管理
 *
****/
_check_define();

// 检测权限
_check_rights(13,13);


require(CLASS_DIR . 'ip.class.php');
$ip = new Ip();

if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 游戏列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(131,13);
	$baselink = '?action=ip&do=list';
	if(!isset($order_name)){ $order_name='ip_id'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$result = $ip->getIpList($baselink, $order_name, $order_by);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/ip/list.html');
}
/*------------------------------------------------------ */
//-- 游戏添加 编辑
/*------------------------------------------------------ */
elseif($do == 'add'){
	// 检测权限
	_check_rights(132,13);
	if($type == 'edit'){
		$result = $ip->getIpById($id);
		$tpl->assign('result', $result);
		$tpl->assign('type', $type);
		$tpl->assign('id', $id);
	}
	$tpl->display($temple_folder.'/ip/add.html');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'insert'){
	// 检测权限
	_check_rights(132,13);
	$post_array = array(
					'ip1' =>trim($ip1), 
					'ip2' =>trim($ip2), 
					'ip3' =>trim($ip3),
					'ip4' =>trim($ip4),
					'isopen' =>$isopen,
				);
	$ip->addIp($post_array,'table');
	file_get_contents('http://www.1uc.com.cn/index/setipforbid');
	showmessage('添加成功！！', '?action=ip&do=list');
}
/*------------------------------------------------------ */
//-- 游戏保存
/*------------------------------------------------------ */
elseif($do == 'update'){
	// 检测权限
	_check_rights(132,13);
	$post_array = array(
					'ip1' =>trim($ip1), 
					'ip2' =>trim($ip2), 
					'ip3' =>trim($ip3),
					'ip4' =>trim($ip4),
					'isopen' =>$isopen,
				);
	$ip->updateIp($post_array,'table','ip_id='.$id);
	file_get_contents('http://www.1uc.com.cn/index/setipforbid');
	showmessage('修改成功', '?action=ip&do=list');
}
/*------------------------------------------------------ */
//-- 游戏删除
/*------------------------------------------------------ */
elseif($do == 'del'){
	// 检测权限
	_check_rights(132,13);
	$result = $ip->del($id);
	file_get_contents('http://www.1uc.com.cn/index/setipforbid');
	echo true;
}
?>
