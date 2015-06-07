function tab(o, s, cb, ev){ //tab切换类
var $ = function(o){return document.getElementById(o)};
var css = o.split((s||'_'));
if(css.length!=4)return;
this.event = ev || 'onclick';
o = $(o);
if(o){
this.ITEM = [];
o.id = css[0];
var item = o.getElementsByTagName(css[1]);
var j=1;
for(var i=0;i<item.length;i++){
if(item[i].className.indexOf(css[2])>=0 || item[i].className.indexOf(css[3])>=0){
if(item[i].className == css[2])o['cur'] = item[i];
item[i].callBack = cb||function(){};
item[i]['css'] = css;
item[i]['link'] = o;
this.ITEM[j] = item[i];
item[i]['Index'] = j++;
item[i][this.event] = this.ACTIVE;
}
}
return o;
}
}
tab.prototype = {
ACTIVE:function(){
var $ = function(o){return document.getElementById(o)};
this['link']['cur'].className = this['css'][3];
this.className = this['css'][2];
try{
$(this['link']['id']+'_'+this['link']['cur']['Index']).style.display = 'none';
$(this['link']['id']+'_'+this['Index']).style.display = 'block';
}catch(e){}
this.callBack.call(this);
this['link']['cur'] = this;
}
}

window.onload = function(){
new tab('news_li_now_', '_', null, 'onmouseover');
};

	function ranklist(id,ele,elename,elechild,start_ele,cur_ele){
    var obj_id=document.getElementById(id);
    var obj_ele=$tag(obj_id,ele);
    for(i=0;i<obj_ele.length;i++){
        if(obj_ele[i].className.indexOf(elename) == -1) continue;
        var objlist=$tag(obj_ele[i],elechild);
        for(j=0;j<objlist.length;j++){
            objlist[j].onmouseover=function(){
                var paris=this.parentNode;
                var list=$tag(paris,elechild);
                for(x=0;x<list.length;x++){
                    var thisdiv=$tag(list[x],cur_ele)[0];
                    var thisp=$tag(list[x],start_ele)[0];
                    thisdiv.style.display="none";
                    thisp.style.display="block";
                    }
                var start=$tag(this,start_ele)[0];
                start.style.display='none';
                var cur=$tag(this,cur_ele)[0];
                cur.style.display='block';
            }
        }
    }
	
	setTimeout("ranklist('ranklist','ul','hot_act','li','p','div')",0);
}
function $tag(id,tag){return id.getElementsByTagName(tag);}


function getUserNum(){
	$.ajax({ 									//一个Ajax过程
		type: "POST", 						
		url : "index.php",						//与此php页面沟通
		data: 'action=ajax&do=usernum', 					//发给php的数据有两项，分别是上面传来的u和p
		success: function(result){				//如果调用php成功
			$("#js_user_num").html(result);
		}
	});
}

$(window).ready(function() { 
	getUserNum();
	window.setInterval(getUserNum, 60000); //间隔函数，3秒执行 
	
	$(".login_in").click(function(){
		pt_login();
	})
	
	$("#username").keydown(function(event){
		if (event.keyCode == 13)    {
			 pt_login();
		}
	})
	
	$("#password").keydown(function(event){
		if (event.keyCode == 13)    {
			pt_login();
		}
	})
}); 
function pt_login(){
	if(trim($("#username").val()) == "") {
		$("#userti").html('用户名不能为空');
		$("#userti").css({"display" : "block"});
		setTimeout("redirect('userti')",2000);
		$("#username").focus();
		return false;
	}

	$("#userti").html('');
	if(trim($("#password").val()) == "") {
		$("#wordti").html('密码不符合规范');
		$("#wordti").css({"display" : "block"});
		setTimeout("redirect('wordti')",2000);
		$("#password").focus();
		return false;
	}
	if($("#remeber").attr("checked"))
		var remeber = 1;
	else 
		var remeber = 0;
	$.ajax({

		type    :  'POST',
		data    :  'do=login&username=' + $("#username").val() + "&password=" + $("#password").val() + "&remeber="+ remeber,
		url     :  'index.php?action=ajax',
		success :  function(msg){
			var data = eval("("+msg+")");
			if(data.success == 2 ) {
				$("#name").html(data.username);
				$("#uu").html(data.userid);
				$("#coin").html(data.coin);
				$(".login").hide();
				$(".login_suc").show();
				return false;
			}else if (data.success == 0){
				$("#userti").html('用户名或密码错误');
				$("#userti").css({"display" : "block"});
				setTimeout("redirect('userti')",2000);
				$("#username").focus();
				return false;
			}else {
				$("#userti").html('用户名或密码错误');
				$("#userti").css({"display" : "block"});
				setTimeout("redirect('userti')",2000);
				return false;
			}
		}
	});
}

function login_check() {
	
	$.ajax({
		
		type    :  'post',
		url     :  'index.php',
		data    :  'action=ajax&do=logined',
		success :  function(msg) {
			
			if(msg == "0") {
				$(".login").show();
			} else {
				var data = eval("("+msg+")");
				if(data[1]) {
					$("#name").html(data[1]);
					$("#uu").html(data[3]);
					$("#coin").html(data[4]);
					$(".login_suc").show();
				}
			}
		}
		
	})
	
}

function login_out() {
	
	$.ajax({

		type    :  'POST',
		data    :  'do=logout',
		url     :  'index.php?action=ajax',
		success :  function(msg){
			
			if(msg == 1 ) {
				$(".login_suc").hide();
				$(".login").show();
			}else {
				alert('网络忙，请稍后再试！');
				return false;
			}
		}
	});
	
}

function redirect(obj) {
	
	$("#" + obj).css({"display" : "none"});
	
}

function GetCookieVal (offset)
{
    var endstr = document.cookie.indexOf (";", offset);
    if (endstr == -1)
    endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
}

//取得名称为name的cookie值
function GetCookie (name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen)
    {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg)
       return GetCookieVal (j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}

function trim(str){  //删除左右两端的空格

	 return str.replace(/(^\s*)|(\s*$)/g, '');

}

function addfavorite()
{
	if(window.sidebar){
		window.sidebar.addPanel('2121动漫王国', 'http://www.2121.com', "");
	}else{
		try{
			window.external.addFavorite('http://www.2121.com','2121动漫王国');
		}catch(e){
			alert("您的浏览器不支持该功能,请使用Ctrl+D收藏本页");	
		}
	}
} 