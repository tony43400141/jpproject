<?php
class upload {
		var $uptypes=array( 
			'image/jpg',  
			'image/jpeg',  
			'image/png', 
			'image/pjpeg', 
			'image/gif', 
			'image/bmp', 
			'image/x-png' 
		); 
		var $max_file_size=2000000;            //上传文件大小限制, 单位BYTE 
		var $destination_folder= UPLOADPATH; 	 //上传文件路径 
		var $watermark=0;                      //是否附加水印(1为加水印,其他为不加水印); 
		var $watertype=2;                      //水印类型(1为文字,2为图片) 
		var $waterposition=1;                  //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中); 
		var $waterstring="http://www.example.cn/";  //水印字符串 
		var $waterimg= "../images/c_logo.gif";                //水印图片 
		var $newfilename;
		var $type;
		var $oldimage;
		var $newsmallname;
		function uploadImage($arr,$upname){
			if (!is_uploaded_file($arr[$upname]['tmp_name'])) 
			//是否存在文件 
			{ 
				 echo "图片不存在!"; 
				 exit; 
			} 
			$file = $arr[$upname]; 
			if($this->max_file_size < $file["size"]) 
			//检查文件大小 
			{ 
				echo "文件太大!"; 
				exit; 
			} 
			if(!in_array($file["type"], $this->uptypes))  
			//检查文件类型 
			{ 
				echo "文件类型不符!".$file["type"]; 
				exit; 
			} 
			if(!file_exists($this->destination_folder)) 
			{ 
				mkdir($this->destination_folder); 
			} 
			$filename=$file["tmp_name"]; 
			$image_size = getimagesize($filename); 
			$pinfo=pathinfo($file["name"]); 
			$ftype=strtolower($pinfo['extension']); 
			$ft = $upname . time();
			$destination = $this->destination_folder.$ft.".".$ftype; 
			if(substr($destination,0,1) === '.') {
				$this->newfilename = substr($destination,3);
			} else {
				$this->newfilename = $destination;
			}
			$this->newsmallname = $this->destination_folder.$ft."_s.".$ftype;
			$this->type = $ftype;
			if (file_exists($destination))  
			{ 
				echo "同名文件已经存在了"; 
				exit; 
			} 
			if(!move_uploaded_file ($filename, $destination)) 
			{ 
				echo "移动文件出错"; 
				exit; 
			} 
			copy($destination,$this->newsmallname);
			$pinfo=pathinfo($destination); 
			$fname=$pinfo['basename']; 
			if($this->watermark==1) 
			{ 
				$this->addwater($destination,$image_size);
			}
		}
		function addwater($destination,$image_size) {
			$iinfo=getimagesize($destination,$iinfo); 
				$nimage=imagecreatetruecolor($image_size[0],$image_size[1]); 
				$white=imagecolorallocate($nimage,255,255,255); 
				$black=imagecolorallocate($nimage,0,0,0); 
				$red=imagecolorallocate($nimage,255,0,0); 
				imagefill($nimage,0,0,$white); 
				switch ($iinfo[2]) 
				{ 
					case 1:  
					$simage =imagecreatefromgif($destination); 
					break; 
					case 2: 
					$simage =imagecreatefromjpeg($destination); 
					break; 
					case 3: 
					$simage =imagecreatefrompng($destination); 
					break; 
					case 6: 
					$simage =imagecreatefromwbmp($destination);  
					break; 
					default: 
					die("不支持的文件类型"); 
					exit; 
				} 
				imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]); 
				switch($this->watertype) 
				{ 
					case 1:   //加水印字符串 
					imagefilledrectangle($nimage,1,$image_size[1]-15,140,$image_size[1],$white); 
					imagestring($nimage,2,3,$image_size[1]-15,$this->waterstring,$black);  
					break; 
					case 2:   //加水印图片 
					$simage1 =imagecreatefromgif($this->waterimg); 
					if($this->waterposition == 1) {
						imagecopy($nimage,$simage1,0,0,0,0,200,60); 
					} elseif($this->waterposition == 2) {
						imagecopy($nimage,$simage1,$image_size[0]-200,0,0,0,200,60);
					} else {
						imagecopy($nimage,$simage1,$image_size[0]-200,$image_size[1]-60,0,0,200,60);
					}
					
					imagedestroy($simage1); 
					break; 
				} 
				switch ($iinfo[2]) 
				{ 
					case 1: 
					//imagegif($nimage, $destination);  
					imagejpeg($nimage, $destination); 
					break; 
					case 2: 
					imagejpeg($nimage, $destination); 
					break; 
					case 3: 
					imagepng($nimage, $destination); 
					break; 
					case 6: 
					imagewbmp($nimage, $destination);  
					//imagejpeg($nimage, $destination); 
					break; 
				} 
				//覆盖原上传文件 
				imagedestroy($nimage); 
				imagedestroy($simage); 
		}
	}
class resizeimage extends upload 
{
   //图片类型
   var $type;
   //实际宽度
   var $width;
   //实际高度
   var $height;
   //改变后的宽度
   var $resize_width;
   //改变后的高度
   var $resize_height;
   //是否裁图
   var $cut;
   //源图象
   var $srcimg;
   //目标图象地址[separator]
   var $dstimg;
   //临时创建的图象
   var $im;
function image($wid, $hei,$c,$array)
   {
       $this->resize_width = $wid;
       $this->resize_height = $hei;
       $this->cut = $c;
       //图片的类型
      // $this->type = substr(strrchr($this->srcimg,"."),1);
       //初始化图象
       $this->initi_img();
       //目标图象地址
       $this -> dst_img();
       //--
       $this->width = imagesx($this->im);
       $this->height = imagesy($this->im);
       //生成图象
       $this->newimg();
       ImageDestroy ($this->im);
	   return true;
   }
   function newimg()
   {
       //改变后的图象的比例
       $resize_ratio = ($this->resize_width)/($this->resize_height);
       //实际图象的比例
       $ratio = ($this->width)/($this->height);
       if(($this->cut)=="1")
       //裁图
       {
           if($ratio>=$resize_ratio)
           //高度优先
           {
               $newimg = imagecreatetruecolor($this->resize_width,$this->resize_height);
               imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width,$this->resize_height, (($this->height)*$resize_ratio), $this->height);
               ImageJpeg ($newimg,$this->dstimg);
			   $this->watertype = 1 ;
			   $image_s = getimagesize($this->dstimg);
			   if($this->watermark==1) {
			   		$this->addwater($this->dstimg,$image_s);
			   }
           }
           if($ratio<$resize_ratio)
           //宽度优先
           {
               $newimg = imagecreatetruecolor($this->resize_width,$this->resize_height);
               imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, $this->resize_height, $this->width, (($this->width)/$resize_ratio));
               ImageJpeg ($newimg,$this->dstimg);
			   $this->watertype = 1 ;
			   $image_s = getimagesize($this->dstimg);
			   if($this->watermark==1) {
			   		$this->addwater($this->dstimg,$image_s);
			   }
           }
       }
       else
       //不裁图
       {
           if($ratio>=$resize_ratio)
           {
               $newimg = imagecreatetruecolor($this->resize_width,($this->resize_width)/$ratio);
               imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, ($this->resize_width)/$ratio, $this->width, $this->height);
               ImageJpeg ($newimg,$this->dstimg);
			   $this->watertype = 1 ;
			   $image_s = getimagesize($this->dstimg);
			   if($this->watermark==1) {
			   		$this->addwater($this->dstimg,$image_s);
			   }
           }
           if($ratio<$resize_ratio)
           {
               $newimg = imagecreatetruecolor(($this->resize_height)*$ratio,$this->resize_height);
               imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, ($this->resize_height)*$ratio, $this->resize_height, $this->width, $this->height);
               ImageJpeg ($newimg,$this->dstimg);
			   $this->watertype = 1 ;
			   $image_s = getimagesize($this->dstimg);
			   if($this->watermark==1) {
			   		$this->addwater($this->dstimg,$image_s);
			   }
           }
       }
	   @unlink($this->newsmallname);
   }
   //初始化图象
   function initi_img()
   {
       if($this->type=="jpg")
       {
           $this->im = imagecreatefromjpeg($this->newsmallname);
       }
       if($this->type=="gif")
       {
           $this->im = imagecreatefromgif($this->newsmallname);
       }
       if($this->type=="png")
       {
           $this->im = imagecreatefrompng($this->newsmallname);
       }
   }
   //图象目标地址
   function dst_img()
   {
       $full_length = strlen($this->newsmallname);
       $type_length = strlen($this->type);
       $name_length = $full_length-$type_length;
       $name         = substr($this->newsmallname,0,$name_length-1);
       $this->dstimg = $name."mall.".$this->type;
   }
}
?>
