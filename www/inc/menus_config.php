<?php
if(!defined('IN_PT')) exit('Access Denied');
	/**
	 * 左侧目录数组
	 */
	$menus = 
	array( 
		'1' => array(
					array(
					'mainmenu'=>'管理员管理', 'rights' => 1, 'submenu' => array(
						array('name'=> '添加', 'url' => '?action=adminmanage&do=add', 'rights' => 1),
						array('name' => '管理', 'url' => '?action=adminmanage', 'rights' => 1),
						array('name' => '分组管理', 'url' => '?action=groupmanage&do=list', 'rights' => 11),
						array('name' => '添加分组', 'url' => '?action=groupmanage&do=add', 'rights' => 11),
						array('name' => '管理员操作记录', 'url' => '?action=managelog', 'rights' => 12),
						
						/* array('name' => '清除缓存', 'url' => '?action=clearcache', 'rights' => 13),
						array('name' => '系统设置', 'url' => '?action=sys_config', 'rights' => 14), */
						)
					),
				),
			
			'6' => array(
					array(
							'mainmenu'=>'团队管理', 'rights' => 6, 'submenu' => array(
									array('name' => '团队列表', 'url' => '?action=man&do=list', 'rights' => 6001),
									array('name' => '添加人员', 'url' => '?action=man&do=add', 'rights' => 6002),
							)
					),
			),
			
		'3' => array(
					array(
							'mainmenu'=>'作品管理', 'rights' => 3, 'submenu' => array(
							array('name' => '作品列表', 'url' => '?action=gamea&do=list', 'rights' => 3001),
							array('name' => '添加作品', 'url' => '?action=gamea&do=add', 'rights' => 3002),
							)
					),
			),
		'2' => array(
				array(
						'mainmenu'=>'新闻管理', 'rights' => 2, 'submenu' => array(
						array('name' => '新闻列表', 'url' => '?action=news&do=list', 'rights' => 2001),
						array('name' => '添加新闻', 'url' => '?action=news&do=add', 'rights' => 2002),
						)
				),
		),
		'4' => array(
				array(
						'mainmenu'=>'信息管理', 'rights' => 4, 'submenu' => array(
								array('name' => '信息列表', 'url' => '?action=info&do=list', 'rights' => 4001),
								array('name' => '添加信息', 'url' => '?action=info&do=add', 'rights' => 4002),
						)
				),
		),
		'5' => array(
				array(
						'mainmenu'=>'咨询管理', 'rights' => 5, 'submenu' => array(
								array('name' => '咨询列表', 'url' => '?action=adv&do=list', 'rights' => 5001),
						)
				),
		),
		/* '4' => array(
					array(
					'mainmenu'=>'文章管理', 'rights' => 4, 'submenu' => array(
						array('name' => '文章(资讯)', 'url' => '?action=article&do=list', 'rights' => 41),
						array('name' => '文章分类', 'url' => '?action=article&do=category', 'rights' => 42),
						array('name' => '游戏关联分类', 'url' => '?action=category_to_gamecat&do=list', 'rights' => 43),
						)
					),
				), */
		/* '5' => array(
					array(
					'mainmenu'=>'图库管理', 'rights' => 5, 'submenu' => array(
						array('name' => '图库', 'url' => '?action=portalpic&do=list', 'rights' => 51),
						array('name' => '图库分类', 'url' => '?action=portalpic&do=category', 'rights' => 52),
						)
					),
				), */

	
	
		
		
			'9' => array(
					array(
							'mainmenu'=>'广告管理', 'rights' => 9, 'submenu' => array(
						/* 	array('name' => '广告分类', 'url' => '?action=ad&do=clist', 'rights' => 9001),
							array('name' => '添加分类', 'url' => '?action=ad&do=c_add', 'rights' => 9002), */
							array('name' => '广告列表', 'url' => '?action=ad&do=list', 'rights' => 9003),
							array('name' => '添加广告', 'url' => '?action=ad&do=add', 'rights' => 9004),
							)
					),
			),
		
		
		'15' => array(
					array(
					'mainmenu'=>'密码修改', 'rights' => 15, 'submenu' => array(
						array('name' => '密码修改', 'url' => '?action=editpsd', 'rights' => 151),
						)
					),
				),		
									
	);
		
?>