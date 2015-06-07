<?php
	defined('WLGMTOOL') or exit('Access Denied');

	class Man{
		var $table				= TABLE_MAN;
		var $db;

		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		
		
	
		
		/**
		 *团队列表
		 *
		 * @param string $baselink 		分页连接
		 * @param string $order_name 	排序字段
		 * @param string $order_by		排序正序或倒序
		 */
		function getManList($baselink,$order_name,$order_by,$keyword,$c_id) {
			$cd='';
			if(!empty($keyword))
			{
				$cd.=" and m.m_title='".$keyword."'";
			}
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table. ' AS a where 1=1 '.$cd;
			$page_sql = 'SELECT a.*
							FROM ' . $this->table .' AS a  where 1=1'.$cd.' ORDER BY a.add_time DESC';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 团队信息表
		 */
		function getManListById($mid=0) {
			$sql = 'SELECT a.*
					FROM ' . $this->table .' AS a where
					 m_id='.$mid.' ORDER BY a.update_time DESC';
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 新增广告
		 *
		 */
		function addMan($arr,$table='')
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
		function updateMan($arr,$table='',$where)
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
		function del($mid=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where m_id='.$mid;
			$this->db->query($sql);
		}
	}
?>