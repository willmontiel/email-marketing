<?php
class AssetObj
{
	const MAX_FILE_SIZE = 1000000;
	protected $log = null;
	
	function __construct(Account $account) 
	{
		$this->account = $account;
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		
		$this->assetsrv = $di['asset'];
		$this->url = $di['url'];
	}
	
	/**
	 * Función que se encarga de gestionar la creación de una imagen y thumbnail en el servidor y un registro en la base de datos
	 * @param string $name
	 * @param int $size
	 * @param string $type
	 * @param string $tmp_dir
	 */
	public function createImage($name, $type, $tmp_dir, $size = null)
	{
		try {
			$this->validateFile($name, $size);
			$this->saveAssetInDb($name, $size, $type, $tmp_dir);
			
			$image = new Image($this->account);
			$dirImage = $image->saveImage($this->asset, $name, $tmp_dir);
			
			$thumbnail = new Thumbnail($this->account);
			$thumbnail->createThumbnail($this->asset, $dirImage, $name);
		}
		catch (InvalidArgumentException $e) {
			throw new InvalidArgumentException('we have a error...');
		}
	}
	
	public function createGlobalImage($name, $type, $tmp_dir, $size = null)
	{
		try {
			$this->validateFile($name, $size);
			$this->saveGloblaImageInDb($name, $size, $type, $tmp_dir);
			
			$image = new Image($this->account);
			$dirImage = $image->saveImage($this->asset, $name, $tmp_dir);
			
			$thumbnail = new Thumbnail($this->account);
			$thumbnail->createThumbnail($this->asset, $dirImage, $name);
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
		$ext= '%\.(gif|jpe?g|png)$%i';
		
		$isValid = preg_match($ext, $name);
		
		if ($size > self::MAX_FILE_SIZE) {
			throw new InvalidArgumentException('File size exceeds maximum: ' . self::MAX_FILE_SIZE . ' bytes');
		}
		else if (!$isValid) {
			throw new InvalidArgumentException('Invalid extension for file...');
		}
	}
	
	/**
	 * Funcion que guarda un información de un asset en las base de datos y crea un objeto asset
	 * @param string $name nombre con extensión del archivo
	 * @param int $size peso del archivo
	 * @param string $type tipo mime del archivo
	 * @param string $tmp_dir ubicación de archivo
	 * @throws InvalidArgumentException
	 */
	protected function saveAssetInDb($name, $size, $type, $tmp_dir) 
	{
		$info = getimagesize($tmp_dir);
		$dimensions = $info[0] . ' x ' . $info[1];
		
		$asset = new Asset();

		$asset->idAccount = $this->account->idAccount;
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
	
	/**
	 * funcion que retorna un objeto assetObj
	 * @param Account $account
	 * @return \AssetObj
	 */
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
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/images/';
		$thumb = $dir . $this->asset->idAsset . '_thumb.png';
		if (!file_exists($thumb)) {
			$ext = pathinfo( $this->asset->fileName, PATHINFO_EXTENSION);
			$image = $dir . $this->asset->idAsset . '.' . $ext;
			$thumbnail = new Thumbnail($this->account);
			$thumbnail->createThumbnail($this->asset, $image, $this->asset->fileName);
		}
		
		$urlThumbnail = $this->url->get('asset/thumbnail')  . '/' . $this->asset->idAsset;
		return $urlThumbnail;
	}
	
	public function getFileName()
	{
		return $this->asset->fileName;
	}
	
	public function getIdAsset()
	{
		return $this->asset->idAsset;
	}
}