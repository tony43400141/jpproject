<?php
class db_mysql
{
	/**
	* MySQL 连接标识
	* @var resource
	*/
	var $connid;
  	var $table;
	/**
	* 整型变量用来计算被执行的sql语句数量
	* @var int
	*/
	var $querynum = 0;
	var $tables = array();
	/**
	* 数据库连接，返回数据库连接标识符
	* @param string 数据库服务器主机
	* @param string 数据库服务器帐号
	* @param string 数据库服务器密码
	* @param string 数据库名
	* @param bool 是否保持持续连接，1为持续连接，0为非持续连接
	* @return link_identifier
	*/
	function db_mysql($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0)
	{
		$func = $pconnect == 1 ? 'mysql_pconnect' : 'mysql_connect';
		if(!$this->connid = @$func($dbhost, $dbuser, $dbpw))
		{
			$this->halt('Can not connect to MySQL server');
		}
		// 当mysql版本为4.1以上时，启用数据库字符集设置
		if($this->version() > '4.1')
        {
			mysql_query("SET NAMES utf8" , $this->connid);
		}
		// 当mysql版本为5.0以上时，设置sql mode
		if($this->version() > '5.0')
		{
			mysql_query("SET sql_mode=''" , $this->connid);
		}
		if($dbname)
		{
			if(!@mysql_select_db($dbname , $this->connid))
			{
				$this->halt('Cannot use database '.$dbname);
			}
		}
		return $this->connid;
	}
	/**
	* 选择数据库
	* @param string 数据库名
	*/
	function select_db($dbname)
	{
		return mysql_select_db($dbname , $this->connid);
	}
	/**
	* 执行sql语句
	* @param string sql语句
	* @param string 默认为空，可选值为 CACHE UNBUFFERED
	* @param int Cache以秒为单位的生命周期
	* @return resource
	*/
	function query($sql , $type = '' , $expires = 3600, $dbname = '')
	{
		$func = $type == 'UNBUFFERED' ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql , $this->connid)) && $type != 'SILENT')
		{
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		return $query;
	}
	/**
	* 执行sql语句，只得到一条记录
	* @param string sql语句
	* @param string 默认为空，可选值为 CACHE UNBUFFERED
	* @param int Cache以秒为单位的生命周期
	* @return array
	*/
	function get_one($sql, $type = '', $expires = 3600, $dbname = '')
	{
		$query = $this->query($sql, $type, $expires, $dbname);
		$rs = $this->fetch_array($query);
		$this->free_result($query);
		return $rs ;
	}

	/**
	 * 执行SQL语句，只获取一个值
	 *
	 * @param string $sql 查询语句
	 * @return string 返回该值
	 */
	function get_value($sql){
		$query = $this->query($sql, 'UNBUFFERED');
		$rs = $this->fetch_array($query, MYSQL_BOTH);
		$this->free_result($query);

		return $rs[0];
	}

	/**
	* 从结果集中取得一行作为关联数组
	* @param resource 数据库查询结果资源
	* @param string 定义返回类型
	* @return array
	*/
	function fetch_array($query, $result_type = MYSQL_ASSOC)
	{
		return mysql_fetch_array($query, $result_type);
	}
	function array_num($query,$result_type = MYSQL_ASSOC)
	{
    	$result = array();
		while ($row = mysql_fetch_array($query,$result_type)){
			$result[] = $row;
		}
		return $result;
	}
	/**
	* 取得前一次 MySQL 操作所影响的记录行数
	* @return int
	*/
	function affected_rows()
	{
		return mysql_affected_rows($this->connid);
	}
	/**
	* 取得结果集中行的数目
	* @return int
	*/
	function num_rows($query)
	{
		return mysql_num_rows($query);
	}
	/**
	* 返回结果集中字段的数目
	* @return int
	*/
	function num_fields($query)
	{
		return mysql_num_fields($query);
	}
    /**
	* @return array
	*/
	function result($query, $row)
	{
		return @mysql_result($query, $row);
	}
	function free_result($query)
	{
		return mysql_free_result($query);
	}
	/**
	* 取得上一步 INSERT 操作产生的 ID
	* @return int
	*/
	function insert_id()
	{
		return mysql_insert_id($this->connid);
	}
    /**
	* @return array
	*/
	function fetch_row($query)
	{
		return mysql_fetch_row($query);
	}
    /**
	* @return string
	*/
	function version()
	{
		return mysql_get_server_info($this->connid);
	}
	function close()
	{
		return mysql_close($this->connid);
	}
    /**
	* @return string
	*/
	function error()
	{
		return @mysql_error($this->connid);
	}
    /**
	* @return int
	*/
	function errno()
	{
		return intval(@mysql_errno($this->connid)) ;
	}
	/*
	数据库加前
	*/
	function set_prefix($prefix) {
		foreach ( $this->tables as $table ){
			$this->$table = $prefix . $table;
    }
		//return $old_prefix;
	}
    /**
	* 显示mysql错误信息
	*/
	function halt($message = '', $sql = '')
	{
		exit("MySQL Query:$sql <br> MySQL Error:".$this->error()." <br> MySQL Errno:".$this->errno()." <br> Message:$message");
	}

	
	
	/**
	* 连接数据库，
	*/
	function ping($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0, $new_connect = false){
		if(!mysql_ping($this->connid)){
			mysql_close($this->connid); //注意：一定要先执行数据库关闭，这是关键
			$this->db_mysql($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0, $new_connect = false);
			return $this->connid;
		}
	}
	
	/**
	 * 新增数据
	 *
	 * @param string $table 表名
	 * @param array $ins_arr 新增元素（key为表字段名，value为值）
	 * @return int 成功返回新增记录ID，失败返回0
	 */
	function query_insert($table, $ins_arr=array()){

		if( empty($ins_arr) )
			return 0;
		$value_list = '';
		foreach ($ins_arr as $value){
			// if( is_numeric($value) )
				// $value_list .= $value . ',';
			// else
				$value_list .= "'" . $value . "',";
		}

		$value_list = substr($value_list, 0, -1);

		// 新增
		$query = 'INSERT INTO '	. $table . ' (' . implode(',', array_keys($ins_arr)) . ') VALUES (' . $value_list . ')';
		if( $this->query($query) )
			return $this->insert_id();
		else
			return 0;
	}

	/**
	 * 更新表记录
	 *
	 * @param string $table 表名
	 * @param array $update_arr 要更新的字段（key为字段名，value为值）
	 * @param array $where 更新条件（key为字段名，value为值）
	 * @return int 成功返回1，失败返回0
	 */
	function query_update($table, $update_arr=array(), $where=array()){

		if( empty($update_arr) )
			return 0;

		$query = 'UPDATE ' . $table . ' SET ';

		foreach ($update_arr as $key => $value){
			if( is_numeric($value) )
				$query .= $key . '=' . $value . ',';
			else
				$query .= $key . '=' . "'" . $value . "',";
		}

		$query = substr($query, 0, -1);

		if( !empty($where) ){

			$query .= ' WHERE ';

			foreach ($where as $key => $value){
				if( is_numeric($value) )
					$query .= $key . '=' . $value . ',';
				else
					$query .= $key . '=' . "'" . $value . "',";
			}

			$query = substr($query, 0, -1);
		}
		// echo $query;
		if( $this->query($query) )
			return 1;
		else
			return 0;
	}
	
}
?>
