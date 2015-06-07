<?php

class db_mssql
{
	var $querynum = 0;
	var $connid = 0;
	var $insertid = 0;
	var $cursor = 0;

	function db_mssql($dbhost = 'localhost', $dbuser, $dbpw, $dbname, $pconnect = 0)
	{
//		$func = $pconnect == 1 ? 'mssql_pconnect' : 'mssql_connect';
		if(!$this->connid = mssql_connect($dbhost, $dbuser, $dbpw))
		{
        	$this->halt('Can not connect to MsSQL server');
		}
		if($dbname)
		{
			if(!@mssql_select_db($dbname , $this->connid))
			{
				$this->halt('Cannot use database '.$dbname);
			}
		}
		return $this->connid;
	}

	function select_db($dbname)
	{
		return mssql_select_db($dbname , $this->connid);
	}

	function query($sql, $type = '', $expires = 3600, $dbname = '')
	{
		$this->querynum++;
		$sql = trim($sql);
		if(preg_match("/^(select.*)limit ([0-9]+)(,([0-9]+))?$/i", $sql, $matchs))
		{
			$sql = $matchs[1];
			$offset = $matchs[2];
			$pagesize = $matchs[4];
			$query = mssql_query($sql, $this->connid) or $this->halt('MsSQL Query Error', $sql);
			return $this->limit($query, $offset, $pagesize);
		}
		elseif(preg_match("/^insert into/i", $sql))
		{
			$sql = "$sql; SELECT @@identity as insertid";
			$query = mssql_query($sql, $this->connid) or $this->halt('MsSQL Query Error', $sql);
			$insid = $this->fetch_row($query);
			$this->insertid = $insid[0];
			return $query;
		}
		else
		{
			$query = mssql_query($sql, $this->connid) or $this->halt('MsSQL Query Error', $sql);
			return $query;
		}
	}

	function get_one($sql, $type = '', $expires = 3600, $dbname = '')
	{
		$query = $this->query($sql, $type, $expires, $dbname);
		$rs = $this->fetch_array($query);
		$this->free_result($query);
		return $rs ;
	}

	function fetch_array($query, $type = MSSQL_ASSOC)
	{
		if(is_resource($query)) return mssql_fetch_array($query, $type);
		if($this->cursor < count($query))
		{
			return $query[$this->cursor++];
		}
		return FALSE;
	}

	function array_num($query,$result_type = MSSQL_ASSOC)
	{
    $result = array();
		while ($row = mssql_fetch_array($query,$result_type))
			{
				$result[] = $row;
			}
		return $result;
	}

	function affected_rows()
	{
		return mssql_rows_affected($this->connid);
	}

	function num_rows($query)
	{
		return is_array($query) ? count($query) : mssql_num_rows($query);
	}

	function num_fields($query)
	{
		return mssql_num_fields($query);
	}

	function result($query, $row)
	{
		return @mssql_result($query, $row);
	}

	function free_result($query)
	{
		if(is_resource($query)) mssql_free_result($query);
	}

	function insert_id()
	{
		return $this->insertid;
	}

	function fetch_row($query)
	{
		return mssql_fetch_row($query);
	}

	function close()
	{
		return mssql_close($this->connid);
	}

	function error()
	{
		return TRUE;
	}

	function errno()
	{
		return TRUE;
	}

	function halt($message = '', $sql = '')
	{
		exit("MsSQL Query:$sql <br> MsSQL Error:".$this->error()." <br> MsSQL Errno:".$this->errno()." <br> Message:$message");
		exit("MsSQL error");
	}

	function limit($query, $offset, $pagesize = 0)
	{
		if($pagesize > 0)
		{
			mssql_data_seek($query, $offset);
		}
		else
		{
			$pagesize = $offset;
		}
		$info = array();
		for($i = 0; $i < $pagesize; $i++)
		{
			$r = $this->fetch_array($query);
			if(!$r) break;
			$info[] = $r;
		}
		$this->free_result($query);
		$this->cursor = 0;
		return $info;
	}

	function _fetch_one_value($query){
		$result = $this->fetch_row($query);
		return $result[0];
	}

	function _sp_call($sp_name, $param=array(), $return='all', $type=SQLVARCHAR){
		global $debug;

		// sp不存在
		if( !$sp_name )
			return false;

		// 初始化
		$ms_sp = mssql_init($sp_name, $this->connid);

		// 传值
		if( !empty($param) ){
			foreach ($param as $key => $value){
				mssql_bind($ms_sp, "@$key", $value, $type);

				if( $debug )
					$_SESSION['ms_sp'][$sp_name]['param'][] = array('name'=>$key, 'value'=>$value);
			}
		}

		// 执行SP
		$query = mssql_execute($ms_sp, false);

		// 获取数据
		if( $return == 'value' )
			$result = $this->_fetch_one_value($query);
		else
			$result = $this->array_num($query);

		// 释放资源
		$this->free_result($query);
		mssql_free_statement($ms_sp);

		return $result;
	}
}
?>