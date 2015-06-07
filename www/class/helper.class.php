<?php

if(!defined('IN_PT')) exit('Access Denied');
/**
 * 生成静态页面帮助类
 * $fileArray 新闻类别对应的模板
 *
 */
	class Helper {

		function __construct() {

		}

		function __destruct() {

		}

		function curl_file_get_contents($durl){
		   global $referer;
		   $ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL, $durl);
		   curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		   curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		   curl_setopt($ch, CURLOPT_REFERER,$referer);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   $r = curl_exec($ch);
		   curl_close($ch);
		   return $r;
		 }

	}