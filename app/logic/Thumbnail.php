<?php
class Thumbnail
{
	function __construct(Account $account) 
	{
		$this->account = $account;
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->log = new Phalcon\Logger\Adapter\File("../app/logs/debug.log");
		$this->assetsrv = $di['asset'];
	}
	
	public function createThumbnail(Asset $asset, $dirImage, $name)
	{
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/images/' ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$dir .= $asset->idAsset . '_thumb.png';
		
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				$img = @imagecreatefromjpeg($dirImage);
				break;

			case 'png':
				$img = @imagecreatefrompng($dirImage);
				break;

			case 'gif':
				$img = @imagecreatefromgif($dirImage);
				break;
		}

		$width = imagesx ($img);
		$height =imagesy($img);
		
		$thumbnailInfo = $this->getCreateThumbnailInfo($width, $height);
		
		$thumb = imagecreatetruecolor(100 ,74);
		
		$transparent = imagecolorallocate($thumb, 0, 0, 0);
		// Hacer el fondo transparente
		imagecolortransparent($thumb, $transparent);
		
		ImageCopyResized($thumb, $img, $thumbnailInfo['x'], $thumbnailInfo['y'], 0, 0, $thumbnailInfo['newWidth'], $thumbnailInfo['newHeight'], $width, $height);

		if (!imagepng ($thumb, $dir)) {
			throw new InvalidArgumentException('thumb could not be saved on the server');
		}		
	}
	
	protected function getCreateThumbnailInfo($width, $height)
	{
		$thumbnail = $width/$height;
		
		if ($thumbnail >= 1.35) {
			$newHeight = (100/$width) * $height;;
			$thumbnailInfo = array('newWidth' => 100, 
								   'newHeight' => $newHeight,
								   'y' => (74-$newHeight)/2,
								   'x' => 0);
		}
		else if ($thumbnail < 1.35) {
			$newWidth = (74/$height) * $width;;
			$thumbnailInfo = array('newWidth' => $newWidth, 
								   'newHeight' => 74,
								   'y' => 0,
								   'x' => (100-$newWidth)/2);
		}
		return $thumbnailInfo;
	}
}