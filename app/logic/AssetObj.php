<?php
class AssetObj
{
	const MAX_FILE_SIZE = 1000000;
	protected $log = null;
	
	function __construct(Account $account) 
	{
		$this->idAccount = $account->idAccount;
		$this->log = new Phalcon\Logger\Adapter\File("../app/logs/debug.log");
		
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		
		$this->assetsrv = $di['asset'];
		$this->url = $di['url'];
	}
	
	/**
	 * Función que encarga de crear imagen, thumbnail y guardar en la base de datos
	 * @param string $name
	 * @param int $size
	 * @param string $type
	 * @param string $tmp_dir
	 */
	public function createImage($name, $type, $tmp_dir, $size = null)
	{
		try {
			$this->validateFile($name, $size);
			$this->saveImageFile($name, $size, $type, $tmp_dir);
		}
		catch (InvalidArgumentException $e) {
			throw new InvalidArgumentException('we have a error...');
		}
	}
	
	/**
	 * Funcion que valida que el archivo este correcto
	 * @param string $name
	 * @param string $size
	 * @throws InvalidArgumentException
	 */
	protected function validateFile($name, $size) 
	{	
		$ext= "%\.(gif|jpe?g|png)$%i";
		
		$isValid = preg_match($ext, $name);
		
		if ($size > self::MAX_FILE_SIZE) {
			throw new InvalidArgumentException('File size exceeds maximum: ' . self::MAX_FILE_SIZE . ' bytes');
		}
		else if (!$isValid) {
			throw new InvalidArgumentException('Invalid extension for file...');
		}
	}
	
	
	protected function saveImageFile($name, $size, $type, $tmp_dir)
	{
		$dir = $this->assetsrv->dir . $this->idAccount . "/images/" ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$this->saveAssetInDb($name, $size, $type, $tmp_dir);
		
		$nameImage = $this->asset->idAsset . "." .$ext;
		$dirImage = $dir . $nameImage;
		
		if (!move_uploaded_file($tmp_dir, $dirImage)){ 
			throw new InvalidArgumentException('Image could not be uploaded on the server');
		}
		
		$nameThumb = $this->asset->idAsset . "_thumb.jpeg";
		$dirThumb = $dir . $nameThumb;
		$this->createThumbnail($dirThumb, $dirImage, $ext);
	}
	
	protected function saveAssetInDb($name, $size, $type, $tmp_dir) 
	{
		$info = getimagesize($tmp_dir);
		$dimensions = $info[0] . " x " . $info[1];
		
		$asset = new Asset();

		$asset->idAccount = $this->idAccount;
		$asset->fileName = $name;
		$asset->fileSize = $size;
		$asset->dimensions = $dimensions;
		$asset->type = $type;
		$asset->createdon = time();

		if (!$asset->save()) {
			throw new InvalidArgumentException('could not be saved on the database');
		}
		$this->asset = $asset;
	}
	
	public function createThumbnail($dirThumb, $dirImage, $ext)
	{
		switch ($ext) {
			case "jpg":
			case "jpeg":
				$img = @imagecreatefromjpeg($dirImage);
				break;

			case "png":
				$img = @imagecreatefrompng($dirImage);
				break;

			case "gif":
				$img = @imagecreatefromgif($dirImage);
				break;
		}

		$width = imagesx ($img);
		$heigh =imagesy($img);
		$thumb = imagecreatetruecolor(100 ,74);

		ImageCopyResized($thumb, $img, 0, 0, 0, 0, 100, 74, $width, $heigh);

		if (!imagejpeg($thumb, $dirThumb, 50)) {
			throw new InvalidArgumentException('thumb could not be saved on the server');
		}		
	}
	
	public static function findAllAssetsInAccount(Account $account)
	{
		$assets = Asset::findAllAssetsInAccount($account);
		$aobjs = array();
		foreach ($assets as $a) {
			$obj = new AssetObj($account);
			$obj->setAsset($a);
			$aobjs[] = $obj;
		}
		
		return $aobjs;
	}
	
	protected function setAsset(Asset $a)
	{
		$this->asset = $a;
	}

	public function getImagePrivateUrl()
	{
		$urlImage = $this->url->get('asset/show') . '/' . $this->asset->idAsset;
		return $urlImage;
	}
	
	public function getThumbnailUrl()
	{
		$urlThumbnail = $this->url->get('asset/thumbnail')  . '/' . $this->asset->idAsset;
		return $urlThumbnail;
	}
	
	public function getFileName()
	{
		return $this->asset->fileName;
	}
}