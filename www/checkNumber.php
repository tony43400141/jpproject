<?php 
/* 
* Filename:authimg.php 
*/ 
Header("Content-type:image/PNG"); 
session_start(); 
$auth_num=""; 
//session_register('auth_num'); 
//$_SESSION['vali_auth_num']=$auth_num;
$im=imagecreate(63,20); 
srand((double)microtime()*1000000); 
$auth_num_k=md5(rand(0,9999)); 
$auth_num=substr($auth_num_k,17,4); 
$black=ImageColorAllocate($im,0,0,0); 
$white=ImageColorAllocate($im,255,255,255); 
$gray=ImageColorAllocate($im,200,200,200); 
//ImageFill($im,63,20,$black);//这行不知道为什么在我公司的服务器上出错误，换个空间ok 
imagestring($im,5,10,3,$auth_num,$gray); 
for($i=0;$i<200;$i++) 
{ 
$randcolor=ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255)); 
imagesetpixel($im,rand()%70,rand()%30,$randcolor); 
} 
ImagePNG($im); 
ImageDestroy($im); 
$_SESSION['auth_num']=$auth_num;
?> 
