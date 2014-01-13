<?php
class ImageObject
{
	public $img;
	public $newImg;
	public $ext;
	public $resultingImage;
	public $path;
	/**
	 * This function creates a image from a base64 image, receive a base64 image and the extension to return. the default
	 * extension is png
	 * @param string $img
	 * @param string $ext
	 * @throws InvalidArgumentException
	 */
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
	 * This function creates a image from other image hosted in a temporal directory on server, and receives:
	 * the image path and the image name to find its extension 
	 * @param string $path
	 * @param string $name
	 */
	public function createFromImage($path, $name)
	{
		$this->ext = pathinfo($name, PATHINFO_EXTENSION);
		
		switch ($this->ext) {
			case 'jpg':
			case 'jpeg':
				$this->img = @imagecreatefromjpeg($path);
				break;

			case 'png':
				$this->img = @imagecreatefrompng($path);
				break;

			case 'gif':
				$this->img = @imagecreatefromgif($path);
				break;
		}
	}
	
	/**
	 * This function cuts a image created previusly with this class, and receive: width, height and the background color
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
	}
	
	/**
	 * This function calculates the image proportion for then to be cut. Receives:
	 * width and height of the original image and width and height of the final image
	 * @param int $width
	 * @param int $height
	 * @param int $w
	 * @param int $h
	 */
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
	
	/**
	 * This function returns a base64 processed image
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function getImageBase64()
	{
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
			return $this->resultingImage;
		}
		else {
			$outputBuffer = ob_get_clean();
			$this->resultingImage = null;
			throw new InvalidArgumentException('Error while creating base64 image...');
		}
	}
	
	/**
	 * This function returns the path image after to be cut. receives:
	 * the format image(png, jpeg, jpg, gif) to return (png is the default format) and the path where it will be saved
	 * @param type $type
	 * @param type $path
	 * @return type
	 * @throws InvalidArgumentException
	 */
	public function getImage($type, $path)
	{
		switch ($type) {
			case 'png':
				$img = imagepng($this->newImg, $path);
				break;
			case 'jpg':
			case 'jpeg':
				$img = imagejpeg($this->newImg, $path);
				break;
			case 'gif':
				$img = imagegif($this->newImg, $path);
				break;
			default:
				$img = imagepng($this->newImg, $path);
				break;
		}
		if (!$img) {
			throw new InvalidArgumentException('image not be saved on the path...');
		}	
		
		return $this->path;
	}
}
