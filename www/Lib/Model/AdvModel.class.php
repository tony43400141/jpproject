<?php
class AdvModel extends Model
{
	protected $tableName = 'db_advice';
	function getAdList($field='*',$where="",$limit=3,$order=array('update_time'=>'desc'))
	{
		return $this->field($field)->where($where)->order($order)->limit($limit)->select();
	}
	function addAdv($data)
	{
		return $this->data($data)->add();
	}
}