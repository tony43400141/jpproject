<?php
	_check_define();

	class UserGroup{
		public $db;
		public $table;
		function __construct() {
			global $db;
			// $this->db = &$db ;
			$this->db = new db_mysql(DBHOST, DBUSER, DBPWD, DBNAME);
			$this->table = TABLE_GROUP;
			$this->usertable = TABLE_ADMIN;
		}

		/**
		 * 获取所有分组信息
		 *
		 * @return array $result 返回信息
		 */
		function get_groups(){
			global $ACCOUNT_RIGHTS;

			// 获取信息
			$sql = 'SELECT * FROM ' . $this->table;
			$query = $this->db->query($sql);

			// 获取结果集
			$result = $this->db->array_num($query);

			// 释放结果集
			$this->db->free_result($query);

			// 处理结果
			if( !empty($result) ){
				foreach ($result as $key => $value){
					if( $value['group_rights'] ){
						// 解序列化
						$rights = unserialize($value['group_rights']);

						// 处理权限
						$right = '';
						foreach ($rights as $key2 => $value2){
							foreach ($value2 as $key3 => $value3){
								if(isset($ACCOUNT_RIGHTS[$key2][$key3])){
									$right .= $ACCOUNT_RIGHTS[$key2][$key3] . "\t";
								}
							}
						}
						
						$result[$key]['group_rights'] = $right;
					}
				}
			}

			return $result;
		}

		/**
		 * 获取一个分组信息
		 *
		 * @param int $id 分组ID
		 * @return array 返回信息
		 */
		function get_one_group($id){

			// 获取数据
			$sql = 'SELECT * FROM ' . $this->table . ' WHERE group_id=' . $id;
			$result = $this->db->get_one($sql);

			// 解序列化
			if( $result['group_rights'] )
				$result['group_rights'] = unserialize($result['group_rights']);

			return $result;
		}

		/**
		 * 新增分组
		 *
		 * @param string $groupname 分组名
		 * @param array $grights 分组权限
		 */
		function add_group($groupname, $grights = array()){
			global $ops;

			// 处理分组权限
			$rights = $this->treat_group_rights($grights);

			// 新增并记录log
			$sql = 'INSERT INTO ' . $this->table . ' (group_name, group_rights) VALUES(' . "'" . $groupname . "', '" . $rights . "'" . ')';
			if( $this->db->query($sql) )
				log_record($ops['addgroupsuccess'] . '【' . $groupname . '】');
		}

		/**
		 * 修改分组
		 *
		 * @param string $groupname 分组名
		 * @param array $grights 分组权限
		 */
		function edit_group($gid, $groupname, $grights = array()){
			global $ops;

			// 处理分组权限
			$rights = $this->treat_group_rights($grights);

			// 修改并记录log
			$sql  = ' UPDATE ' . $this->table . ' SET group_name=' . "'" . $groupname . "', group_rights='" . $rights . "'";
			$sql .= ' WHERE group_id=' . $gid;
			if( $this->db->query($sql) )
				log_record($ops['editgroupsuccess'] . '【' . $groupname . '】');
		}

		/**
		 * 处理分组权限
		 *
		 * @param array $grights 分组权限
		 * @return string 返回序列化的权限值
		 */
		function treat_group_rights($grights = array()){
			// 处理分组权限
			if( !empty($grights) ){
				$new_rights = array();
				foreach ($grights as $value){
					$val_array = explode('_',$value);
					$new_rights[$val_array[0]][$val_array[1]] = 1;
				}
				$rights = serialize($new_rights);

				unset($grights, $new_rights);
			} else
				$rights = '';

			return $rights;
		}

		/**
		 * 判断分组下是否有用户
		 *
		 * @param int $id
		 * @return int 无用户返回0,有用户返回用户数
		 */
		function group_has_users($id){

			$sql = 'SELECT COUNT(*) FROM ' . TABLE_ADMIN . ' WHERE admin_groupid=' . $id;
			return $this->db->get_value($sql);
		}

		/**
		 * 删除分组
		 *
		 * @param int $id 分组ID
		 */
		function delete_group($id){
			global $ops;

			// 获取分组名
			$group_name = $this->get_group_name($id);

			// 删除并记录log
			$sql = 'DELETE FROM ' . $this->table . ' WHERE group_id=' . $id;
			if( $this->db->query($sql) )
				log_record($ops['deletegroupsuccess'] . '【'. $group_name .'】');
		}

		/**
		 * 获取分组名
		 *
		 * @param int $id
		 * @return string 返回分组名
		 */
		function get_group_name($id){

			$sql = 'SELECT group_name FROM ' . $this->table . ' WHERE group_id=' . $id;
			return $this->db->get_value($sql);
		}

		/**
		 * 获取分组对
		 *
		 * @return array 返回结果集
		 */
		function get_group_pair(){

			// 获取所有分组
			$sql = 'SELECT group_id, group_name FROM ' . $this->table;
			$query = $this->db->query($sql);

			$result = array();
			while ($res = $this->db->fetch_array($query))
				$result[$res['group_id']] = $res['group_name'];

			// 释放结果
			$this->db->free_result($query);

			return $result;
		}
		
		/**
		 * 修改密码
		 *
		 */
		function edit_psd($id,$oldpsd,$psd){
			// 获取数据
			$sql = 'SELECT COUNT(1) AS count FROM ' . $this->usertable . ' WHERE admin_id=' . $id . ' AND admin_pwd = "'.md5($oldpsd).'"';
			$result = $this->db->get_one($sql);
			if($result['count'] == 1){
				// 修改并记录log
				$sql  = ' UPDATE ' . $this->usertable . ' SET admin_pwd=' . '"' . md5($psd) . '"';
				$sql .= ' WHERE admin_id=' . $id;
				if( $this->db->query($sql) ){
					log_record($ops['editpsdsuccess'] . '【' . $groupname . '】');
					return 1;
				}
			}
		}
	}
?>