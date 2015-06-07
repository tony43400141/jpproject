function inAdv()
{
        reg_name = null;
		reg_email = null;
		reg_mobile = null; 
		reg_text = null;
        if(reg_name === null) {
            verifyName();
        }
		if(reg_mobile === null) {
            verifyMobile();
        }
		if(reg_email === null) {
            verifyEmail();
        }
		if(reg_text === null) {
            verifyText();
        }
        if(!reg_name || (!reg_email && !reg_mobile)||!reg_text)
        {
			return false;
		}
        else
		{
			Today = new Date();
			var NowHour = Today.getHours();
			var NowMinute = Today.getMinutes();
			var NowSecond = Today.getSeconds();
			var mysec = (NowHour*3600)+(NowMinute*60)+NowSecond;
			if((mysec-$('#mypretime').val())>30){
			 $('#mypretime').val(mysec);
			}
			else{
			   alert('按一次就够了，请勿重复提交！请耐心等待！谢谢合作！');
			   return false;
			}
            $.ajax({
			type:'post',
			url:'/index/addv/',
			data:{name:$('#r_name').val(),email:$('#r_email').val(),mobile:$('#r_mobile').val(),content:$('#r_text').val()},
			cache:false,
			dataType:'json',
			success:function(data)
			{
				
				alert(data.msn);
			
			}
		
			});
		}
}
$('#r_name').blur(function(){
	verifyName();
});
$('#r_mobile').blur(function(){
	verifyMobile();
});
$('#r_email').blur(function(){
	verifyEmail();
});
$('#r_text').blur(function(){
	verifyText();
});

 
function verifyName(){
	reg_name = true;
	var name = $('#r_name').val();
	if(!name || name.length<2 || name.length>5 || !(/^[\u4E00-\u9FA5]+$/.test(name))) {
		$('#r_name').css('border-color','red');
		reg_name = false;
	}
	else
	{
		$('#r_name').css('border-color','#BFBFBF');
	}
}
    
function verifyEmail(){
	reg_email = true;
	var email = $('#r_email').val();
	if(!email ||!(/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(email))) {
		$('#r_email').css('border-color','red');
		reg_email = false;
	}
	else
	{
		$('#r_email').css('border-color','#BFBFBF');
	}
}
function verifyMobile(){
	reg_mobile = true;
	var mobile = $('#r_mobile').val();
	if(!mobile ||!(/^(13[0-9]|14[0-9]|15[0|1|2|3|5|6|7|8|9]|18[0|3|5|6|7|8|9])\d{8}$/.test(mobile))) {
		$('#r_mobile').css('border-color','red');
		reg_mobile = false;
	}
	else
	{
		$('#r_mobile').css('border-color','#BFBFBF');
	}
}
function verifyText(){
	reg_text = true;
	var text = trim($('#r_text').val());
	if(!text || text=='') {
		$('#r_text').css('border-color','red');
		reg_text = false;
	}
	else
	{
		$('#r_text').css('border-color','#BFBFBF');
	}
}
function trim(str)
{ 
 return str.replace(/(^\s*)|(\s*$)/g, '');
}