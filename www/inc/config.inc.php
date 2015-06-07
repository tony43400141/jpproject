<?php
if(!defined('IN_PT')) exit('Access Denied');

//配置信息文件
	define('WLGMTOOL', 		TRUE);									// 配置 session前缀
	define('SESSION_NAME', 	'ptadmin');
	define('ROOT_DIR', 		substr(dirname(__FILE__), 0, -3));					// 根目录
	define('INC_DIR', 		ROOT_DIR . 'inc/');									// 配置文件目录
	define('CLASS_DIR', 	ROOT_DIR . 'class/');								// 类目录
	define('CSS_DIR', 		ROOT_DIR . 'css/');									// css文件目录
	define('JS_DIR', 		ROOT_DIR . 'js/');									// js文件目录
	define('IMAGES_DIR',	ROOT_DIR . 'images/');								// 图片文件目录
	define('UPLOADPATH',	ROOT_DIR . 'upfiles/');								// 图片文件目录
//	define('INC_DIR_TABLE', ROOT_DIR . 'inc/db_table.inc.php');					// 配置数据库表文件目录
	define('CACHE_DIR',     ROOT_DIR . 'cache/');

	define('PAGES', 		'25');												//  分页 每页显示条数
	require(INC_DIR.'db.inc.php');
	require(INC_DIR.'db_table.inc.php'); 
	

?>