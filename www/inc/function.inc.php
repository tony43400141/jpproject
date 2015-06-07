<?php
if(!defined('IN_PT')) exit('Access Denied');
	/**
	 * 检测WLGMTOOL是否定义
	 */
	function _check_define(){
		defined('WLGMTOOL') or exit('Access Denied');
	}

	_check_define();

	function showmessage($msg, $url_forward = 'goback', $ms=3000)
	{
		include 'message.php';
		exit;
	}
	function strip_sql($string)
	{
		global $search_arr,$replace_arr;
		return is_array($string) ? array_map('strip_sql', $string) : preg_replace($search_arr, $replace_arr, $string);
	}
	function new_addslashes($string)
    {
      if(!is_array($string)) return addslashes($string);
      foreach($string as $key => $val) $string[$key] = new_addslashes($val);
      return $string;
    }
	function new_htmlspecialchars($string)
	{
		return is_array($string) ? array_map('new_htmlspecialchars', $string) : htmlspecialchars($string,ENT_QUOTES);
	}
    function & init_tpl() {

    	$root_dir = ROOT_DIR;

		if(isset($GLOBALS['tpl']))
			return $GLOBALS['tpl'];

		require_once $root_dir . 'libs/smarty/Smarty.class.php';
		$tpl = new Smarty();

		$tpl->template_dir 		= $root_dir . "templates";
		$tpl->compile_dir 		= $root_dir . "templates_c";
		$tpl->config_dir 		= $root_dir . "configs";
		$tpl->cache_dir 		= $root_dir . "cache";
		$tpl->compile_id 		= SMARTY_CID;
		$tpl->left_delimiter 	= '<{';
		$tpl->right_delimiter	= '}>';

		return $tpl;
	}
	/**
	 * 获取当前页面的地址
	 *
	 * @return string url
	 */
	function getYMD($type = 1) {
		$self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
		$querystring = $_SERVER['QUERY_STRING'];
		$domain = $_SERVER['HTTP_HOST'];
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$scheme = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$port = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
		if($type == 1)
			$url = $scheme.$domain.$port.$self.($querystring ? '?'.$querystring : '');
		else {
			$url = $scheme.$domain.$port;
			$s = explode('/' , $self);
			array_pop($s);
			$url .= join('/' , $s) . '/';
		}
			
		return $url;
	}

function dir_create($path, $mode = 777)
{
    global $PHPCMS, $ftp;
	if(is_dir($path)) return TRUE;
    if($PHPCMS['enableftp'] && !is_object($ftp)) require_once PHPCMS_ROOT.'/include/ftp.inc.php';
	$dir = str_replace(__SITE_ROOT, '', $path);
	$dir = dir_path($dir);
    $temp = explode('/', $dir);
    $cur_dir = __SITE_ROOT;
	$max = count($temp) - 1;
    for($i=0; $i<$max; $i++)
    {
        $cur_dir .= $temp[$i].'/';
        if(is_dir($cur_dir)) continue;
		if($PHPCMS['enableftp'])
		{
		    $ftp->mkdir($cur_dir);
		}
		else
		{
		    mkdir($cur_dir);
			@chmod($cur_dir, 0777);
		}
    }
	return $PHPCMS['enableftp'] ? TRUE : is_dir($path);
}
function dir_path($dirpath)
{
	$dirpath = str_replace('\\', '/', $dirpath);
	if(substr($dirpath, -1) != '/') $dirpath = $dirpath.'/';
	return $dirpath;
}

function str_cut($string, $length, $dot = ' ...')
{
	global $CONFIG;
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), array(' ', '&', '"', "'", '<', '>'), $string);
	$strcut = '';
	$tt = 'utf-8';
	if($tt == 'utf-8')
	{
		$n = $tn = $noc = 0;
		while($n < $strlen)
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$strcut = substr($string, 0, $n);
	}
	else
	{
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		for($i = 0; $i < $maxi; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}
function get_substr($string, $start=0, $length='')
{
	global $CONFIG;
	if(!$string) return false;
	$start = (int)$start;
	$length = (int)$length;
	$i = 0;
	$step = strtolower($CONFIG['dbcharset']) == 'utf8' ? 3 : 2;
	$strlen = strlen($string);
	if($start>=0)
	{
		while($i<$start)
		{
			if(ord($string[$i])>127)
			{
				$i = $i+$step;
			}
			else
			{
				$i++;
			}
		}
		$start = $i;
		if($length=='')
		{
			return substr($string,$start);
		}
		elseif($length>0)
		{
			$end = $start+$length;
			while($i<$end && $i<$strlen)
			{
				if(ord($string[$i])>127)
				{
					$i = $i+$step;
				}
				else
				{
					$i++;
				}
			}
			if($end != $i-1)
			{
				$end = $i;
			}
			else
			{
				$end--;
			}
			$length = $end-$start;
			return substr($string,$start,$length);
		}
		elseif($length==0)
		{
			return '';
		}
		else
		{
			$length = $strlen-abs($length)-$start;
			return get_substr($string,$start,$length);
		}
	}
	else
	{
		$start = $strlen-abs($start);
		return get_substr($string,$start,$length);
	}
}

	function getSmallpic($url) {
		$u = split("\.",$url);
		$url = $u[0] . "_small." . $u[1] ;
		return $url;
	}

	function array_remove_empty(& $arr, $trim = true){

	    foreach ($arr as $key => $value) {
	        if (is_array($value)) {
	            array_remove_empty($arr[$key]);
	        } else {
	            $value = trim($value);
	            if ($value == '') {
	                unset($arr[$key]);
	            } elseif ($trim) {
	                $arr[$key] = $value;
	            }
	        }
	    }
	    return $arr;
	}

	/**
	 * 检测权限
	 */
	function _check_rights($right, $game = 1){
		global $ops;
		if(isset($_COOKIE['admin_SESSIONID'])){
			$SESSIONID = $_COOKIE['admin_SESSIONID'];
			if( !isset($_SESSION[$SESSIONID][SESSION_NAME.'_adminrights'][$game][$right]) )
				showmessage($ops['norights'], '');
			elseif( !$_SESSION[$SESSIONID][SESSION_NAME.'_adminrights'][$game][$right] )
				showmessage($ops['norights'], '');
		}else{
			if( !isset($_SESSION[SESSION_NAME.'_adminrights'][$game][$right]) )
				showmessage($ops['norights'], '');
			elseif( !$_SESSION[SESSION_NAME.'_adminrights'][$game][$right] )
				showmessage($ops['norights'], '');
		}
	}

	/**
	 * log记录
	 *
	 * @param string $msg 操作内容
	 */
	function log_record($content){
		$game2121db = &init_db();
		if(isset($_COOKIE['admin_SESSIONID'])){
			$SESSIONID = $_COOKIE['admin_SESSIONID'];
		}
		// 记录
		$sql  = 'INSERT INTO ' . TABLE_LOG . ' (log_user, log_time, log_ip, log_content) VALUES(' . "'" . $SESSIONID . "', ";
		$sql .= time() . ', ' . ip2long($_SERVER['REMOTE_ADDR']) . ', ' . "'" . $content . "')";
		$game2121db->query($sql);
	}

	/**
	 * 获取分页数据
	 *
	 * @param string $sql		查询sql获取总记录数
	 * @param string $sql2		查询sql2获取数据
	 * @param string $baselink	页面链接
	 * @param int $pagesize		每页显示记录数，默认显示15条
	 * @param int $pagestyle	显示样式，默认为4，可用值（1,2,3,4,5）
	 * @return array 返回数据，total=>总记录数，pages=>页面显示，result=>查询数据集
	 */
	function _get_results($sql, $sql2, $baselink, $pagesize=15, $pagestyle=4){
		global $db, $page, $pagesize;

		// 获取总记录数
		$total = $db->get_value($sql);

		// 处理page
		$page = isset($page) && $page > 1 ? intval($page) : 1;

		// 分页
		require_once(CLASS_DIR . 'page.class.php');
		$page_show = new mypage(array('total' => $total, 'nowindex' => $page, 'perpage' => $pagesize, 'url' => $baselink));
		$pages = $page_show->show($pagestyle);

		// 获取数据
		$sql2 	.= ' LIMIT ' . $page_show->offset . ', ' . $pagesize;
		$query 	= $db->query($sql2);
		$data	= $db->array_num($query);

		// 释放结果
		$db->free_result($query);

		$result['total'] 	= $total;
		$result['pages'] 	= $pages;
		$result['result'] 	= $data;

		return $result;
	}

	/**
	 * 获得link的查询参数
	 *
	 * @param array $vars 参数数组
	 * @return string $link_var 参数字符串
	 */
	function _get_link_var($vars){
		$link_var = '';
		foreach ($vars as $key => $value)
			$link_var .= '&' . $key . '=' . $value;

		return $link_var;
	}

	/**
	 * 角色注册来源
	 */
	function _get_role_from(){
		return array(0 => 'vali', 1 => '开心vali', 2 => '天火', 3 => '超兽vali', 4 => '漫界', 5 => '凤凰');
	}


	/**
	 * 将管理员的权限写到模板文件里
	 */
	function _admin_rights_tpl(){
		global $tpl;

		$tpl->assign('admin_rights', $_SESSION['vali_adminrights']);
	}

	/**
	 * 新分页函数
	 *
	 * @param string $sql 查询sql获取总记录数
	 * @param string $sql2 查询sql2获取数据
	 * @param int $pagesize 每页显示记录数，默认显示15条
	 * @param string $baselink 页面链接
	 * @param object $db_query 数据库连接对象
	 * @param string $type 类型，默认为获取数据（getdata:获取数据, 空：获取查询结果对象）
	 * @param int $pagestyle 分页显示风格，默认为10
	 * @return mix 如果类型为getdata则返回数据(array)，如果类型为空或其他返回数据查询对象(object)
	 */
	function cp($sql,$sql2,$pagesize=15,$baselink,$db_query=null,$type='getdata',$pagestyle=10){
		global $page, $db;

		if( !$db_query )
			$db_query = $db;

		if( !$pagestyle )
			$pagestyle = 10;

		$counts = $db_query->get_value($sql);
		$page = isset($page) ? (int)$page : 1;

		require_once('inc/lib_class.php');
		$getpage = new Page($page,$counts,$pagesize,$pagestyle);
		$pages = $getpage->genHtml($baselink);
		$sql2.=" LIMIT ".$getpage->start().", ".$pagesize;
		$query = $db_query->query($sql2);

		$result['total']		= $counts;
		$result['page_string']	= $pages;
		if( $type == 'getdata' ){
			$data = $db_query->array_num($query);
			$db_query->free_result($query);

			$result['result'] = $data;
		} else
			$result['query'] = $query;

		return $result;
	}
	
	function pagecp($sql,$sql2,$pagesize,$baselink,&$pages,&$counts,$pagestyle=10){
		global $db,$page;
		$c=$db->get_one($sql);
		$counts = $c['COUNT(1)'];
		$page = isset($page)?(int)$page:1;
		require_once('class/page.class.php');
		$getpage = new Page($page,$counts,$pagesize,$pagestyle);
		$pages = $getpage->genHtml($baselink);
		$sql2.=" LIMIT ".$getpage->start().", ".$pagesize;
		$query = $db->query($sql2);
		return $query;
	}

	

	/**
	 * 获取分页数据
	 *
	 * @param string $total_page		查询sql获取总记录数
	 * @param string $sql2		查询sql2获取数据
	 * @param string $baselink	页面链接
	 * @param int $pagesize		每页显示记录数，默认显示15条
	 * @param int $pagestyle	显示样式，默认为4，可用值（1,2,3,4,5）
	 * @return array 返回数据，total=>总记录数，pages=>页面显示，result=>查询数据集
	 */
	function sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by, $pagesize=25, $pagestyle=4, $db2 = ''){
		global $db, $page;
//		 if($db2 != ''){
//			 $db = $db2;
//		 }
		// 获取总记录数
		if($db2){
			$total = $db2->get_value($total_sql);
		}else {
			$total = $db->get_value($total_sql);
		}

		// 处理page
		$page = isset($page) && $page > 1 ? intval($page) : 1;

		// 分页
		require_once(CLASS_DIR . 'page.class.php');
		// $page_show = new page($page, $total, $pagesize);
		$page_show = new page(array('total' => $total, 'nowindex' => $page, 'perpage' => $pagesize, 'url' => $baselink));
		// $pages = $page_show->show($pagestyle);
		$pages = $page_show->genHtml($baselink);
		if($order_name != '' && $order_by != ''){
			if(strpos($page_sql,'ORDER BY') > 0){
				$page_sql = Substr($page_sql,0,strpos($page_sql,'ORDER BY'));
				$page_sql .= 'ORDER BY '.$order_name.' '.$order_by.' ';
			}
		}
		// 获取数据
		$page_sql 	.= ' LIMIT ' . $page_show->offset . ', ' . $pagesize;
		if($db2) {
			$query 	= $db2->query($page_sql);
			$data	= $db2->array_num($query);
			$db2->free_result($query);
		} else {
			$query 	= $db->query($page_sql);
			$data	= $db->array_num($query);
			$db->free_result($query);
		}

		// 释放结果
		
		// page($v_page, $v_dbcount, $v_size,$back=0,$back_page='photos.php')
		 
		$result['total'] 	= $total;
		$result['pages'] 	= $pages;
		$result['order_name'] 	= $order_name;
		$result['order_by'] = $order_by;
		$result['result'] 	= $data;

		return $result;
	}
		
	function getthemonth($date)
	{
		$firstday = date('Y-m-01', strtotime($date));
		$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
		return array($firstday, $lastday);
	} 

	function getweek($time = '')
	{
		if($time == ''){
			$time = time();
		}
		$y = date("Y",$time);
		$d = date("d",$time);
		$m = date("m",$time);
		$flag = date("w",$time);
		switch($flag)
		{
			case 0:
			$week_start = date("U",mktime(0,0,0,$m,$d-13,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-7,$y));
			break;
			case 1:
			$week_start = date("U",mktime(0,0,0,$m,$d-7,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-1,$y));
			break;
			case 2:
			$week_start = date("U",mktime(0,0,0,$m,$d-8,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-2,$y));
			break;
			case 3:
			$week_start = date("U",mktime(0,0,0,$m,$d-9,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-3,$y));
			break;
			case 4:
			$week_start = date("U",mktime(0,0,0,$m,$d-10,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-4,$y));
			break;
			case 5:
			$week_start = date("U",mktime(0,0,0,$m,$d-11,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-5,$y));
			break;
			case 6:
			$week_start = date("U",mktime(0,0,0,$m,$d-12,$y));
			$week_end = date("U",mktime(23,59,59,$m,$d-6,$y));
			break;
		}
		$week_start = date('Y-m-d', $week_start);
		$week_end = date('Y-m-d', $week_end);
		
		return array($week_start, $week_end);
	}
	
	function ued($content,$tname='content',$w='700',$h='400'){
		$html = '<script id="'.$tname.'" name="'.$tname.'" type="text/plain"></script>';
		$html.='<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>';
		$html.='<script type="text/javascript" src="/ueditor/ueditor.all.js"></script>';
		$html.='<script type="text/javascript">';
        $html.="var ue = UE.getEditor('".$tname."',{
    	toolbars: [
        ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'pagebreak','background', '|',
                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                'print', 'preview', 'searchreplace', 'help', 'drafts']
    ],initialFrameWidth:".$w.",initialFrameHeight:".$h."});ue.ready(function() {ue.setContent('".$content."');});";
   		$html.='</script>';
		return $html;
	}
	function selectTree($pid,$selectid = 0 , $table ) {
		global $db , $table_arr;
		//$table_arr[] = '<option value="0">顶级</option>';
		
		$query1=$db->query("select * from $table where game_pid= $pid");
		while($q=$db->fetch_array($query1)){
			$table_str = '';
			$table_str .= '<option value="'.$q['id'].'"';
			if($selectid == $q['id']){
				$table_str .= ' selected';
			}
			$table_str .= '>';
			if($q['game_havefather'] == 1) {
				$n = count(split(',',$q['game_path'])) - 2;
				for($i = 1; $i <= $n;$i++) {
					$table_str .= '｜---';
				}
			}
			$table_str .= $q['game_name'].'</option>';
			
			$table_arr[] = $table_str;
			if($q['game_havechild'] == 1) {
				selectTree($q['id'],$selectid , $table );
			}
		}
		
		return $table_arr;
	}
	
	function insert_sql($arr,$table){
		$sql="insert into $table (";
		$sql1="";
		$sql2="";
		if(is_array($arr)){
			foreach($arr as $key => $value){
				if(is_array($value)){
					$sql1.=empty($sql1)?$value[0]:",".$value[0];
					$sql2.=empty($sql2)?"'$".$value[1]."'":",'$".$value[1]."'";
				}else{
					$sql1.=empty($sql1)?$value:",".$value;
					$sql2.=empty($sql2)?"'$".$value."'":",'$".$value."'";
				}
			}
		$sql=$sql.$sql1.') values ( '.$sql2.' )';
		return $sql;
		}

	}

function get_cname($table,$needid=''){
	global $db;
	$sql="SHOW COLUMNS FROM $table";

	$query=$db->query($sql);
	$colums = '';
	$i=0;
	while($a=$db->fetch_array($query)){
		$colums_=$a['Field'];
		if($needid==1){
			$colums.=empty($colums)?$colums_:",".$colums_;
		}else{
			if($i>0){
				$colums.=empty($colums)?"$colums_":",".$colums_."";
			}
		}
		$i++;
	}
	$arr=explode(",",$colums);
	return  insert_sql($arr,$table);

}


function tableTree($pid) {
	global $db ,$table_arr;
	$table_arr = '';
	$query1=$db->query("select * from ".TABLE_GAMECAT." a left join ".TABLE_DB_CATEGORY." b on a.game_cat=b.category_id where a.game_pid= $pid order by server_flag asc");
	while($q=$db->fetch_array($query1)){
		$table_arr .= '<tr id="'.$q['game_nike_name'].'_'.$q['id'].'" style="display:none;"><td align="center"><input type="checkbox" name="moderate[]" value="'.$q['id'].'"></td>';
		$table_arr .= '<td height="22" align="left">';
		if($q['game_havefather'] == 1) {
			$n = count(explode(',',$q['game_path'])) - 2;
			for($i = 1; $i <= $n;$i++) {
				$table_arr .= '｜------';
			}
		}
		$table_arr .= $q['game_name'].'</td>';
		$table_arr .= '<td align="center">'.$q['category_name'].'</td>';
		$table_arr .= '<td align="center">'.($q['game_open']== 1 ? "开启" : "<font color='red'>关闭</font>").'</td>';
		$table_arr .= '<td align="center">'.($q['game_hidden']== 0 ? "否" : "<font color='red'>已隐藏</font>").'</td>';
		$table_arr .= '<td align="center">'.$q['game_title'].'</td>';
		$table_arr .= '<td align="center"><a href="?action=gamecat&do=add&type=edit&id='.$q['id'].'&pid='.$q['game_pid'].'">修改</a>&nbsp;&nbsp; <a href="javascript:" onclick="del_data('.$q['id'].');" id="dialog_del">删除</a>&nbsp;&nbsp;<a href="javascript:addcps('.$q['id'].','.$q['game_pid'].');">点击添加cps</a></td></tr>';

		if($q['game_havechild'] == 1) {
			tableTree($q['id']);
		}

	}
	return array($table_arr) ;
}


function RemoveXSS($val) {
	   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	   // this prevents some character re-spacing such as <java\0script>
	   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
	   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
	   // straight replacements, the user should never need these since they're normal characters
	   // this prevents like <IMG SRC=@avascript:alert('XSS')>
	   $search = 'abcdefghijklmnopqrstuvwxyz';
	   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   $search .= '1234567890!@#$%^&*()';
	   $search .= '~`";:?+/={}[]-_|\'\\';
	   for ($i = 0; $i < strlen($search); $i++) {
		  // ;? matches the ;, which is optional
		  // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

		  // @ @ search for the hex values
		  $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		  // @ @ 0{0,7} matches '0' zero to seven times
		  $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	   }

	   // now the only remaining whitespace attacks are \t, \n, and \r
	   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base' , 'marquee');
	   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	   $ra = array_merge($ra1, $ra2);

	   $found = true; // keep replacing as long as the previous round replaced something
	   while ($found == true) {
		  $val_before = $val;
		  for ($i = 0; $i < sizeof($ra); $i++) {
			 $pattern = '/';
			 for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if ($j > 0) {
				   $pattern .= '(';
				   $pattern .= '(&#[xX]0{0,8}([9ab]);)';
				   $pattern .= '|';
				   $pattern .= '|(&#0{0,8}([9|10|13]);)';
				   $pattern .= ')*';
				}
				$pattern .= $ra[$i][$j];
			 }
			 $pattern .= '/i';
			 $replacement = substr($ra[$i], 0, 2).' '.substr($ra[$i], 2); // add in <> to nerf the tag
			 $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
			 if ($val_before == $val) {
				// no replacements were made, so exit the loop
				$found = false;
			 }
		  }
	   }
	   return $val;
	}
	
	
	
	/**
	 * 获取分页数据
	 *
	 * @param string $total_page		查询sql获取总记录数
	 * @param string $sql2		查询sql2获取数据
	 * @param string $baselink	页面链接
	 * @param int $pagesize		每页显示记录数，默认显示15条
	 * @param int $pagestyle	显示样式，默认为4，可用值（1,2,3,4,5）
	 * @return array 返回数据，total=>总记录数，pages=>页面显示，result=>查询数据集
	 */
	function sql_index_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by, $pagesize=15 , $category_id = 0, $pagestyle=4, $db2 = ''){
		global $db, $page;
		// if($db2 != ''){
			// $db = $db2;
		// }
		// 获取总记录数
		$total = $db->get_value($total_sql);
	
		// 处理page
		$page = isset($page) && $page > 1 ? intval($page) : 1;
		
		// 分页
		require_once(CLASS_DIR . 'indexpage.class.php');
		// $page_show = new page($page, $total, $pagesize);
		$page_show = new indexpage(array('total' => $total, 'nowindex' => $page, 'perpage' => $pagesize, 'url' => $baselink));
		// var_dump($page_show);
		// $pages = $page_show->show($pagestyle);
		$pages = $page_show->genHtml($baselink , $category_id);
		if($order_name != '' && $order_by != ''){
			if(strpos($page_sql,'ORDER BY') > 0){
				$page_sql = Substr($page_sql,0,strpos($page_sql,'ORDER BY'));
				$page_sql .= 'ORDER BY '.$order_name.' '.$order_by.' ';
			}
		}
		// 获取数据
		$page_sql 	.= ' LIMIT ' . $page_show->offset . ', ' . $pagesize;
		$query 	= $db->query($page_sql);
		$data	= $db->array_num($query);

		// 释放结果
		$db->free_result($query);
		
		// page($v_page, $v_dbcount, $v_size,$back=0,$back_page='photos.php')
		 
		$result['total'] 	= $total;
		$result['pages'] 	= $pages;
		$result['order_name'] 	= $order_name;
		$result['order_by'] = $order_by;
		$result['result'] 	= $data;

		return $result;
	}
	
	/**
	 * 删除文件夹内的内容
	 *
	 * @param unknown_type $dir
	 */
	function delete_dir_files($dir){
		if( is_dir($dir) ){
			$handle = opendir($dir);
	
			while (($file = readdir($handle)) !== false){
				if( $file != '.' && $file != '..' )
					unlink($dir . '/' . $file);
			}
	
			closedir($handle);
		}
	}
	
	/**
	 * 获取客户端IP地址
	 *
	 * @return unknown
	 */
	function get_OlineIp() {
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
		{
			$onlineip = getenv('HTTP_CLIENT_IP');
		}
		elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
		{
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
		{
			$onlineip = getenv('REMOTE_ADDR');
		}
		elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
		{
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $ipmatches);
		$onlineip = $ipmatches[0] ? $ipmatches[0] : 'unknown';
		return $onlineip;
	}
	
	
	function login_check(){
		global $_COOKIE;

		$auth = isset($_COOKIE[COOKIEPRE.'auth']) ? $_COOKIE[COOKIEPRE.'auth'] : '';
        // var_dump($auth);
		// 未登录
		if( !$auth )
			return false;

		list($username,$isactive,$password,$userid,$coin,$token) = explode('/', authcode($auth, 'DECODE', AUTHKEY));
		$arr = array($username,$isactive,$password,$userid,$coin,$token);
		//var_dump($arr);
		if($username&&$password&&$userid){
			return $arr;
		}else{
			return false;
		}
	}
	
	 //cookie  start
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
			$ckey_length = 4;
	
			$key = md5($key ? $key : UC_KEY);
			$keya = md5(substr($key, 0, 16));
			$keyb = md5(substr($key, 16, 16));
			$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	
			$cryptkey = $keya.md5($keya.$keyc);
			$key_length = strlen($cryptkey);
	
			$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
			$string_length = strlen($string);
	
			$result = '';
			$box = range(0, 255);
	
			$rndkey = array();
			for($i = 0; $i <= 255; $i++) {
				$rndkey[$i] = ord($cryptkey[$i % $key_length]);
			}
	
			for($j = $i = 0; $i < 256; $i++) {
				$j = ($j + $box[$i] + $rndkey[$i]) % 256;
				$tmp = $box[$i];
				$box[$i] = $box[$j];
				$box[$j] = $tmp;
			}
	
			for($a = $j = $i = 0; $i < $string_length; $i++) {
				$a = ($a + 1) % 256;
				$j = ($j + $box[$a]) % 256;
				$tmp = $box[$a];
				$box[$a] = $box[$j];
				$box[$j] = $tmp;
				$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
			}
	
			if($operation == 'DECODE') {
				if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
					return substr($result, 26);
				} else {
						return '';
					}
			} else {
				return $keyc.str_replace('=', '', base64_encode($result));
			}
		}
	
	 function login_setcookie($username, $isactive,$password , $userid, $life=0 , $coin , $token){
	
			c_setcookie('auth', authcode("$username/$isactive/$password/$userid/$coin/$token", 'ENCODE', AUTHKEY), $life , true);
	
	
	
			c_setcookie('auth_time', $life, $life);
			c_setcookie('user', $username, $life);
		}
	
	function logout_setcookie(){
			c_setcookie('auth', '', -365*86400);
			c_setcookie('auth_time', '', -365*86400);
			//c_setcookie('user', '', -365*86400);
			c_setcookie('password', '', -365*86400);
			c_setcookie('newserver', '', -365*86400);
			c_setcookie('playnowgame', '', -365*86400);
			c_setcookie('playgame', '', -365*86400);
			c_setcookie('playedgame', '', -365*86400);
			return 1;
		}
	
	function c_setcookie($name,$value,$life = 0 , $only = false) {
		setcookie(COOKIEPRE.$name, $value, $life ? time() + $life : 0, COOKIEPATH, COOKIEDDOMAIN , '' , $only);
	}
	
	/**
	 * 创建桌面图标
	 *
	 * @param string $filename 文件名
	 * @param string $site_url 平台地址
	 * @return string 返回输出内容
	 */
	function create_shortcut($filename, $site_url){
		$filename=iconv('UTF8', 'GB2312', $filename) . '.url';

		header("Content-Type: application/octet-stream");
		header("Content-disposition: attachment; filename=$filename");

		$shortcut = "[InternetShortcut]
		URL=$site_url
		IDList=
		IconIndex=1
		IconFile=$site_url/favicon.ico
		HotKey=1626
		[{000214A0-0000-0000-C000-000000000046}]
		Prop3=19,2";

		return $shortcut;
	}
	
	function validation_filter_id_card($id_card)
 {
 
   if(strlen($id_card) == 18)
   {
   	 $brithday = get_birthday($id_card);
  	 $brithday = explode('-' , $brithday);
  	 
  	 if(intval($brithday[0]) < 1900 || intval($brithday[0]) > date('Y', time())){
  	 	return false;
  	 }
  	 if(intval($brithday[1]) < 0 || intval($brithday[1]) > 12)
  	 	return false;
  	 if(intval($brithday[2]) < 0 || intval($brithday[2]) > 31)
  	 	return false;
  	 
    return idcard_checksum18($id_card);
   }
   elseif((strlen($id_card) == 15))
   {
   	$brithday = get_birthday($id_card);
  	$brithday = explode('-' , $brithday);
  	$brithday[0] = intval('19' . $brithday[0]);
  	
  	if($brithday[0] < 1912 || $brithday[0] > date('Y', time())){
  	 	return false;
  	}
  	 if($brithday[1] < 0 || $brithday[1] > 12)
  	 	return false;
  	 if($brithday[2] < 0 || $brithday[2] > 31)
  	 	return false;
  	 	
    $id_card = idcard_15to18($id_card);
    return idcard_checksum18($id_card);
   }
   else
   {
    return false;
   }
 }

  /**
  * 根据身份证获取用户的出生日期
  *
  * @param unknown_type $validation
  * @return unknown
  */
 function get_birthday($validation) {
	if(strlen($validation) == 18) {
		$brithday = substr($validation,6,8);
		$brithday = substr($brithday,0,4) . "-" . substr($brithday,4,2) . "-" . substr($brithday,6,2);
	} elseif(strlen($validation) == 15){
		$brithday = substr($validation,6,6);
		$brithday = substr($brithday,0,2) . "-" . substr($brithday,2,2) . "-" . substr($brithday,4,2);
	} else {
			$brithday = '';
	}
	return $brithday;
}

 // 计算身份证校验码，根据国家标准GB 11643-1999
 function idcard_verify_number($idcard_base)
 {
   if(strlen($idcard_base) != 17)
   {
    return false;
   }
   //加权因子
   $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

  //校验码对应值
  $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
  $checksum = 0;
  for ($i = 0; $i < strlen($idcard_base); $i++)
  {
   $checksum += substr($idcard_base, $i, 1) * $factor[$i];
  }
   $mod = $checksum % 11;
   $verify_number = $verify_number_list[$mod];
   return $verify_number;
 }

 // 将15位身份证升级到18位
 function idcard_15to18($idcard){
  if (strlen($idcard) != 15){
   return false;
  }else{
   // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
   if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
    $idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
   }else{
    $idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
   }
  }
  $idcard = $idcard . idcard_verify_number($idcard);
  return $idcard;
 }
 // 18位身份证校验码有效性检查
 function idcard_checksum18($idcard){
  if (strlen($idcard) != 18){ return false; }
   $idcard_base = substr($idcard, 0, 17);
  if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
   return false;
  }else{
   return true;
  }
 }
 
 function getFriendUrl($id=0)
{
	global $db;
	$temp = array();
	$result = $db->query("select url,img,title from friend_url where status=1 and gameid=".$id." order by orderby desc");
	while($row = $db->fetch_array($result))
	{
		$temp[] = $row;
	}
	return $temp;
}
	function &init_db_code()
	{
		return new db_mysql(DATABASE_PASSPORT_HOST,DATABASE_PASSPORT_USER,DATABASE_PASSPORT_PASS,PASSPORTCODE);
	}


	// passport数据库
	function &init_db_passport(){
		$db = new db_mysql(DATABASE_PASSPORT_HOST, DATABASE_PASSPORT_USER, DATABASE_PASSPORT_PASS, DATABASE_PASSPORT_NAME);
		return $db;
	}
	
	// 数据库
	function &init_db(){
		// stat数据库
		$db = new db_mysql(DBHOST, DBUSER, DBPWD, DBNAME, 0, true);
		return $db;
	}
?>
