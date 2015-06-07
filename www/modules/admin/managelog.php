<?php
if(!defined('IN_PT')) exit('Access Denied');
	_check_define();

	// 检测权限
	_check_rights(12);

	// 获取数据
	$sql 		= 'SELECT COUNT(*) FROM ' . TABLE_LOG;
	$sql2 		= 'SELECT * FROM ' . TABLE_LOG . ' ORDER BY log_id DESC';
	$baselink 	= '?action=managelog';

	$tpl->assign('result', _get_results($sql, $sql2, $baselink));

	$tpl->display($temple_folder.'/managelog.html');
?>
