<?php
class IpModel extends Model 
{
	protected $tableName = 'ip_forbid_list';
	function getIps($where='isopen=1')
	{
		return  $this->where($where)->order(array('ip_id'=>'desc'))->select(); 
	}
	
}