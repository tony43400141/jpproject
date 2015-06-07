<?php
	
	/**
	 * 2011-11-05 by hukai
	 */
	set_time_limit(0);
	
	defined('WLGMTOOL') or exit('Access Denied');
	
	_check_rights(9,9);
	
	require(CLASS_DIR . 'gamecat.class.php');
	$gamecat = new GameCat();
	require(CLASS_DIR . 'article.class.php');
	require(CLASS_DIR . 'createhtml.class.php');
	$article = new Article();
	$createhtml = new Createhtml();

	$baselink = '?action=createhtml';
	$do = isset($do) ? $do : 'list';
	$category_id = isset($category_id) ? $category_id : 0;
	$game_id = isset($game_id) ? $game_id : 0;
	
	if($do == 'list') {
		_check_rights(94,9);
		$tpl->assign('gamelist' , $gamecat->getGameList());
//		$tpl->assign('cat' , $article->getArticleCategoryList());
		$tpl->display($temple_folder.'/createhtml/list.html');
		
	} else {
		_check_rights(94,9);
		
		if($game_id == 0) {  // 生成平台的
			if($category_id > 0) {
				$url = PTURL . 'admin.php?action=static&do='. $do .'&category_id='. $category_id;
				$createhtml->curl_file_get_contents($url);
			} else {
				$url = PTURL . 'admin.php?action=static&do=all';
				$createhtml->curl_file_get_contents($url);
			}
		}elseif($game_id > 0) {  // 生成选择的游戏静态页
			
			
			if(file_exists(CACHE_DIR . 'game_'. $game_id .'_config.php')) {
				// 生取出选择游戏的全局变量
				$gameconfig = require(CACHE_DIR . 'game_'. $game_id .'_config.php');
				
				if($gameconfig['game_html'] <> 1) {  // 游戏不允许生成静态文件
					showmessage($ops['GameNotOpen'] , $baselink);
				}
				
				if(!$gameconfig['game_url']) {
					showmessage($ops['GameNotWebUrl'] , $baselink);
				}
				if($category_id > 0) {
					if($do == 'create') {
						$createhtml->curl_file_get_contents($gameconfig['game_url'] . 'index.php?action=index_createindex');  //首页
					} elseif($do == 'createlist') {
						$createhtml->curl_file_get_contents($gameconfig['game_url'] . 'index.php?action=index_createhtml&type=list&category_id=' . $category_id);  //详细页
					} elseif($do == 'createdetail') {
	        			$createhtml->curl_file_get_contents($gameconfig['game_url'] . 'index.php?action=index_createhtml&type=detail&category_id=' . $category_id);  //详细页
					}
				} else {
					$createhtml->curl_file_get_contents($gameconfig['game_url'] . 'index.php?action=index_createall');  //生成所有
				}
				
			} else {
				showmessage($ops['NoGameFile'] , $baselink);
			}
			
		}
		showmessage($ops['CreateHtmlSuccess'] , $baselink);
	}
	