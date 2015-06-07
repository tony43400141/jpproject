<?php
	_check_define();

	class Admin{
		public $username;
		public $db;
		public $table;
		function __construct() {
			global $db;
			$this->db = &$db ;
			$this->table = TABLE_ADMIN;
		}

		/**
		 * 后台用户进入判断
		 *
		 * @param string $UserName  用户名
		 * @param string $Password  密码
		 */
		function Login($UserName,$Password) {

			// 获取用户密码
			$query = 'SELECT admin_pwd FROM ' . $this->table . ' WHERE admin_user=' . "'" . $UserName . "'";
			$pwd = $this->db->get_value($query);

			// 检查该用户名是否存在
			if( $pwd ) {
				if(md5(trim($Password)) == $pwd) {

					// 获取用户信息
					$query = 'SELECT * FROM ' . $this->table . ' WHERE admin_user=' . "'" . $UserName . "'" .' AND admin_pwd=' . "'" . $pwd . "'";
					$res = $this->db->get_one($query);

					// 更新用户登录时间和IP
					$query  = ' UPDATE ' . $this->table . ' SET admin_logintime=' . time() . ', admin_loginip=' . intval(ip2long($_SERVER['REMOTE_ADDR']));
					$query .= ' WHERE admin_id=' . $res['admin_id'];
					$this->db->query($query);

					// 获取用户所在分组的权限
					if( $res['admin_groupid'] ){
						require_once(CLASS_DIR . 'group.class.php');
						$group_obj = new UserGroup();
						$groups = $group_obj->get_one_group($res['admin_groupid']);

						$res['rights'] = $groups['group_rights'];
					} else
						$res['rights'] = array();

					return $res;
				}
			}
		}

		function showAdmin($id = '') {
			if(empty($id)) {
				$sql = 'SELECT * FROM ' . $this->table .' ORDER BY admin_id DESC';
			} else {
				$sql = 'SELECT * FROM ' . $this->table . ' WHERE admin_id=' . $id;
			}
			$query = $this->db->query($sql);
			return $this->db->array_num($query);
		}

		/**
		 * 添加管理员
		 *
		 * @param string $username 用户名
		 * @param string $password 密码
		 * @param int $group 所属分组
		 */
		function addAdmin($username, $password, $group=0) {
			global $ops;

			// 判断用户是否存在
			$sql = 'SELECT COUNT(*) FROM ' . $this->table . ' WHERE admin_user=' . "'" . $username . "'" ;

			if( $this->db->get_value($sql) ){
				showmessage($ops['adminerror']);
			} else {

				// 新增并记录log
				$query = 'INSERT INTO ' . $this->table . " (admin_user, admin_pwd, admin_groupid) VALUES ('$username', '" . md5(trim($password)) . "', '$group')";
				if( $this->db->query($query) )
					log_record($ops['addadmin'] . '【' . $username .'】');
			}
		}
		/**
		 * 删除用户
		 *
		 * @param int $id
		 */
		function deleteAdmin($id) {
			global $ops;

			// 获取管理员名称
			$user = $this->get_admin_name($id);

			// 删除并记录log
			$query = 'DELETE FROM ' . $this->table . ' WHERE admin_id=' . $id;
			if( $this->db->query($query) )
				log_record($ops['deladmin'] . '【' . $user .'】');
		}

		/**
		 * 修改管理员信息
		 *
		 * @param int $id 用户ID
		 * @param string $password 密码
		 * @param int $group 属于分组
		 */
		function updateAdmin($id, $password, $group) {
			global $ops;

			$update = '';
			if( $password )
				$update = ", admin_pwd='" . md5(trim($password)) . "'";

			// 获取管理员名称
			$user = $this->get_admin_name($id);

			// 修改并记录log
			$query = 'UPDATE ' . $this->table . ' SET admin_groupid=' . $group . $update . '  WHERE admin_id=' . $id;
			if( $this->db->query($query) )
				log_record($ops['editadmin'] . '【' . $user .'】');
		}

		/**
		 * 获取管理员名称
		 *
		 * @param int $id 管理员ID
		 * @return string 返回名称
		 */
		function get_admin_name($id){
			$sql = 'SELECT admin_user FROM ' . $this->table . ' WHERE admin_id=' . $id;
			return $this->db->get_value($sql);
		}
	}
