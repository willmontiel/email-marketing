<?php
class Thumbnail
{
	function __construct(Account $account) 
	{
		$this->account = $account;
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->assetsrv = $di['asset'];
	}
	
	public function createThumbnail(Asset $asset, $dirImage, $name)
	{
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/images/' ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$dir .= $asset->idAsset . '_thumb.jpeg';
		
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
		$heigh =imagesy($img);
		$thumb = imagecreatetruecolor(100 ,74);

		ImageCopyResized($thumb, $img, 0, 0, 0, 0, 100, 74, $width, $heigh);

		if (!imagejpeg($thumb, $dir, 50)) {
			throw new InvalidArgumentException('thumb could not be saved on the server');
		}		
	}
}