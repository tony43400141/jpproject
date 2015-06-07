<?php
	defined('WLGMTOOL') or exit('Access Denied');
	class Gamea{
		
		var $table		= TABLE_A;
		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		
		/**
		 * 查询作品列表
		 *
		 */
		function getGameaList($baselink,$order_name,$order_by ,$keyword=0,$is_m=-1,$is_display=-1,$pages = PAGES) {
			$cd = '';
			if($is_m!=-1) $cd .= ' and is_m = '. $is_m ;
			if($is_display!=-1) $cd .= ' and is_display = '. $is_display ; 
			if($keyword) $cd .= ' and ga_title = "'. $keyword .'"'; 
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table .' where 1' . $cd;
			$page_sql = 'SELECT * FROM ' . $this->table .' where 1 '.$cd .' ORDER BY {$order_name} {$order_by}';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by, $pages);
			return $query;
		}
		
		/**
		 * 查询作品详细内容
		 *
		 * @param string $id 文章分类ID
		 */
		function getGameaResult($id) {
			$sql = 'SELECT a.*  
							FROM ' . $this->table .' AS a 
							WHERE a.ga_id = '.$id;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 新增作品
		 *
		 */
		function addGamea($arr,$table='')
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
		 * 修改作品
		 *
		 */
		function updateGamea($arr,$table='',$where)
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
		 *删除作品 
		 */
		function delGamea($adid=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where ga_id='.$adid;
			$this->db->query($sql);
		}
		
		
	}
?>