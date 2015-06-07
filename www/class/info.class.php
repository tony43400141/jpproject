<?php
	defined('WLGMTOOL') or exit('Access Denied');

	class Info{
		var $table				= TABLE_INFO;
		var $db;

		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		
		/**
		 *信息列表
		 *
		 * @param string $baselink 		分页连接
		 * @param string $order_name 	排序字段
		 * @param string $order_by		排序正序或倒序
		 */
		function getInfoList($baselink,$order_name,$order_by) {
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table;
			$page_sql = 'SELECT *
							FROM ' . $this->table .'
							 ORDER BY {$order_name} {$order_by}';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 信息列表
		 */
		function getInfoListLimit($limit=10) {
			$sql = 'SELECT a.*
							FROM ' . $this->table .' AS a
							  ORDER BY a.update_time DESC limit 0, '.$limit;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 信息
		 */
		function getInfoById($iid=0) {
			$sql = 'SELECT a.*
					FROM ' . $this->table .' AS a where
					 a.i_id='.$iid.' ORDER BY a.update_time DESC';
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 新增信息
		 *
		 */
		function addInfo($arr,$table='')
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
		function updateInfo($arr,$table='',$where)
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
			$cmd =  'UPDATE ';
			$this->db->query("$cmd $table SET $sql WHERE $where");
		}
		/**
		 * 
		 */
		function del($id=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where i_id='.$iid;
			$this->db->query($sql);
		}
	}
?>