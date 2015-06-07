<?php
class ArticleModel extends Model 
{
	protected $tableName = 'db_news';
	function getArticle($where,$order)
	{
		import('ORG.Util.Page');
		$count      = $this->where($where)->count();
		$count = intval($count)>500?500:intval($count);
		$Page       = new Page($count,24);
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$list =  $this->where($where)->order($order)->page($nowPage.','.$Page->listRows)->select();
		$show       = $Page->show();
		return array('list'=>$list,'show'=>$show);
		//return $this->where($where)->order(array('add_time'=>'desc'))->limit(100)->select();
	}
	function getArticleByConPage($where,$order)
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
	function getArticleByCon($where,$order,$limit)
	{
		return $this->where($where)->order($order)->limit($limit)->select();
	}
	function getArticleById($nid)
	{
		return $this->where('n_id='.$nid." and is_display=1")->find();
	}
}