<?php
	defined('WLGMTOOL') or exit('Access Denied');

	class AD{
		var $table				= TABLE_AD;
		var $ctable				= TABLE_CAD;
		var $db;

		function __construct() {
			global $db ;
			$this->db = & $db;
		}
		
		
		/**
		 *广告分类列表
		 *
		 * @param string $baselink 		分页连接
		 * @param string $order_name 	排序字段
		 * @param string $order_by		排序正序或倒序
		 */
		function getCList($baselink,$order_name) {
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->ctable;
			$page_sql = 'SELECT *
			FROM ' . $this->ctable .' ORDER BY c_id DESC';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 广告分类列表
		 */
		function getCListById($cid=0) {
			$sql = 'SELECT *
			FROM ' . $this->ctable .'
			where c_id='.$cid;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		
		
		/**
		 *广告列表
		 *
		 * @param string $baselink 		分页连接
		 * @param string $order_name 	排序字段
		 * @param string $order_by		排序正序或倒序
		 */
		function getAdList($baselink,$order_name,$order_by,$keyword,$c_id) {
			$cd='';
			if(!empty($keyword))
			{
				$cd.=" and a.ad_title='".$keyword."'";
			}
			if(!empty($c_id))
			{
				$cd.=" and a.c_id=".$c_id;
			}
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table. ' AS a where 1=1 '.$cd;
			$page_sql = 'SELECT a.*,b.c_name 
							FROM ' . $this->table .' AS a left join '.$this->ctable.' AS b ON a.c_id = b.c_id where 1=1'.$cd.' ORDER BY a.add_time DESC';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 广告列表
		 */
		function getAdListLimit($limit=10) {
			$sql = 'SELECT a.*,b.c_name 
							FROM ' . $this->table .' AS a left join '.$this->ctable.' AS b ON a.c_id = b.c_id
							 ORDER BY a.update_time DESC limit 0, '.$limit;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 广告信息表
		 */
		function getAdListById($adid=0) {
			$sql = 'SELECT a.*,b.c_name 
					FROM ' . $this->table .' AS a left join '.$this->ctable.' AS b ON a.c_id = b.c_id where
					 ad_id='.$adid.' ORDER BY a.update_time DESC';
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		/**
		 * 新增广告
		 *
		 */
		function addAd($arr,$table='')
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
		function updateAd($arr,$table='',$where)
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
		function del($adid=0) {
			$sql = 'delete
			FROM ' . $this->table .'
			where ad_id='.$adid;
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