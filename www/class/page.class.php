<?php
class page
{
 /**
  * config ,public
  */
 var $page_name="page";//page标签，用来控制url页。比如说xxx.php?PB_page=2中的PB_page
 var $next_page='<img src="images/page_next.gif" border="0">';//下一页
 var $pre_page='<img src="images/page_preview.gif" border="0">';//上一页
 var $first_page='<img src="images/page_first.gif" border="0">';//首页
 var $last_page='<img src="images/page_over.gif" border="0">';//尾页
 var $pre_bar='<img src="images/page_preview.gif" border="0">';//上一分页条
 var $next_bar='<img src="images/page_next.gif" border="0">';//下一分页条
 var $format_left='';
 var $format_right='';
 var $is_ajax=false;//是否支持AJAX分页模式
 var $pro = '' ;
 
 var $with_back  = 10;
 /**
  * private
  *
  */
 var $pagebarnum=5;//控制记录条的个数。
 var $totalpage=0;//总页数
 var $m_pagecount=0;//总页数
 var $ajax_action_name='';//AJAX动作名
 var $nowindex=1;//当前页
 var $m_page=1;//当前页
 var $url="";//url地址头
 var $total=""; //数据总数
 var $perpage=""; //每个页面数据量
 var $offset=0;
 /**
  * constructor构造函数
  *
  * @param array $array['total'],$array['perpage'],$array['nowindex'],$array['url'],$array['ajax']...
  */
 function page($array)
 {
  if(is_array($array)){
     if(!array_key_exists('total',$array))$this->error(__FUNCTION__,'need a param of total');
     $total=intval($array['total']);
	 $this->total = $total;
     $perpage=(array_key_exists('perpage',$array))?intval($array['perpage']):10;
		$this->perpage = $perpage;
	$nowindex=(array_key_exists('nowindex',$array))?intval($array['nowindex']):'';
	$this->nowindex = $nowindex;
	$this->m_page = $nowindex;
     $url=(array_key_exists('url',$array))?$array['url']:'';
	 $this->pro = (array_key_exists('pro',$array))?$array['pro']:'';
  }else{
     $total=$array;
     $perpage=10;
     $nowindex='';
     $url='';
  }
  if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');
  if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
  if(!empty($array['page_name']))$this->set('page_name',$array['page_name']);//设置pagename
  $this->_set_nowindex($nowindex);//设置当前页
  $this->_set_url($url);//设置链接地址
  $this->totalpage=ceil($total/$perpage);
  $this->m_pagecount = $this->totalpage;
  $this->offset=($this->nowindex-1)*$perpage;
  if(!empty($array['ajax']))$this->open_ajax($array['ajax']);//打开AJAX模式
 }
 /**
  * 设定类中指定变量名的值，如果改变量不属于这个类，将throw一个exception
  *
  * @param string $var
  * @param string $value
  */
 function set($var,$value)
 {
  if(in_array($var,get_object_vars($this)))
     $this->$var=$value;
  else {
   $this->error(__FUNCTION__,$var." does not belong to PB_Page!");
  }
 }
 /**
  * 打开倒AJAX模式
  *
  * @param string $action 默认ajax触发的动作。
  */
 function open_ajax($action)
 {
  $this->is_ajax=true;
  $this->ajax_action_name=$action;
 }
 /**
  * 获取显示"下一页"的代码
  *
  * @param string $style
  * @return string
  */
 function next_page($style='')
 {
  if($this->nowindex<$this->totalpage){
   return $this->_get_link($this->_get_url($this->nowindex+1),$this->next_page,$style);
  }
  return '<span class=disabled>'.$this->next_page.'</span>';
 }
 /**
  * 获取显示“上一页”的代码
  *
  * @param string $style
  * @return string
  */
 function pre_page($style='')
 {
  if($this->nowindex>1){
   return $this->_get_link($this->_get_url($this->nowindex-1),$this->pre_page,$style);
  }
  return '<span class=disabled>'.$this->pre_page.'</span>';
 }
 /**
  * 获取显示“首页”的代码
  *
  * @return string
  */
 function first_page($style='')
 {
  if($this->nowindex==1){
      return '<span class="'.$style.'">'.$this->first_page.'</span>';
  }
  return $this->_get_link($this->_get_url(1),$this->first_page,$style);
 }
 /**
  * 获取显示“尾页”的代码
  *
  * @return string
  */
 function last_page($style='')
 {
  if($this->nowindex==$this->totalpage){
      return '<span class="'.$style.'">'.$this->last_page.'</span>';
  }
  return $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style);
 }
 function nowbar($style='',$nowindex_style='')
 {
  $plus=ceil($this->pagebarnum/2);
  if($this->pagebarnum-$plus+$this->nowindex>$this->totalpage)$plus=($this->pagebarnum-$this->totalpage+$this->nowindex);
  $begin=$this->nowindex-$plus+1;
  $begin=($begin>=1)?$begin:1;
  $return='';
  for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
  {
   if($i<=$this->totalpage){
    if($i!=$this->nowindex)
        $return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
    else
        $return.=$this->_get_text("<span class=current>".$i."</span>");
   }else{
    break;
   }
   $return.="\n";
  }
  unset($begin);
  return $return;
 }
 /**
  * 获取显示跳转按钮的代码
  *
  * @return string
  */
 function select()
 {
   $return='<select name="PB_Page_Select">';
  for($i=1;$i<=$this->totalpage;$i++)
  {
   if($i==$this->nowindex){
    $return.='<option value="'.$i.'" selected>'.$i.'</option>';
   }else{
    $return.='<option value="'.$i.'">'.$i.'</option>';
   }
  }
  unset($i);
  $return.='</select>';
  return $return;
 }
 /**
  * 获取mysql 语句中limit需要的值
  *
  * @return string
  */
 function offset()
 {
  return $this->offset;
 }
 /**
  * 控制分页显示风格（你可以增加相应的风格）
  *
  * @param int $mode
  * @return string
  */
 function show($mode=1)
 {
  switch ($mode)
  {
   case '1':
    // $this->next_page='Next  > ';
    // $this->pre_page=' <  Prev';
    return $this->pre_page().$this->nowbar().$this->next_page();
    break;
   case '2':
    // $this->next_page='下一页';
    // $this->pre_page='上一页';
    // $this->first_page='首页';
    // $this->last_page='尾页';
    return $this->first_page().$this->pre_page().'[第'.$this->nowindex.'页]'.$this->next_page().$this->last_page().'第'.$this->select().'页';
    break;
   case '3':
    // $this->next_page='下一页';
    // $this->pre_page='上一页';
    // $this->first_page='首页';
    // $this->last_page='尾页';
    return $this->first_page().$this->pre_page().$this->next_page().$this->last_page();
    break;
   case '4':
    // $this->next_page='下一页';
    // $this->pre_page='上一页';
    return $this->pre_page().$this->nowbar().$this->next_page();
    break;
   case '5':
    return $this->pre_bar.$this->pre_page().$this->nowbar().$this->next_page().$this->next_bar;
    break;
  }
 }
 
 function begin()
	{
		return 1;
	}
	function end()
	{
		if($this->m_pagecount==0)
			$end = 1;
		else
			$end = $this->m_pagecount;
		return $end;
	}
	function pre()
	{

		if($this->m_pagecount==0 || $this->m_page == 1 )
			$pre = 1;
		else
			$pre = $this->m_page - 1;
		return $pre;
	}
	function next()
	{
		if($this->m_pagecount==0)
			$next = 1;
		elseif($this->m_pagecount==$this->m_page)
			$next = $this->m_pagecount;
		else
			$next = $this->m_page + 1;
		return $next;
	}
	
 function genHtml($baseLink, $pageFont="") {

		$firstop = strpos($baseLink, "?")<0?"?":"&";
		
		if($this->with_back == 10){
		
			$pages = "";
			$pages .="<div style=\"float:right;\">";
			$pages.="<div style=\"float:left;\">第 ".$this->nowindex."/".$this->totalpage." 页（共 ".$this->total." 条）&nbsp;每页".$this->perpage."条</div>";
			$pages.= "<div style=\"margin-left:5px;margin-right:5px;float:left;\"><a href=".$baseLink.$firstop."page=".$this->begin()." title=\"首页\"><font face=webdings><img src=\"images/page_first.gif\" border=\"0\"></font></a></div><div style=\"margin-left:5px;margin-right:5px;float:left;\"><a href=".$baseLink.$firstop."page=".$this->pre()." title=\"上 ".$this->perpage." 页\"><font face=webdings><img src=\"images/page_preview.gif\" border=\"0\"></font></a></div>";
			$pages .="";
			if($this->totalpage < 10) {
				$k = 1;
				$j = $this->totalpage;
			} else {
				$m = intval(($this->nowindex-1)/10);
				$k = $m*10+1;
				$j = $m*10 + 10;
			}
	
			for($i = $k; $i <= $j && $i<=$this->totalpage; $i++) {
				if($i==$this->nowindex ) {
					$pages.= "<div style=\"margin-left:5px;margin-right:5px;float:left;padding-left:4px;padding-right:6px;width:5px;border: #5fa6b6 1px solid;\"><a href=".$baseLink.$firstop."page=$i"."><font color=\"red\"><b>$i</b></font></a></div>";
				} else {
					$pages.= "<div style=\"margin-left:5px;margin-right:5px;float:left;padding-left:4px;padding-right:6px;width:5px;border: #5fa6b6 1px solid;\"><a href=".$baseLink.$firstop."page=$i>$i</a></div>";
				}
			}
			$pages .="";
			$pages.= "<div style=\"margin-left:5px;margin-right:5px;float:left;\"><a href=".$baseLink.$firstop."page=".$this->next()." title=\"下 ".$this->perpage." 页\"><font face=webdings><img src=\"images/page_next.gif\" border=\"0\"></font></a></div><div style=\"margin-left:5px;margin-right:5px;float:left;\"><a href=".$baseLink.$firstop."page=".$this->end()." title=\"尾页\"><font face=webdings><img src=\"images/page_over.gif\" border=\"0\"></font></a></div>";
			$pages .="</div>";
			
			return $pages;
		}elseif($this->with_back == 1){
			if($this->totalpage > 1) {
				$pages = "";
				$pages.= "<a href=".$baseLink.$firstop."page=".$this->first()." title=\"首页\">首页</a>&nbsp;<a href=".$baseLink.$firstop."page=".$this->pre()." title=\"上一页\">上一页</a>";
				$pages.= "&nbsp;<a href=".$baseLink.$firstop."page=".$this->next()." title=\"下一页\">下一页</a>&nbsp;<a href=".$baseLink.$firstop."page=".$this->end()." title=\"尾页\">尾页</a>&nbsp;页:<strong><font color=red>".$this->m_page."</font>/".$this->totalpage."</strong>";			
				$pages.="&nbsp;<input type='text' size='4' name='pageno' id='pageno' class='pageinput'>&nbsp;<input type='button' value='GO' class='pagebutton' onclick='if(!isNaN(document.getElementById(\"pageno\").value)){window.location=\"".$baseLink.$firstop."page=\"+document.getElementById(\"pageno\").value;}else{alert(\"请输入半角数字\")}'>";	
			} else {
				$pages = "";
				$pages.= "首页&nbsp;上一页";
				$pages.= "&nbsp;下一页&nbsp;尾页&nbsp;页:<strong><font color=red>".$this->nowindex."</font>/".$this->totalpage."</strong>";			
				$pages.="&nbsp;<input type='text' size='4' name='pageno' id='pageno' class='pageinput'>&nbsp;<input type='button' value='GO' class='pagebutton' onclick='if(!isNaN(document.getElementById(\"pageno\").value)){window.location=\"".$baseLink.$firstop."page=\"+document.getElementById(\"pageno\").value;}else{alert(\"请输入半角数字\")}'>";	
			}
			return $pages;
		}

		return $pages;
	}
	
	
/*----------------private function (私有方法)-----------------------------------------------------------*/
 /**
  * 设置url头地址
  * @param: String $url
  * @return boolean
  */
 function _set_url($url="")
 {
  if(!empty($url) && $this->pro <> 'show'){
      //手动设置
   $this->url=$url.((stristr($url,'?'))?'&':'?').$this->page_name."=";
  }else{
  	$this->url = $url;
  }//end if
 }
 /**
  * 设置当前页面
  *
  */
 function _set_nowindex($nowindex)
 {
  if(empty($nowindex)){
   //系统获取
   if(isset($_GET[$this->page_name])){
    $this->nowindex=intval($_GET[$this->page_name]);
   }
  }else{
      //手动设置
   $this->nowindex=intval($nowindex);
  }
 }
 /**
  * 为指定的页面返回地址值
  *
  * @param int $pageno
  * @return string $url
  */
 function _get_url($pageno=1)
 {
	 if($this->pro == "show") {
	  	return $this->url."_".$pageno.".html";
	  } else {
	 	 return $this->url.$pageno;
	  }
 }
 /**
  * 获取分页显示文字，比如说默认情况下_get_text('<a href="">1</a>')将返回[<a href="">1</a>]
  *
  * @param String $str
  * @return string $url
  */
 function _get_text($str)
 {
  return $this->format_left.$str.$this->format_right;
 }
 /**
   * 获取链接地址
 */
 function _get_link($url,$text,$style=''){
  $style=(empty($style))?'':'class="'.$style.'"';
  if($this->is_ajax){
      //如果是使用AJAX模式
   return '<a '.$style.' href="javascript:'.$this->ajax_action_name.'(\''.$url.'\')">'.$text.'</a>';
  }else{
   return '<a '.$style.' href="'.$url.'">'.$text.'</a>';
  }
 }
 /**
   * 出错处理方式
 */
function error($function,$errormsg)
{
 die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
}
}
class mypage extends page
{
    function mypage($array)
   {
       parent::page($array);
      $this->first_page=1;
      $this->last_page=$this->totalpage;
   }
}
