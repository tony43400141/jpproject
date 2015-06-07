<?php
return array(
	'DEFAULT_MODULE'     => 'Index', //默认模块
	'URL_MODEL'          => '2', //URL模式
	//'APP_GROUP_LIST' => 'Game', //项目分组设定
	//'DEFAULT_GROUP'  => 'Game', //默认分组
	'SESSION_AUTO_START' => true, //是否开启session
	'VAR_PAGE'          =>'p',
	'URL_CASE_INSENSITIVE' =>true,  //路由不区分大小写
	'TMPL_L_DELIM'=>'<{',
	'TMPL_R_DELIM'=>'}>',
	'DEFAULT_CHARSET'=>'UTF-8',
	'TMPL_PARSE_STRING'  =>array(
	'__PUBLIC__' => '/Common', // 更改默认的/Public 替换规则
	'__JS__' => '/Public/JS/', // 增加新的JS类库路径替换规则
	'__IMG__'=>'/Public/IMG/',
	'__CSS__'=>'/Public/CSS/',
	'__WEBN__'=>'pierrot',
	),
	'TMPL_ACTION_ERROR'=>'./Tpl/Public/error.html',
	'ERROR_PAGE'=>'./Tpl/Public/page_error.html',
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'jppdb', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '111111', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀
);
?>