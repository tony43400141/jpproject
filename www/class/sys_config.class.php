<?php
	defined('WLGMTOOL') or exit('Access Denied');

	class Sys_config{
		var $table_sys_config				= TABLE_SYS_CONFIG;
		var $table_db_usercount				= TABLE_USERCOUNT;
		var $db;

		function __construct() {
			global $db ;
			$this->db = & $db;
			$this->passportdb = init_db_passport();
		}
		
		/**
		 * 查询视频列表
		 *
		 * @param string $baselink 		分页连接
		 * @param string $order_name 	排序字段
		 * @param string $order_by		排序正序或倒序
		 */
		function getSys_config($baselink,$order_name,$order_by) {
			$total_sql = 'SELECT COUNT(1)  FROM ' . $this->table_sys_config .' ';
			$page_sql = 'SELECT a.*
							FROM ' . $this->table_sys_config .' AS a
							ORDER BY sys_config_id DESC';
			$query = sql_page_data($total_sql, $page_sql, $baselink, $order_name, $order_by);
			return $query;
		}
		
		/**
		 * 查询视频详细内容
		 *
		 * @param string $id 视频分类ID
		 */
		function getSys_configResult($id) {
			$sql = 'SELECT a.* 
							FROM ' . $this->table_sys_config .' AS a
							WHERE a.sys_config_id = '.$id;
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}
		
		/**
		 * 新增视频
		 *
		 * @param string $m_name 	视频名
		 * @param string $m_desc 	视频说明
		 * @param string $m_link 	视频连接
		 * @param string $mc_name 	视频名称
		 * @param string $status	视频状态
		 */
		function insertSys_config($post_array){
			global $ops;
				$id = $this->db->query_insert($this->table_sys_config,$post_array);
				if($id){
					log_record($ops['editSys_configSuccess'] . '【系统信息修改】');
				}
		}
		
		/**
		 * 保存视频
		 *
		 * @param string $m_name 	视频名
		 * @param string $m_desc 	视频说明
		 * @param string $m_link 	视频连接
		 * @param string $mc_name 	视频名称
		 * @param string $status	视频状态
		 */
		function editSys_config($post_array, $id_array){
			global $ops;
			
			$sql = 'SELECT a.* 
							FROM ' . $this->table_sys_config .' AS a
							WHERE a.sys_config_id = '.$id_array['sys_config_id'];
			$query = $this->db->query($sql);
			$result = $this->db->array_num($query);
			if($result){
				$flag = $this->db->query_update($this->table_sys_config,$post_array,$id_array);
				if( $flag == 1 ){
					log_record($ops['editSys_configSuccess'] . '【系统信息修改】');
				}
			}else{
				$id = $this->db->query_insert($this->table_sys_config,$post_array);
				if($id){
					log_record($ops['editSys_configSuccess'] . '【系统信息修改】');
				}
			}
			
		}
		
		/**
		 * 删除视频
		 *
		 * @param varchar $id 视频分类ID
		 */
		function delSys_config($id){
			global $ops;
			$str_array = explode("|",$id);
			foreach($str_array as $val){
				$sql = 'SELECT sys_config_name FROM ' . $this->table_sys_config .' WHERE sys_config_id = '.$val;
				$query = $this->db->query($sql);
				$result = $this->db->array_num($query);
				$query = 'DELETE FROM ' . $this->table_sys_config . ' WHERE sys_config_id=' . $val;
				if( $this->db->query($query) )
					log_record($ops['delSys_configSuccess'] . '【' . $result[0]['sys_config_name'] .'】');
			}
		}
		
		/**
		 * 
		 *
		 * 
		 */
		function getUserCount(){
			$sql = 'SELECT * FROM ' . $this->table_db_usercount .' where id = 1 ';
			$query = $this->passportdb->query($sql);
			$result = $this->passportdb->array_num($query);
			return $result;
		}
	}
?>