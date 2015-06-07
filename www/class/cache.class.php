<?php
/**
 * 文件缓存类
 *
 */
class File_Cache{

	var $file_path;
	var $db;
	var $table;
	var $cache_data = array();
	var $array_name;

	function File_Cache(){
		global $db;
		$this->db =& $db;
	}

	/**
	 * 创建缓存文件
	 *
	 * @return bool 没有缓存数据或不存在文件路径返回false
	 */
	function create_cache_file(){

		// 没有缓存数据
		if( empty($this->cache_data) )
			return false;

		// 缓存文件路径
		if( !$this->file_path )
			return false;

		$handle = fopen($this->file_path, 'w');

		$string = "<?php\n return ".var_export($this->cache_data,TRUE).";\n?>";

		// 写缓存
		flock($handle, LOCK_EX);
		fwrite($handle, $string);
		flock($handle, LOCK_UN);
		fclose($handle);
	}
	
	/**
	 * 生成广告缓存
	 *
	 */
	function create_ad_file() {
		
		$sql = 'select * from '. TABLE_DB_ADS . ' a left join '. TABLE_DB_AD .' b on a.ad_cat = b.ad_id where ad_open = 1 group by ad_cat order by id desc';
		$res = $this->db->query($sql);
		$ads = array();
		while ($result = $this->db->fetch_array($res)) {
			$ads[$result['ad_cat']] = $result;
		}
//		print_r($ads);exit;
		$this->file_path = CACHE_DIR .'ad_config.php';
		$this->cache_data = $ads;
		
		$this->create_cache_file();
		
	}
	
	/**
	 * 生成系统信息缓存
	 *
	 */
	function create_sys_file() {
		
		$sql = 'select * from '. TABLE_SYS_CONFIG .' where sys_config_id = 1';
		$res = $this->db->get_one($sql);
		
		$this->file_path = CACHE_DIR .'sys_config.php';
		$this->cache_data = $res;
		
		$this->create_cache_file();
		
	}
	
	/**
	 * 生成开服时间缓存
	 *
	 * @param unknown_type $gameid
	 */
	function create_time_file($gameid) {
		$sql = 'select * from '. TABLE_GAME_TIME . ' where game_title = "'. $gameid .'"';
		$res = $this->db->query($sql);
		$r = $this->db->array_num($res);
		
		$this->file_path = CACHE_DIR . 'game_time_'. $gameid .'.php';
		$this->cache_data = $r;
		$this->create_cache_file();
	}

}