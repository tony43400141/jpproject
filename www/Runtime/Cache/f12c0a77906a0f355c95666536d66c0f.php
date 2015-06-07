<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>__WEBN__-首页</title><link rel="stylesheet" type="text/css" href="__CSS__css.css" /><script type="text/javascript" src="__JS__jquery.min.js"></script></head><body><div class="allbg"><!------------框架-------------><div class="wrapper"><!------------幻灯片js-------------><script type="text/javascript" src="__JS__slidejs.js"></script><!------------幻灯片-------------><div id="focus"><ul><?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["ad_web"]); ?>" target="_blank"><img src="/<?php echo ($vo["ad_pic"]); ?>" alt="" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul></div><!------------内容-------------><div class="main"><!------------左侧-------------><div class="left fl"><!------------logo-------------><div class="logo"><img src="__IMG__images/logo.jpg" /></div><!------------导航-------------><div class="nav white"><ul><li><h2 class="aa"><a href="/">首页 ></a></h2></li><li><h2 class="bb">公司信息</h2><div class="menu chengse"><a href="<?php echo U('index/company');?>">Pierrot 株式会社</a><a href="<?php echo U('index/china');?>">Pierrot China</a></div></li><li><h2  class="cc">公司业务摘要</h2><div class="menu meise"><p><a href="<?php echo U('index/business');?>">公司业务介绍</a></p><p><a href="<?php echo U('index/process');?>">授权流程</a></p><p><a href="<?php echo U('index/server');?>">授后服务</a></p><p><a href="<?php echo U('index/protect');?>">版权保护</a></p></div></li><li><h2  class="dd">作品信息</h2><div class="menu lvse" id="cartoon"><p><a href="<?php echo U('index/more');?>">更多作品</a></p></div></li><li><h2 class="ee">联系我们</h2><div class="menu lanse"><p><a href="<?php echo U('index/contact');?>">联系方式</a></p></div><!------------微博微信-------------><div class="weixin"><div class=" gz fl"><p><a href="http://weibo.com/5017523178/profile?topnav=1&wvr=5" target="_blank"><img src="__IMG__images/wb-xl.jpg" /></a></p><p><a href="http://t.qq.com/pierrotchina?preview" target="_blank"><img src="__IMG__images/wb-tx.jpg" /></a></p></div><div class="ewm fr"><img src="__IMG__images/ewm.jpg" style="width:66px;height:66px;"  onmouseover="ShowFloatingImage(this, 150, 150);" /></div></div></li></ul></div><script language='javascript'>$(function(){
	$.ajax({
			   type:'get',
			   url:'/index/getCartoon',
			   cache:false,
			   success:function(data)
			   {
				  var _t = $('#cartoon').html(); 
				 $('#cartoon').html(data+_t);   
			   }
		});
	});

</script><script>function GetAbsPosition(obj)
{
      var curleft = 0, curtop = 0;
      do {
      curleft += obj.offsetLeft;
      curtop += obj.offsetTop;
      } while (obj = obj.offsetParent);
      return [curleft,curtop];       
}

function ShowFloatingImage(image, width, height)
{
    var id = "trailimageid";
    var newdiv = document.getElementById(id);
    if(newdiv == null)
    {
      newdiv = document.createElement('div');
      newdiv.setAttribute('id',id);
      newdiv.setAttribute('onmouseout', "HideElement('"+id+"');");
      document.body.appendChild(newdiv);
    }
    newdiv.innerHTML = '<img src='+image.src+ ' width='+(image.width + width) + ' height=' + (image.height + height) + ' />';

    var absPos = GetAbsPosition(image);
    newdiv.style.position = "absolute";        
    newdiv.style.posLeft = absPos[0] - width/2;
    newdiv.style.posTop = absPos[1] - height/2-75;
    newdiv.style.display="block";
}

function HideElement(id)
{
    var elem = document.getElementById(id);
    elem.style.display="none";
}

</script></div><!------------右侧-------------><div class="right fr"><!------------最新信息-------------><div class="new fl"><div class="title"><div class="st fl"></div><h2 style="color:#009999;">最新信息</h2><div class="xx fr"></div></div><div class="newmain fl gray"><ul><?php if(is_array($newslist)): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($i < 5): ?><li><div class="newimg"><a href="<?php echo U('Index/ndetail',array('nid'=>$vo['n_id']));?>" target="_blank"><img src="<?php echo ($vo["n_pic"]); ?>" style=" width:90px; height:90px;"/></a></div><div class="newnr"><h2 class="blun"><a href="<?php echo U('Index/ndetail',array('nid'=>$vo['n_id']));?>" target="_blank"><?php echo (mb_substr($vo['n_title'],0,14,'UTF-8')); ?></a></h2><span><?php echo (date("Y.m.d",$vo['add_time'])); ?></span><p><a href="<?php echo U('Index/ndetail',array('nid'=>$vo['n_id']));?>" target="_blank"><?php echo (mb_substr($vo['n_sum'],0,41,'UTF-8')); ?></a></p></div></li><?php else: ?><li><h2 class="blun"><a href="<?php echo U('Index/ndetail',array('nid'=>$vo['n_id']));?>" target="_blank"><?php echo (mb_substr($vo['n_title'],0,23,'UTF-8')); ?></a></h2><span><?php echo (date("Y.m.d",$vo['add_time'])); ?></span></li><?php endif; endforeach; endif; else: echo "" ;endif; ?></ul><div class="newmore fl"><a href="<?php echo U('Index/nlist');?>" target="_blank">查看全部 ></a></div></div></div><div class="clear"></div><!------------合作项目-------------><div class="hzxm fl"><div class="title"><div class="hzxmst fl"></div><h2 style="color:#186fcb;">我们的作品</h2><div class="xx fr" style="width:520px;"></div></div><div class="hzxmmain"><?php if(is_array($cooperation)): $i = 0; $__LIST__ = $cooperation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["ad_web"]); ?>" target="_blank"><img src="/<?php echo ($vo["ad_pic"]); ?>" /></a><?php endforeach; endif; else: echo "" ;endif; ?></ul></div></div></div></div><!------------底部版权-------------><div class="footer fr white"><a href="/" target="_blank">首页</a><a href="<?php echo U('index/company');?>" target="_blank">公司信息</a><a href="<?php echo U('index/business');?>" target="_blank">公司业务摘要</a><a href="<?php echo U('index/more');?>" target="_blank">作品信息</a><a href="<?php echo U('index/employ');?>" target="_blank">招聘信息</a><a href="<?php echo U('index/clause');?>" target="_blank">网站使用条款</a><a href="<?php echo U('index/adv');?>" target="_blank">咨询及申请</a><p>© 2014. Pierrot China版权所有 <a href="http://www.miitbeian.gov.cn" target="_blank" style="float:none;">沪ICP备14018648号-1</a></p></div></div></div></body></html>