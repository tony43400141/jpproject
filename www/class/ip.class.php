<?php
	defined('WLGMTOOL') or exit('Access Denied');

	class IP{
		var $table				= "ip_forbid_list";
		var $db;

		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		
		function getIpList($baselink,$order_name,$order_by) {
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table;
			$page_sql = 'SELECT * 
							FROM ' . $this->table .'
							 ORDER BY ip_id DESC';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		function getIpById($ipid=0) {
			$sql = 'SELECT * 
					FROM ' . $this->table .' where
					 ip_id='.$ipid;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 添加ip禁止
		 *
		 */
		function addIp($arr,$table='')
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
		function updateIp($arr,$table='',$where)
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
		function del($ipid=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where ip_id='.$ipid;
			$this->db->query($sql);
		}
	}
?>