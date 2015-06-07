<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>__WEBN__-创始人寄语</title><link rel="stylesheet" type="text/css" href="__CSS__css.css" /><script type="text/javascript" src="__JS__jquery.min.js"></script></head><body><div class="allbg"><!------------框架-------------><div class="nywrapper"><!------------左侧-------------><div class="left nynav fl"><!------------logo-------------><div class="logo"><img src="__IMG__images/logo.jpg" /></div><!------------导航-------------><div class="nav white"><ul><li><h2 class="aa"><a href="/">首页 ></a></h2></li><li><h2 class="bb">公司信息</h2><div class="menu chengse"><a href="<?php echo U('index/company');?>">Pierrot 株式会社</a><a href="<?php echo U('index/china');?>">Pierrot China</a></div></li><li><h2  class="cc">公司业务摘要</h2><div class="menu meise"><p><a href="<?php echo U('index/business');?>">公司业务介绍</a></p><p><a href="<?php echo U('index/process');?>">授权流程</a></p><p><a href="<?php echo U('index/server');?>">授后服务</a></p><p><a href="<?php echo U('index/protect');?>">版权保护</a></p></div></li><li><h2  class="dd">作品信息</h2><div class="menu lvse" id="cartoon"><p><a href="<?php echo U('index/more');?>">更多作品</a></p></div></li><li><h2 class="ee">联系我们</h2><div class="menu lanse"><p><a href="<?php echo U('index/contact');?>">联系方式</a></p></div><!------------微博微信-------------><div class="weixin"><div class=" gz fl"><p><a href="http://weibo.com/5017523178/profile?topnav=1&wvr=5" target="_blank"><img src="__IMG__images/wb-xl.jpg" /></a></p><p><a href="http://t.qq.com/pierrotchina?preview" target="_blank"><img src="__IMG__images/wb-tx.jpg" /></a></p></div><div class="ewm fr"><img src="__IMG__images/ewm.jpg" style="width:66px;height:66px;"  onmouseover="ShowFloatingImage(this, 150, 150);" /></div></div></li></ul></div><script language='javascript'>$(function(){
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

</script></div><!------------最新信息-------------><div class="nymain fr"><div class="nytitle"><div class="fl"><div class="st fl" style="background:#fe9900"></div><h2 style="color:#fe9900;">公司信息</h2><div class="xx fr"></div></div><div class="jylist fl"><a href="<?php echo U('index/company');?>" class="hover">创始人寄语</a><a href="<?php echo U('index/pierrot');?>">Pierrot 株式会社介绍</a></div></div><div class="jyjsnr" style="padding-bottom:81px;"><img src="__IMG__images/jyjs.jpg" /><p>Pierrot（日本），自1979年设立以来，摒弃了刻板的动画制作，一直致力于新思想、新鲜感的动画作品创作。我们基于畅销漫画，制作了许多脍炙人口的动画作品，如：《尼尔斯骑鹅旅行记》等、其中也有像《GTO》、《棋魂》、《幽游白书》、《火影忍者疾风传》这样的代表作。与此同时，我们也同样致力于制作自己的原创动画，如《Creamy mami》。这些动画不仅在日本，也在海外播映并有着非常高的知名度。自此，一个源于日本的词----"动漫"，变成了流行世界的共通语言。</p><p>在这样的大背景下，几十年来我与中国的动漫产业保持着良好的关系。我对Pierrot的作品能在中国享有如此美赞的声誉感到非常自豪，我也非常的希望越来越多的中国能够熟悉和喜爱日本的动画人物以及动画作品。</p><p>我非常荣幸可以设立Pierrot China，以此来更好地在中国引进日本动画作品。作为历史悠久的动画制作公司，我们的任务和愿景是将高质量的动画形象和动画作品正式地传达给更多的人，并且唤醒国人的IP意识。</p></div></div><div class="footer fr white"><a href="/" target="_blank">首页</a><a href="<?php echo U('index/company');?>" target="_blank">公司信息</a><a href="<?php echo U('index/business');?>" target="_blank">公司业务摘要</a><a href="<?php echo U('index/more');?>" target="_blank">作品信息</a><a href="<?php echo U('index/employ');?>" target="_blank">招聘信息</a><a href="<?php echo U('index/clause');?>" target="_blank">网站使用条款</a><a href="<?php echo U('index/adv');?>" target="_blank">咨询及申请</a><p>© 2014. Pierrot China版权所有 <a href="http://www.miitbeian.gov.cn" target="_blank" style="float:none;">沪ICP备14018648号-1</a></p></div></div></div></body></html>