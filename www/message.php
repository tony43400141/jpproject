<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示信息</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/jquery.js"></script>
<SCRIPT src="js/func.js" type="text/javascript"></SCRIPT>
</head>
<body leftmargin="2" topmargin="0" marginwidth="0" marginheight="0">
<center>
<div <?php if(!isset($action) || $action == 'logout'){ ?>style="width:600px;"<?php } ?>>
<h1>
<span class="action-span1"><a href="admin.php"> 管理中心</a>  - 系统信息 </span>

<div style="clear: both;"></div>
</h1>
<div class="list-div">
	  <div style="background: none repeat scroll 0% 0% rgb(255, 255, 255); padding: 20px 50px; margin: 2px;">
		<table align="center" width="400" border="0" cellpadding="2" cellspacing="1" >
				<tr class="">
					<td height="22" colspan="2" align="left" style="font-size: 14px; font-weight: bold;">
					<ul style="margin: 0pt; padding: 0pt 10px;" class="msg-link">
						<li>
							
						<?=$msg;?>
						</li>
					</ul>
					</td>
				</tr>
				<tr class="">
					<td height="22" colspan="2" align="left" style="font-size: 12px; padding-left:40px;">
						<?php if( $url_forward ) { ?>
						<a href="<?=$url_forward ; ?>">无反应请点击返回</a>
						<?php } ?>
					</td>
				</tr>

			<?php if ($url_forward == 'goback'){?>
				<tr><td colspan="2"  align="left" style="font-size: 12px; padding-left:40px;">
				  <a href="javascript:history.go(-1);">点击返回上一页</a>
				   <script>setTimeout("window.history.back();", <?=$ms?>);</script>
				</td></tr>
			<?php } elseif( $url_forward ) { ?>
				<tr><td colspan="2" align="left" style="font-size: 12px; padding-left:40px;">
				  将在 <span id="spanSeconds">3</span>秒后直接跳转
				  <script>setTimeout("redirect('<?=$url_forward ; ?>');", <?=$ms?>);</script>
				</td></tr>
			<?php } ?>
		</table>
	</div>
</div>
</div>
</center>
</body>
</html>
