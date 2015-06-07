<?php
	defined('WLGMTOOL') or exit('Access Denied');

	class Adv{
		var $table				= TABLE_ADV;
		var $db;

		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		/**
		 *咨询列表
		 *
		 * @param string $baselink 		分页连接
		 * @param string $order_name 	排序字段
		 * @param string $order_by		排序正序或倒序
		 */
		function getAdvList($baselink,$order_name,$order_by,$array=array()) {
			$cd='';
			if($array['is_contact']!=-1)
			{
				$cd.=" and is_contact=".$array['is_contact'];
			}
			if(!empty($array['username']))
			{
				$cd.=" and username like '%".$array['username']."%'";
			}
			if(!empty($array['email']))
			{
				$cd.=" and email like '%".$array['email']."%'";
			}
			if(!empty($array['mobile']))
			{
				$cd.=" and mobile like '%".$array['mobile']."%'";
			}
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table ." where 1=1 ".$cd;
			$page_sql = 'SELECT * 
							FROM ' . $this->table .' where 1=1 '.$cd.' 
							 ORDER BY {$order_name} {$order_by}';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 咨询信息表
		 */
		function getAdvById($advid=0) {
			$sql = 'SELECT * 
					FROM ' . $this->table .' where
					 adv_id='.$advid.' ORDER BY update_time DESC';
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 新增咨询
		 *
		 */
		function AddAdv($arr,$table='')
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
		function updateAdv($arr,$table='',$where)
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
		function del($advid=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where adv_id='.$advid;
			$this->db->query($sql);
		}
		/**
		 * 
		 */
		function cdel($cid=0) {
			$sql = 'delete
			FROM ' . $this->ctable .'
			where oi_id='.$cid;
			$this->db->query($sql);
		}
	}
?>