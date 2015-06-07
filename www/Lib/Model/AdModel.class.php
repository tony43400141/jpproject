<?php
class AdModel extends Model
{
	protected $tableName = 'db_ad';
	function getAdList($field='*',$where="",$limit=3,$order=array('update_time'=>'desc'))
	{
		return $this->field($field)->where($where)->order($order)->limit($limit)->select();
	}
}