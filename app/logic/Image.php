<?php
class Image
{
	function __construct(Account $account) 
	{
		$this->account = $account;
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->assetsrv = $di['asset'];
	}
	
	/**
	 * funcion que guarda una imagen en el servidor, debe ser menor a 10 MB
	 * @param Asset $asset objeto asset para tomar el id, que sera el nombre del thumbnail
	 * @param string $name nombre de la imagen a procesar con extension
	 * @param string $tmp_dir ubicación de la imágen a procesar
	 * @return string retorna la direccion de en la que se guardo la imágen
	 * @throws InvalidArgumentException
	 */
	public function saveImage(Asset $asset, $name, $tmp_dir)
	{
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/images/' ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		
		$dir .= $asset->idAsset . '.' .$ext;
		
		if (!move_uploaded_file($tmp_dir, $dir)){ 
			throw new InvalidArgumentException('Image could not be uploaded on the server');
		}
		
		return $dir;
	}
}