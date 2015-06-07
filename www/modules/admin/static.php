<?php

/**
 * 2011-11-03 by hukai
 */
	set_time_limit(0);
	
	defined('WLGMTOOL') or exit('Access Denied');
	
	// 检测权限
//	_check_rights(9,9);
	
	require(CLASS_DIR . 'createhtml.class.php');
	require(CLASS_DIR . 'article.class.php');
	
	$createhtml = new Createhtml();
	$baselink = '?action=main';
	$do = isset($do) ? $do : '';
	$category_id = isset($category_id) ? $category_id : 0;
	$id = isset($id) ? $id : 0;
	
	
	switch ($do) {
		
		case 'create' :  //  生成首页
			$createhtml->create_index();
			break;
		case 'createlist' :  //生成所有新闻和公告静态页
			$createhtml->create_news($category_id);
			$createhtml->create_news(8);
			$createhtml->create_news(10);
			break;
		case 'createdetail' :  // 生成所有内容页
			$createhtml->create_news_detail($category_id);
			break;
		case 'createone' :  //生成一个新闻页
			$createhtml->create_one_news($id , $category_id , 0);
			break;
		case 'addnews' :   //添加或修改新闻处理
			$createhtml->create_index();   //更新首页
			$createhtml->create_one_news($id , $category_id); //生成这篇新闻静态页，该类别下的列表
			$createhtml->create_news(0);                //生成所有列表
			break;
		case 'all':
			$article = new Article();
			$arr = array(7,8,9,10,11);
			foreach($arr as $v) {
				$createhtml->create_news($v);  //生成列表页
				$createhtml->create_news_detail($v); //详细页
				$createhtml->create_index(); //首页
			}
			break;
		default:
			break;
	}
	
	showmessage($ops['CreateHtmlSuccess'] , $baselink);
	