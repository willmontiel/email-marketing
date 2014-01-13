<?php
class ImageObject
{
	public $img;
	public $newImg;
	public $ext;
	public $resultingImage;


	public function __construct() 
	{
		
	}
	
	public function createFromBase64($img, $ext = null)
	{
		$this->ext = $ext;
		$search = array('data:image/png;base64,', 'data:image/jpeg;base64,', 'data:image/gif;base64,');
		$replace = array('', '', '');
		$imgB64 = str_replace($search, $replace, $img);
		
		$imgBinary = base64_decode($imgB64, true);
		
		if ($imgBinary == false) {
			throw new InvalidArgumentException('Invalid image on format base64...');
		}
		
		$this->img = imagecreatefromstring($imgBinary);
		
		if ($this->img == false) {
			throw new InvalidArgumentException('Invalid image on format string binary...');
		}
	}
	
	/**
	 * This function resized a image created previusly with this class, and receive: width, height and the background color
	 * in rgb format. transparent color is the default color.
	 * @param int $w
	 * @param int $h
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 */
	public function resizeImage($w, $h, $red = null, $green = null, $blue = null)
	{
		$width = imagesx ($this->img);
		$height =imagesy($this->img);
		
		$proportion = $this->getImageProportion($width, $height, $w, $h);
		
		$this->newImg = imagecreatetruecolor($w ,$h);
		
		if ($red == null || $green == null || $blue == null) {
			$transparent = imagecolorallocate($this->newImg, 0, 0, 0);
			imagecolortransparent($this->newImg, $transparent);
		}
		else {
			imagecolorallocate($this->newImg, $red, $green, $blue);
		}
		
		ImageCopyResized($this->newImg, $this->img, $proportion['x'], $proportion['y'], 0, 0, $proportion['newWidth'], $proportion['newHeight'], $width, $height);	
		
		ob_start();
		switch ($this->ext) {
			case 'png':
				$img = imagepng($this->newImg);
				break;
			case 'jpg':
			case 'jpeg':
				$img = imagejpeg($this->newImg);
				break;
			case 'gif':
				$img = imagegif($this->newImg);
				break;
			default:
				$img = imagepng($this->newImg);
				break;
		}
		
		if ($img) {
			$outputBuffer = ob_get_clean();
			$this->resultingImage = base64_encode($outputBuffer);
		}
		else {
			$outputBuffer = ob_get_clean();
			$this->resultingImage = null;
			throw new InvalidArgumentException('Error while creating base64 image...');
		}
		
	}
	
	protected function getImageProportion($width, $height, $w, $h)
	{
		$scale = $w/$h;
		$proportion = $width/$height;
		
		if ($proportion >= $scale) {
			$newHeight = ($w/$width) * $height;;
			$newProportion = array('newWidth' => $w, 
								   'newHeight' => $newHeight,
								   'y' => ($h-$newHeight)/2,
								   'x' => 0);
		}
		else if ($proportion < $scale) {
			$newWidth = ($h/$height) * $width;;
			$newProportion = array('newWidth' => $newWidth, 
								   'newHeight' => $h,
								   'y' => 0,
								   'x' => ($w-$newWidth)/2);
		}
		return $newProportion;
	}
	
	public function getImageBase64()
	{
		return $this->resultingImage;
	}
}
