<?php

/**
 * 2011-11-03 by hukai
 */
defined('WLGMTOOL') or exit('Access Denied');

require_once('helper.class.php');
class Createhtml extends Helper{

	var $tablepre;
	var $category_id;
	var $num;
	var $pagesize = PAGES;
	var $pathhtml;
	var $pid;
    var $phpUrl;
    var $fileUrl;
    var $totalpage;
    var $sql;
    var $table = TABLE_ARTICLE;
    var $detail_array = array();
    var $detailpath;

	function Createhtml(){
		global $db;

		$this->db =& $db;
	}

	/**
	 * 生成平台新闻公司下列表页面
	 *
	 * @param unknown_type $category_id  类别ID
	 * 
	 */
	function create_news($category_id = 0){
		
		$this->category_id = $category_id;
        $this->sql = 'SELECT COUNT(1) FROM ' . $this->table .' WHERE status = 1 and show_pt = 1 ';
        if($category_id) 
        	$this->sql .= ' and category_id = '. $category_id;
        else
        	$this->sql .= ' and category_id in (8,10)';
        	 
        $this->pathhtml = HTMLURL ;
        
        $this->pagesize = PAGES;
        $this->phpUrl = PTURL . 'index.php?action=newslist&cid=' . $category_id;

        $this->create_listHtml();
	}

	/**
	 * 生成列表静态页面
	 *
	 * @return unknown
	 */
	function create_listHtml() {

            if( !$this->sql )
                return false;

            $this->num = $this->db->get_value($this->sql);
            $this->totalpage = ceil($this->num / $this->pagesize);

            if(!file_exists($this->pathhtml)) {
                mkdir($this->pathhtml, 0777);
            }

            $listpath = $this->pathhtml . '/list';

            if(!file_exists($listpath))			// 不存在目录，新建
                mkdir($listpath, 0777);
//            else								// 存在，删除目录下的所有文件
//            	delete_dir_files($listpath);
			if($this->category_id)
				$cd = $this->category_id . '_';
			else 
				$cd = '';
            for($i = 1;$i <= $this->totalpage; $i++){
					
                $this->fileUrl  = $listpath . '/list_'. $cd . $i . '.html';
                $this->phpUrl  .=  '&page='.$i;

                $this->create();
            }
	}

		/**
		 * 生成文件
		 *
		 * @return bool
		 */
        function create(){
            if( !$this->phpUrl || !$this->fileUrl )
                return false;

            $data = $this->curl_file_get_contents($this->phpUrl);
            file_put_contents($this->fileUrl, $data);
			chmod($this->fileUrl , 0777);
        }

        /**
         * 生成详情静态页面
         *
         * @return bool 没有详情数据返回false
         */
        function create_details(){

            if( empty($this->detail_array) )
                return false;

            if(!file_exists($this->pathhtml))
                mkdir($this->pathhtml, 0777);

            if(!file_exists($this->detailpath))				// 不存在目录，新建
                mkdir($this->detailpath, 0777);
//            else 											// 存在，删除目录下的所有文件
//            	delete_dir_files($this->detailpath);

            foreach( $this->detail_array as $value){
				$this->phpUrl	= $value['phpUrl'];
				$this->fileUrl	= $value['fileUrl'];

				$this->create();
            }
        }

         /**
          * 生成平台新闻公告下的所有详情页面
          *
          * @param int $category_id  类别ID 
          */
        function create_news_detail($category_id){

        	// 分类路径和detail路径
        	$this->pathhtml 	= HTMLURL ;
        	$this->detailpath	= $this->pathhtml . '/detail';

            $sql  = 'SELECT article_id FROM ' . $this->table . ' WHERE status = 1 and show_pt = 1 ';
            
            if($category_id) 
	        	$sql .= ' and category_id = '. $category_id;
            else
	        	$sql .= ' and category_id in (7,8,9,10)';
	        	
            $sql .= '  ORDER BY article_id DESC';
            $query = $this->db->query($sql);

          	$result = array();
          	while ($res = $this->db->fetch_array($query)){
          		$phpUrl 	= PTURL . 'index.php?action=newsdetail&id=' . $res['article_id'];
          		$fileUrl	= $this->detailpath . '/' . $res['article_id'] . '.html';

          		$result[] = array('phpUrl' => $phpUrl, 'fileUrl' => $fileUrl);
          	}

            $this->db->free_result($query);
			
			$this->detail_array = $result;

			$this->create_details();
        }

        /**
         * 生成首页
         *
         */
        function create_index(){
        	
        	$this->phpUrl	= PTURL . 'index.php?action=index';
        	$this->fileUrl	= ROOT_DIR . 'index.html';

        	$this->create();
        }


        /**
         * 生成一个活动页
         *
         * @param int $id 新闻id
         * @param int $category_id  类别ID
         * @param int $list 是否充许生成列表，如果仅仅是生成一篇文章则不需要生成列表
         */
        function create_one_news($id, $category_id , $list = 1){
        	// 重新生成该分类列表页
        	if($list)
        		$this->create_news($category_id);

        	// 生成详情页面
        	$this->phpUrl 	= PTURL . 'index.php?action=newsdetail&id=' . $id;
          	$this->fileUrl	= HTMLURL . 'detail/';
          	
			if(!file_exists($this->fileUrl))
                mkdir($this->fileUrl, 0777);
                
            $this->fileUrl	.= $id . '.html';
                
          	$this->create();
        }
        
        /**
         * 删除这篇文章
         *
         * @param unknown_type $id
         */
        function del_Html($id) {
        	
        	$this->fileUrl = HTMLURL . 'detail/'. $id . '.html';
        	@unlink($this->fileUrl);
        	
        }
        
        /**
         * 根据用户选择的游戏来选择生成静态页面
         *  生成这个类别下的静态文件
         * @param unknown_type $game_id      游戏ID
         * @param unknown_type $category_id  分类ID
         * $id 该新闻的ID
         */
        
        function select_Create_WebHtml($game_id , $category_id , $id) {
        	
        	$sql = 'select game_html,game_url from '.TABLE_GAMECAT.' where id = '. $game_id ;
        	$allow = $this->db->get_one($sql);
			// var_dump($allow);
        	if($allow['game_html'] && $allow['game_url']) {  //允许生成静态页  生成游戏官网接口

        		$this->curl_file_get_contents($allow['game_url'] . 'index.php?action=index_createindex');  //首页
        		$this->curl_file_get_contents($allow['game_url'] . 'index.php?action=index_createonenews&id='. $id .'&type=' . $category_id);  //详细页
        		
        	}
        	
        	// echo $allow['game_url'];
			
			// echo $game_id . $category_id . $id;
	// exit();
	
        	// 不允生成静态页，如果是添加或修改的新闻或公告，则要生成平台的游戏大厅
        	if($category_id == 8 || $category_id == 10) {
        		$this->create_index();   //更新首页
				$this->create_one_news($id , $category_id); //生成这篇新闻静态页，该类别下的列表
				$this->create_news(0);                //生成所有列表
        	} elseif($category_id == 7 || $category_id == 11) {
        		$this->create_one_news($id , $category_id , 0);
        	} elseif($category_id == 9) {
        		$this->create_index();
				$this->create_news(0);
				$this->create_news(8);
				$this->create_news(10);
				$this->create_news_detail(0);
        	}else{	
				$this->create_one_news($id , $category_id); //生成这篇新闻静态页，该类别下的列表
			}
			
				// $this->create_news_detail(0);
			
        }
        /**
         * 删除静态文件
         *
         * @param unknown_type $game_id
         * @param unknown_type $category_id
         * @param unknown_type $id
         */
        function del_WebHtml($game_id , $category_id , $id) {
        	
        	//先要删除游戏官网上的静态文件
        	//file_get_content .....  留好接口
        	
        	//删除平台数据
        	if($category_id == 8 || $category_id == 10) {
        		$this->del_Html($id);
        		$this->create_index();
        		$this->create_news(0);
        		$this->create_news($category_id);
        	} elseif($category_id == 7 || $category_id == 9) {
        		$this->del_Html($id);
        	}
        	
        }
        
        /**
         * 添加友情链接时生成静态页面
         *  生成游戏官网的首页
         */
        function create_Links($gameid) {
        	
        	if($gameid) {  //具体某个游戏的生成，指首页
        		$link_cache = require(CACHE_DIR . 'game_'.$gameid . '_config.php');
        		
        		if($link_cache['game_html'])  { //如果允许生成静态页面，则开始生成首页
        			
        		}
        		
        	}
        	// 生成平台静态页面
        	$this->create_index();
        	
        }
        
        /**
         * 生成游戏列表缓存
         *
         * @param unknown_type $id
         * @param unknown_type $game_open
         */
        function create_GameCat($id = 0 , $game_pid = 0 , $game_open) {
        	
        	if($id > 0) {
	        	$sql = 'select a.*,b.category_name from '. TABLE_GAMECAT .' a left join '.TABLE_DB_CATEGORY.' b on a.game_cat = b.category_id  where a.id =' . $id; //取出父级ID，看是否是顶级
	        	$res = $this->db->get_one($sql);
	        	$pid = $res['game_pid'];
	        	if($res['game_pid'] <> 0) {  // 如果有父级，则处理
	        		unset($res);
	        		$sql = 'select * from '. TABLE_GAMECAT .' where id = '. $pid ;
	        		$res = $this->db->get_one($sql);
	        		$gid = $res['id'];
	        		$sql = 'select * from '. TABLE_GAMECAT .' where game_pid = '. $pid;
	        		$r = $this->db->query($sql);
	        		$res['sub'] = $this->db->array_num($r);
	        	} else { // 如果没有父级，则直接取出同类游戏
	        		$gid = $id;
	        		$sql = 'select * from '. TABLE_GAMECAT .' where game_pid = '. $id;
	        		$r = $this->db->query($sql);
	        		$res['sub'] = $this->db->array_num($r);
	        	}
				
	        	if($res['game_url'] && $res['game_html']) {
					
	        		$this->curl_file_get_contents($res['game_url'] . 'index.php?action=index_cache&id='. $id);  //首页
	        	}
	        	
	        	//生成平台游戏缓存
	        	require_once(CLASS_DIR . 'cache.class.php');
	        	$cache = new File_Cache();
	        	$cache->file_path = CACHE_DIR .'game_'.$gid.'_config.php';
				$cache->cache_data = $res;
				$cache->create_cache_file();
				
				$sql = 'select a.*,b.category_name from ' . TABLE_GAMECAT . ' a left join '.TABLE_DB_CATEGORY.' b on a.game_cat = b.category_id where a.game_pid = 0 and a.game_open = 1 order by a.game_list_order desc';
	        	$g = $this->db->query($sql);
	        	$gs = $this->db->array_num($g);
	        	
				$cache->file_path = CACHE_DIR . 'game_config.php';
				$cache->cache_data = $gs ;
				$cache->create_cache_file();
	        	// 生成完成
	        	$this->create_index();
        	} else {
				require_once(CLASS_DIR . 'cache.class.php');
	        	$cache = new File_Cache();
	        	$sql = 'select * from ' . TABLE_GAMECAT . ' where game_pid = 0 and game_open = 1 order by id desc';
	        	$g = $this->db->query($sql);
	        	$gs = $this->db->array_num($g);
	        	
				$cache->file_path = CACHE_DIR . 'game_config.php';
				$cache->cache_data = $gs ;
				$cache->create_cache_file();
	        	// 生成完成
	        	$this->create_index();
        	}
        }
}