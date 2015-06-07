<?php
class GameaModel extends Model 
{
	protected $tableName = 'db_gamea';
	function getGameaByConPage($where,$order)
	{
		$where = 'is_display=1 and '.$where;
		import('ORG.Util.Page');
		$count      = $this->where($where)->count();
		$count = intval($count)>500?500:intval($count);
		$Page       = new Page($count,20);
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$list =  $this->where($where)->order($order)->page($nowPage.','.$Page->listRows)->select();
		$show       = $Page->show();
		return array('list'=>$list,'show'=>$show);
		//return $this->where($where)->order(array('add_time'=>'desc'))->limit(100)->select();
	}
	function getGameaByCon($where,$order,$limit)
	{
		return $this->where($where)->order($order)->limit($limit)->select();
	}
	function getGameaByConNoL($where,$order,$limit)
	{
		return $this->where($where)->order($order)->select();
	}
	function getGameaById($gaid)
	{
		return $this->where('ga_id='.$gaid." and is_display=1")->find();
	}
}