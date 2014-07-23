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
	public function createImageFromFile($path, $name)
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
	public function resizeImage($w, $h, $color = null)
	{
		$width = imagesx($this->img);
		$height =imagesy($this->img);
		
		$proportion = $this->getImageProportion($width, $height, $w, $h);
		
		$this->newImg = imagecreatetruecolor($w ,$h);
		
		if ($color == null) {
			$transparent = imagecolorallocate($this->newImg, 0, 0, 0);
			imagecolortransparent($this->newImg, $transparent);
		}
		else {
			$rgb = $this->convertFromHexToRgbColor($color);
			imagecolorallocate($this->newImg, $rgb->red, $rgb->green, $rgb->blue);
		}
		
		ImageCopyResized($this->newImg, $this->img, $proportion['x'], $proportion['y'], 0, 0, $proportion['newWidth'], $proportion['newHeight'], $width, $height);	
		$this->img = $this->newImg;
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
	
	protected function convertFromHexToRgbColor($color)
	{
		list($red, $green, $blue) = sscanf($color, "#%02x%02x%02x");
	
		$rgb = new stdClass();
		$rgb->red = $red;
		$rgb->green = $green;
		$rgb->blue = $blue;
		
		return  $rgb;
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
				$img = imagepng($this->img);
				break;
			case 'jpg':
			case 'jpeg':
				$img = imagejpeg($this->img);
				break;
			case 'gif':
				$img = imagegif($this->img);
				break;
			default:
				$img = imagepng($this->img);
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
	public function saveImage($type, $path)
	{
		switch ($type) {
			case 'png':
				$img = imagepng($this->img, $path);
				break;
			case 'jpg':
			case 'jpeg':
				$img = imagejpeg($this->img, $path);
				break;
			case 'gif':
				$img = imagegif($this->img, $path);
				break;
			default:
				$img = imagepng($this->img, $path);
				break;
		}
		if (!$img) {
			throw new InvalidArgumentException('image not be saved on the path...');
		}	
		
		return $this->path;
	}
	
	
	public function getImageInMemory()
	{
		\ob_start();
		\imagepng($this->img);
		$data = \ob_get_contents();
		\ob_clean();
		return $data;
	}
	
	/**
	 * Moves a image from site to another site. Recieves:
	 * the path where the image is, and the path where image will be moved
	 * @param string $tmp_dir
	 * @param string $dir
	 * @throws InvalidArgumentException
	 */
	public function moveImageFromSiteToAnother ($tmp_dir, $dir) 
	{
		if (!move_uploaded_file($tmp_dir, $dir)){ 
			throw new InvalidArgumentException('Image could not be uploaded on the server');
		}
	}
}
