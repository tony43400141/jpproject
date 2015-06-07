$m = function(id) {return document.getElementById(id);}
function trim(str){  //删除左右两端的空格
 return str.replace(/(^\s*)|(\s*$)/g, '');
}
function redirect(url)
	{
		window.location.replace(url);
	}
$(document).ready(function(){
	$("#Login").submit(function(){
		if($m("UserName").value == '') {
			$("#errorMessage").slideDown("slow").text("用户名不能为空!");
			return false;
		}
		if($m("Password").value == '') {
			$("#errorMessage").slideDown("slow").text("密码不能为空!");
			return false;
		}
	});
	$("#form1").submit(function(){
		if(trim($m("username").value) == '') {
			$("#errorMessage").slideDown("slow").text("用户名不能为空!");
			return false;
		}
		if(trim($m("Password").value) == '') {
			$("#errorMessage").slideDown("slow").text("密码不能为空!");
			return false;
		}
		if(trim($m('Password').value) != trim($m('PwdConfirm').value)) {
			$("#errorMessage").slideDown("slow").text("两次密码输入不一样!");
			return false;
		}
	});
	$("#form2").submit(function(){
//		if(trim($m("Password").value) == '') {
//			$("#Message").slideDown("slow").text("密码不能为空!");
//			return false;
//		}
		if(trim($m('Password').value) != trim($m('PwdConfirm').value)) {
			$("#Message").slideDown("slow").text("两次密码输入不一样!");
			return false;
		}
	});
	$(".tag").click(function(){
		$.getJSON("admin.php",{mod:"dotblu",file:"ajax",action:"showmessage",id:$(this).attr("id")},function(data){
//			var i = new Image();
//			i.src = "images/jian.gif";
			if($("#"+data.id).attr("src") == "images/jia.gif") {
				$("#"+data.id).attr({src:"images/jian.gif"});
			} else {
				$("#"+data.id).attr({src:"images/jia.gif"});
			}
			$("#content"+data.id).html(data.name);
			$("#show"+data.id).toggle();
		});
	});
	$(".del").click(function(){
		if(confirm("此操作会把评论都会删除,是否继续")) {
			return true;
		} else {
			return false;
		}
	});
	$("#c").change(function(){
			var type = $(this).val();
			var id = $(this).attr('name');
			if(type == '') return false;
			$.ajax({
				   url : "admin.php",
				   type : "POST" ,
				   data : "file=ajax&action=order&type="+type+"&id="+id,
				   success : function(msg) {
						alert('状态修改成功!');
				   }
				   });
	});

	$(".delete").click(function(){

		var id = $(this).attr("id");
		var pic = $("#pic"+id).val();
		$.ajax({

			url : "admin.php" ,
			type : "POST" ,
			data : "file=ajax&action=templates&id="+id+"&pic="+pic,
			success : function(msg) {
				$("#div"+msg).remove();
				alert('删除图片后请务备点修改按钮!');
			}

		});

	});

});

function checkbox_all(name, type){
	if( type == 2 ){
		$("input[type=checkbox]:checked").each(function(){
			$(this).attr('checked', '');
		});
	} else {
		$("input[type=checkbox]").each(function(){
			$(this).attr('checked', 'checked');
		});
	}
}

