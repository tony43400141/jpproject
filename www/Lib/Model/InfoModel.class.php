<?php
class InfoModel extends Model
{
	protected $tableName = 'db_info';
	function getInfoList($field='*',$where="",$limit=3,$order=array('update_time'=>'desc'))
	{
		return $this->field($field)->where($where)->order($order)->limit($limit)->select();
	}
}