<?php
class IndexAction extends Action {
    public function index()
    {
    	$ad = D('Ad');
    	$gamea = D('Gamea');
    	$news = D('Article');
    	//首页banner
    	$adlist = $ad->getAdList('*','ad_open=1 AND c_id=1',5);
    	
    	//合作项目
    	$adlist1 = $ad->getAdList('*','ad_open=1 AND c_id=2',6);
    	
    	//合作商家
    	$adMembers = $ad->getAdList('*','ad_open=1 AND c_id=5',30);
    	
    	$newslist = $news->getArticleByCon('is_display=1',array('is_order'=>'DESC','add_time'=>'DESC'),4);
    	
    	$this->assign('banner',$adlist);
    	$this->assign('cooperation',$adlist1);
    	$this->assign('newslist',$newslist);
    	$this->assign('adMembers',$adMembers);
		$this->display();
    }
    //公司信息
    public function company()
    {
    	$this->display();
    }
    //皮乐会社介绍
    public function pierrot()
    {
    	$this->display();
    }
    //皮乐中国
    public function china()
    {
    	$m = D('Man');
    	$result = $m->getManLists('*',array('m_order'=>'ASC'));
    	$this->assign('result',$result);
    	$this->display();
    }
    //业务摘要
    public function business()
    {
    	$this->display();
    }
    public function process()
    {
    	$this->display();
    }
    public function server()
    {
    	$this->display();
    }
    public function protect()
    {
    	$this->display();
    }
    public function contact()
    {
    	$this->display();
    }
    public function employ()
    {
    	$info = D('Info');
    	$zp = $info->getInfoList('*',"i_c='zp'",1,array('i_id'=>'ASC'));
    	$this->assign('zp',$zp[0]);
    	$this->display();
    }
    public function clause()
    {
    	$info = D('Info');
    	$wr = $info->getInfoList('*',"i_c='wr'",1,array('i_id'=>'ASC'));
    	$this->assign('wr',$wr[0]);
    	$this->display();
    }
    /*
     *新闻列表
     */
    public function nlist()
    {
    	$news = D('Article');
    	$nlist = $news->getArticle('is_display=1',array('is_order'=>'DESC','add_time'=>'DESC'));
    	$this->assign('nlist',$nlist);
    	$this->display();
    }
    /*
     *新闻详情
    */
    public function ndetail()
    {
    	$news = D('Article');
    	$nid = intval($this->_get('nid'));
    	$detail = $news->getArticleById($nid);
    	$this->assign('detail',$detail);		
    	$this->display();
    }
    /*
     *作品列表详情
    */
    public function getCartoon()
    {
    	$gamea = D('Gamea');
    	$html = '';
    	$gamealist = $gamea->getGameaByCon('is_display=1 AND is_m=1',array('is_order'=>'DESC','add_time'=>'DESC'),5);
    	for($i=0;$i<count($gamealist);$i++)
    	{
    		$html.='<p><a href="'.$gamealist[$i]["ga_url"].'" target="_blank">'.$gamealist[$i]["ga_title"].'</a></p>';
    	}
    	echo $html;
    }
    public function more()
    {
		$ad = D('Ad');
    	
    	//年份作品
    	$adlist = $ad->getAdList('*','ad_open=1 AND c_id=4',100,array('add_time'=>'ASC'));
		
    	$gamea = D('Gamea');
    	$gamealist = $gamea->getGameaByCon('is_display=1 AND is_m=0',array('is_order'=>'DESC','add_time'=>'DESC'),10000);
    	$this->assign('glist',$gamealist);
		$this->assign('adlist',$adlist);
    	$this->display();
    }
    /*
     * CWF
     */
    public function cwf()
    {
    	$this->display();
    }
    public function adv()
    {
    	$adv = D('Adv');
    	$this->display();
    }
    public function addv()
    {
    	$adv = D('Adv');
    	$name = trim($this->_post('name'));
    	$mobile = trim($this->_post('mobile'));
    	$email = trim($this->_post('email'));
    	$content = $this->_post('content');
    	if(empty($name))
    	{
    		echo json_encode(array('error'=>0,'msn'=>'请输入正确的姓名'));die;
    	}
    	if(empty($mobile)&&empty($email))
    	{
    		echo json_encode(array('error'=>0,'msn'=>'email和手机号码 至少有一个需要正确输入'));die;
    	}
    	if(empty($content))
    	{
    		echo json_encode(array('error'=>0,'msn'=>'请输入正确的咨询内容 '));die;
    	}
    	$data['username'] = $name;
    	$data['mobile'] = $mobile;
    	$data['email'] = $email;
    	$data['content'] = $content;
    	$data['is_contact'] = 0;
    	$data['add_time'] = time();
    	$data['update_time'] = time();
    	$adv->addAdv($data);
    	echo json_encode(array('error'=>0,'msn'=>'发送成功,我们会尽快与您取得联系。'));die;
    }
    //作品列表 
    public function hyrz()
    {
    	$this->display();
    }
    public function hyrzjfz()
    {
    	$this->display();
    }
    public function creamy()
    {
    	$this->display();
    }
    public function djcz()
    {
    	$this->display();
    }
}