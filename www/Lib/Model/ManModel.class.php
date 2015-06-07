<?php
class ManModel extends Model
{
	protected $tableName = 'db_manger';
	function getManList($field='*',$where="",$limit=3,$order=array('update_time'=>'desc'))
	{
		return $this->field($field)->where($where)->order($order)->limit($limit)->select();
	}
	function getManLists($field='*',$order=array('update_time'=>'desc'))
	{
		return $this->field($field)->order($order)->select();
	}
}