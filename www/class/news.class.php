<?php
	defined('WLGMTOOL') or exit('Access Denied');
	class News{
		
		var $table		= TABLE_NEWS;
		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		
		/**
		 * 查询新闻列表
		 *
		 */
		function getNewsList($baselink,$order_name,$order_by ,$keyword=0,$is_display=-1,$pages = PAGES) {
			$cd = '';
			if($is_display!=-1) $cd .= ' and is_display = '. $is_display ; 
			if($keyword) $cd .= ' and n_title = "'. $keyword .'"'; 
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table .' where 1' . $cd;
			$page_sql = 'SELECT * FROM ' . $this->table .' where 1 '.$cd .' ORDER BY {$order_name} {$order_by}';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 查询新闻详细内容
		 *
		 * @param string $id 文章分类ID
		 */
		function getNewsResult($id) {
			$sql = 'SELECT a.*  
							FROM ' . $this->table .' AS a 
							WHERE a.n_id = '.$id;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 新增新闻
		 *
		 */
		function addNews($arr,$table='')
		{
			if(empty($table))
			{
				$table = $this->table;
			}
			else
			{
				$table = $this->$table;
			}
			$sql =  $comma = '';
				foreach ($arr as $k => $v) {
					$sql .= $comma."`$k`='$v'";
					$comma = ',';
				}
				$cmd =  'INSERT INTO';
				$this->db->query("$cmd $table SET $sql");
				return $this->db->insert_id();
		}
		/**
		 * 修改新闻
		 *
		 */
		function updateNews($arr,$table='',$where)
		{
			if(empty($table))
			{
				$table = $this->table;
			}
			else
			{
				$table = $table;
			}
			$sql =  $comma = '';
			foreach ($arr as $k => $v) {
				$sql .= $comma."`$k`='$v'";
				$comma = ',';
			}
			$cmd =  'UPDATE ';
			$this->db->query("$cmd $table SET $sql WHERE $where");
		}
		/**
		 *删除新闻 
		 */
		function delNews($adid=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where n_id='.$adid;
			$this->db->query($sql);
		}
		
		
	}
?>