<?php
if(!defined('IN_PT')) exit('Access Denied');
/****
 *
 *	咨询管理
 *
****/
_check_define();

// 检测权限
_check_rights(5,5);


require(CLASS_DIR . 'adv.class.php');
$adv = new Adv();

if(!isset($do)){ $do = 'list'; }
/*------------------------------------------------------ */
//-- 游戏列表
/*------------------------------------------------------ */
if($do == 'list'){
	// 检测权限
	_check_rights(5001,5);
	$baselink = '?action=adv&do=list';
	if(!isset($order_name)){ $order_name='add_time'; }
	if(!isset($order_by)){ $order_by='DESC'; }
	$is_contact = isset($is_contact) ? $is_contact : -1;
	$username = isset($username) ? $username : '';
	$mobile = isset($mobile) ? $mobile : '';
	$email = isset($email) ? $email : '';
	$baselink.="&username=".$username."&mobile=".$mobile."&email=".$email."&is_contact=".$is_contact;
	$arr = array('is_contact'=>$is_contact,'username'=>$username,'mobile'=>$mobile,'email'=>$email);
	$result = $adv->getAdvList($baselink,$order_name,$order_by,$arr);
	$tpl->assign('page', $page);
	$tpl->assign('order_name', $order_name);
	$tpl->assign('order_by', $order_by);
	$tpl->assign('result', $result);
	$tpl->assign('is_contact',$is_contact);
	$tpl->assign('username',$username);
	$tpl->assign('mobile',$mobile);
	$tpl->assign('email',$email);
	$tpl->display($temple_folder.'/adv/list.html');
}
elseif($do == 'update'){
	// 检测权限
	_check_rights(5002,5);
	$post_array = array(
					'update_time' =>time(),
					'is_contact' =>1,
				);
	$adv->updateAdv($post_array,'table','adv_id='.$id);
	showmessage('修改成功', '?action=adv&do=list');
}
elseif($do == 'detail'){
	// 检测权限
	_check_rights(5002,5);
	$detail = $adv->getAdvById($id);
	$tpl->assign('result', $detail);
	$tpl->display($temple_folder.'/adv/detail.html');
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
else{
	$tpl->assign('result', $result);
	$tpl->display($temple_folder.'/gamecat_list.html');
}
?>
