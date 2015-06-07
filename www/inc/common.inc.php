<?php
if(!defined('IN_PT')) exit('Access Denied');
include_once("inc/config.inc.php");
include_once(INC_DIR."function.inc.php");
include_once(INC_DIR."db_mysql.class.php");
include_once(INC_DIR."lib_class.php");
include_once(INC_DIR."op.inc.php");
define('PTURL' , getYMD(0));    //访问的地址

@extract($_REQUEST);

$smarty_file = 'libs/smarty/Smarty.class.php';
$tpl = init_tpl();  //创建 smarty 模板对象
//ini_set('session.cookie_domain' , '');
if(!ob_start()){
	ob_start();
}
$db = new db_mysql(DBHOST, DBUSER, DBPWD, DBNAME);

//获取客户端IP
$onlineip = get_OlineIp();

unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS);

foreach($_POST as $_key=>$_value){
	$_key=RemoveXSS($_POST[$_key]);
}
foreach($_GET as $_key=>$_value){
	$_key=RemoveXSS($_GET[$_key]);
}
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/index.php?action=login';

?>
