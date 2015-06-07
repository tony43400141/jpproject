<?php
if(!defined('IN_PT')) exit('Access Denied');
	/**
	 * project: 清除smarty缓存
	 * file:	clearcache.php
	 */

	_check_define();

	// 检测权限
	_check_rights(13);

	$dir = 'templates_c';

	if( is_dir($dir) ){
		$handle = opendir($dir);

		while (($file = readdir($handle)) !== false){
			if( $file != '.' && $file != '..' )
				unlink($dir . '/' . $file);
		}

		closedir($handle);

		showmessage($ops['opsuccess'], '');
	} else
		showmessage($ops['cacheerror'], '');
?>