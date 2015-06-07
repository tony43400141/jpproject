<?php
		
		if(!file_exists(CACHE_DIR. "sys_config.php")) {  // 系统缓存不存在时，生创建
			require(CLASS_DIR . 'cache.class.php');
			$cache = new File_Cache();
			$cache->create_sys_file();
		}
		
		$_global = include_once(CACHE_DIR. "sys_config.php");
		
		$url = strstr($_SERVER["PHP_SELF"],"admin.php");
		$tpl->assign('_global', $_global);
		if($_global['is_server'] == 0 &&  $url != 'admin.php'){
			include_once(INC_DIR . "function.inc.php");
			$tpl = init_tpl();  //创建 smarty 模板对象
			$tpl->display('index/404.html');
			exit();
		}
		
?>