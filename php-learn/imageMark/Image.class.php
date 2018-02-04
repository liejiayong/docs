<?php
	class Image{
		
		private $imgInfo;
		private $img;
		
		/**
		 * 以路径方式，初始化图片
		 * @param unknown $src
		 */
		public function __construct($src){
			
			$imgInfo = getimagesize($src);
			$this->imgInfo = array(
					'width' => $imgInfo[0],
					'height' => $imgInfo[1],
					'type' => image_type_to_extension($imgInfo['2'],false),
					'mime' => $imgInfo['mime']
					
			);
			
			
			$fun = "imagecreatefrom{$this->imgInfo['type']}";
			
			$this->img =$fun($src);
		}
		
		/**
		 * 添加文字水印
		 */
		public function fontMark($content,$font_url,$size,$color,$local,$angle){
			$col = imagecolorallocatealpha($this->img, $color[0], $color[1], $color[2], $color[3]);
			imagettftext($this->img, $size, $angle, $local['x'], $local['y'], $color, $font_url, $content);
			
			
		}
		
		/**
		 * 添加图片水印
		 */
		
		public function imageMark($srcMark,$local,$alpha){
		
			$infoMark = getimagesize($srcMark);
			$typeMark = image_type_to_extension($infoMark[2],false);
			$funMark = "imagecreatefrom{$typeMark}";
			$water = $funMark($typeMark);
			imagecopymerge($water, $this->img, $local['x'], $local['y'],0,0,$infoMark[0],$infoMark[1],$alpha);
			imagedestroy($water);
			
		}
		
		/**
		 * 压缩图片
		 */
		
		public function thumb($width,$height){
			
			$thumb = imagecreatetruecolor($width, $height);
			
			imagecopyresampled($thumb, $this->img, 0,0,0,0, $width, $height, $this->imgInfo['width'],$this->imgInfo['height']);
			
			imagedestroy($this->img);
			
			$this->img = $thumb;
			
		}
		
		/**
		 * 输出浏览器中的图片
		 */
		
		public function showWebPic(){
			header("Content:".$this->imgInfo['mine']);
			$funs = "imagecreatefrom{$this->imgInfo['type']}";
			$funs($this->img);
			
		}
		
		/**
		 * 保持到硬盘里
		 */
		
		public function save($name){
			
			$funs = "imagecreatefrom{$this->imgInfo['type']}";
			$funs($this->img,$name.".".$this->imgInfo['type']);
		}
		
		
		/**
		 * 销毁图片
		 */
		
		public function __destruct(){
			imagedestroy($this->img);
		}
	}

?>