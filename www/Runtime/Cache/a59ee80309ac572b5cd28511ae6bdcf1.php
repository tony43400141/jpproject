<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>__WEBN__-火影忍者</title><link rel="stylesheet" type="text/css" href="__CSS__css.css" /><script type="text/javascript" src="__JS__jquery.min.js"></script></head><body><div class="allbg"><!------------框架-------------><div class="nywrapper"><!------------左侧-------------><div class="left nynav fl"><!------------logo-------------><div class="logo"><img src="__IMG__images/logo.jpg" /></div><!------------导航-------------><div class="nav white"><ul><li><h2 class="aa"><a href="/">首页 ></a></h2></li><li><h2 class="bb">公司信息</h2><div class="menu chengse"><a href="<?php echo U('index/company');?>">Pierrot 株式会社</a><a href="<?php echo U('index/china');?>">Pierrot China</a></div></li><li><h2  class="cc">公司业务摘要</h2><div class="menu meise"><p><a href="<?php echo U('index/business');?>">公司业务介绍</a></p><p><a href="<?php echo U('index/process');?>">授权流程</a></p><p><a href="<?php echo U('index/server');?>">授后服务</a></p><p><a href="<?php echo U('index/protect');?>">版权保护</a></p></div></li><li><h2  class="dd">作品信息</h2><div class="menu lvse" id="cartoon"><p><a href="<?php echo U('index/more');?>">更多作品</a></p></div></li><li><h2 class="ee">联系我们</h2><div class="menu lanse"><p><a href="<?php echo U('index/contact');?>">联系方式</a></p></div><!------------微博微信-------------><div class="weixin"><div class=" gz fl"><p><a href="http://weibo.com/5017523178/profile?topnav=1&wvr=5" target="_blank"><img src="__IMG__images/wb-xl.jpg" /></a></p><p><a href="http://t.qq.com/pierrotchina?preview" target="_blank"><img src="__IMG__images/wb-tx.jpg" /></a></p></div><div class="ewm fr"><img src="__IMG__images/ewm.jpg" style="width:66px;height:66px;"  onmouseover="ShowFloatingImage(this, 150, 150);" /></div></div></li></ul></div><script language='javascript'>$(function(){
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

</script></div><!------------最新信息-------------><div class="nymain fr"><div class="nytitle"><div class="fl"><div class="st fl" style="background:#66cc33"></div><h2 style="color:#66cc33;">火影忍者作品介绍</h2><div class="xx fr" style="width:460px;"></div></div></div><div class="jyjsnr" style="padding-bottom:87px;"><img src="__IMG__images/works_hyrz.jpg" /><p>《火影忍者》是日本漫画家岸本齐史的代表作，作品于1999年开始在周刊《少年JUMP》上连载。故事成功地将原本隐藏在黑暗中，用世界上最强大的毅力和最艰辛的努力去做最密不可宣和隐讳残酷的事情的忍者，描绘成了太阳下最值得骄傲最光明无限的的职业。在岸本齐史笔下的忍者世界中，每一位年轻的忍者都在开拓着属于自己的忍道。</p><div style="margin-top:20px; overflow:hidden;"><img style="width:170px; margin-right:10px; float:left;" src="__IMG__images/works_hyrztp.jpg" /><p style=" width:420px;float:left;">·截止目前，漫画发行了69卷，持续中
<br />·漫画单行本日本销售1.3亿本，全球超过1.8亿本
<br />·长期日本、美国、法国等国的漫画销售排行榜第一位
<br />·2002年由Pierrot制作动画片，已播582集，已播12年，仍在继续
<br />·动画片全球发行80多个国家。长期居各国动画片收视排行榜第一位
<br />·主角鸣人入选成为 [NEWSWEEK] 评选的全世界最尊敬的100位日本人
<br />·漫画家岸本齐史凭该漫画登上日本漫画家缴税排行榜首位，缴税超亿元
</p></div></div></div><div class="footer fr white"><a href="/" target="_blank">首页</a><a href="<?php echo U('index/company');?>" target="_blank">公司信息</a><a href="<?php echo U('index/business');?>" target="_blank">公司业务摘要</a><a href="<?php echo U('index/more');?>" target="_blank">作品信息</a><a href="<?php echo U('index/employ');?>" target="_blank">招聘信息</a><a href="<?php echo U('index/clause');?>" target="_blank">网站使用条款</a><a href="<?php echo U('index/adv');?>" target="_blank">咨询及申请</a><p>© 2014. Pierrot China版权所有 <a href="http://www.miitbeian.gov.cn" target="_blank" style="float:none;">沪ICP备14018648号-1</a></p></div></div></div></body></html>