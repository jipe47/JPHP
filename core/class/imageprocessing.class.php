<?php
/**
* Executes miscellaneous operations related to image processing.
* 
* @author Jean-Philippe Collette
* @package Core
* @subpackage File
*/
class ImageProcessing
{
	
	public static function crop($file, $destination, $maxWidth, $maxHeight)
	{
		if(!file_exists($file))
		{
			Messages::add("Crop: file " . $file . " does not exist.", Message::ERROR);
			return false;
		}
		$array_dim = getimagesize($file);
		$width = $array_dim[0];
		$height = $array_dim[1];
		
		$r = $width / $height;
		$newR = $maxWidth / $maxHeight;
		
		if($width < $maxWidth || $height < $maxHeight)
		{
			Messages::add("Crop: file too small.", Message::ERROR);
			return false;
		}
			
			
		if($r > $newR)
		{
			$newHeight = $height;
			$newWidth = $newHeight * $newR;
			$decalX = round(($width - $newWidth)/2);
			$decalY = 0;
			
			/*
			 ________________________________
			|		|				|		|
			|		|		X		|		|
			|		|				|		|
			|		|		X		|		|
			|		|				|		|
			|		|		X		|		|
			|		|				|		|
			|		|		X		|		|
			|		|				|		|
			|		|		X		|		|
			|		|				|		|
			|_______|_______________|_______|
			*/
		}
		else
		{
			$newWidth = $width;
			$newHeight = $width / $newR;
			$decalY = round(($height - $newHeight) / 2);
			$decalX = 0;
			
			/*
			 ________________________________
			|								|
			|								|
			|_______________________________|
			|								|
			|								|
			|	X	X	X	X	X	X	X	|
			|								|
			|_______________________________|
			|								|
			|								|
			|_______________________________|
			*/
		}
		
		$crop = imagecreatetruecolor($maxWidth, $maxHeight);
		
		$info = pathinfo($file);
		$extension = $info['extension'];
		
		switch($extension)
		{
			case "jpeg":
			case "jpg":
				$source = imagecreatefromjpeg($file);
				break;
			case "gif":
				$source = imagecreatefromgif($file);
				break;
			case "png":
				$source = imagecreatefrompng($file);
				break;
		}
		
		imagecopyresampled($crop, $source, 0, 0, $decalX, $decalY, $maxWidth, $maxHeight, $newWidth, $newHeight);
		
		switch($extension)
		{
			case "jpeg":
			case "jpg":
				imagejpeg($crop, $destination);
				break;
			case "gif":
				imagegif($crop, $destination);
				break;
			case "png":
				imagepng($crop, $destination);
				break;
		}
		return true;
	}
	/**
	* Resizes a picture. Dimensions are as close as possible to the original while maintaining the aspect ratio. Picture can be filled to create a picture of maximum width and height.
	* @param string $file Path to the picture to resize.
	* @param string $destination Path for the resized picture.
	* @param int $maxWidth Maximum width of the resized picture.
	* @param int $maxHeight Maximum height of the resized picture.
	* @param boolean $fill If true, fills the image background in white to match the maximum dimensions.
	*/
	public static function redim($file, $destination, $maxWidth, $maxHeight, $fill=false)
	{
		if(!file_exists($file))
			Messages::add("Redim: file " . $file . " does not exist.", Message::ERROR);
		
		// Compute new dimensions
		$array_dim = getimagesize($file);
		$width = $array_dim[0];
		$height = $array_dim[1];
				
		$dim = self::computeDim($width, $height, $maxWidth, $maxHeight);
		
		$mini_width = ($fill) ? $maxWidth : $dim['width'];
		$mini_height = ($fill) ? $maxHeight : $dim['height'];
		
		// Creation of miniature
		$mini = imagecreatetruecolor($mini_width, $mini_height);
		
		$info = pathinfo($file);
		$extension = $info['extension'];
				
		switch($extension)
		{
			case "jpeg":
			case "jpg":
				$source = imagecreatefromjpeg($file);
				break;
			case "gif":
				$source = imagecreatefromgif($file);
				break;
			case "png":
				$source = imagecreatefrompng($file);
				break;
		}
		
		$x = ($fill) ? ($mini_width - $dim['width']) / 2 : 0;
		$y = ($fill) ? ($mini_height - $dim['height']) / 2 : 0;
		
		
		
		$black = imagecolorallocate($mini, 0, 0, 0);
		imagefilledrectangle($mini, 0, 0, $mini_width, $mini_height, $black);
		imagecolortransparent($mini, $black);
		//imagefilledrectangle($mini, 0, 0, $mini_width, $mini_height, $white);
		
		imagecopyresampled($mini, $source, $x, $y, 0, 0, $dim['width'], $dim['height'], $width, $height);
		
		if(!$fill)
		{
			switch($extension)
			{
				case "jpeg":
				case "jpg":
					imagejpeg($mini, $destination);
					break;
				case "gif":
					imagegif($mini, $destination);
					break;
				case "png":
					imagepng($mini, $destination);
					break;
			}
		}
		else
		{
			$n = explode(".", $destination);
			array_pop($n);
			$d = implode(".", $n).".png";
			imagepng($mini, $d);
		}
	}
	
	/**
	* Given a width and a height, computes the dimensions as close as possible to a maximum width and height, while maintaining the original aspect ratio.
	* @param int $width Input width.
	* @param int $height Input height.
	* @param int $maxWidth Maximum output width.
	* @param int $maxHeight Maximum output height.
	* @return array An array containing two keys with new dimensions: 'width' and 'height'.
	*/
	public static function computeDim($width, $height, $maxWidth, $maxHeight)
	{
		$ratio = $width / $height;
		
		if($width == $maxWidth && $height == $maxHeight)
		{
			$width = $maxWidth;
			$height = $maxHeight;
		}
		elseif($width > $maxWidth && $height > $maxHeight)
		{
			if($width >= $height){
				$width = $maxWidth;
				$height = $width / $ratio;
			}
			else
			{
				$height = $maxHeight;
				$width = $height * $ratio;
			}
		}
		else if($width > $maxWidth && $height < $maxHeight)
		{
			$width = $maxWidth;
			$height = $width / $ratio;
		}
		else if($width < $maxWidth && $height > $maxHeight)
		{
			$height = $maxHeight;
			$width = $height * $ratio;
		}
		$width = round($width, 0);
		$height = round($height, 0);
		
		return array('width' => $width, 'height' => $height);
	}
	
	
	public static function computeOffset($url, $wmax, $hmax)
	{
		if(!file_exists($url))
			return array('width' => -1, 'height' => -1, 'offsetX' => -1, 'offsetY' => -1);
		$size = getimagesize($url);
		$w = $size[0];
		$h = $size[1];
		$r = $w / $h;
		
		$decalX = 0;
		$decalY = 0;

		if(!($w < $wmax && $h < $hmax))
		{
			if($w > $wmax)
			{
				$w = $wmax;
				$h = floor($w / $r);
			
				if($h > $hmax)
				{	
					$decalY = floor(($h - $hmax) / 2);
							//	echo '<h1>h > hmax ; decal = ' . $decalY . '</h1>';

				}
				else if($h < $hmax)
				{
					$h = $hmax;
					$w = floor($r * $h);
					//echo '<h1>h < hmax</h1>';

					$decalX = floor(($w - $wmax) / 2);
				}
			}
			else
			{
				$h = $hmax;
				$w = $h * $r;
				
				if($w < $wmax)
				{
					$w = $wmax;
					$h = floor($w / $r);
					$decalY = floor(($h - $hmax) / 2);
				}
				else if($w > $wmax)
				{
					$decalX = floor(($w - $wmax) / 2);
				}
			}
		}
		else
		{
			$diffY = $hmax - $h;
			$diffX = $wmax - $w;
			
			if($diffX > $diffY)
			{
				$w = $wmax;
				$h = floor($w / $r);
				$decalY = floor(($h - $hmax) / 2);
			}
			else
			{
				$h = $hmax;
				$w = floor($r * $h);
				$decalX = floor(($w - $wmax) / 2);
			}
		}
		return array('width' => $w, 'height' => $h, 'offsetX' => $decalX, 'offsetY' => $decalY);
	}

}