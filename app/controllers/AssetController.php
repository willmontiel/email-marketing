<?php
class AssetController extends ControllerBase
{	
	public function uploadAction ()
	{
		$space = $this->getSpaceInAccount();
		
		if (!$space) {
			return $this->setJsonResponse(
					array(
						'error' => 'Ha sobrepasaso el limite de espacio en disco. para liberar espacio en disco
							        elimine imágenes o archivos que considere innecesarios'
						)
					, 401 , 'Ha sobrepasado el limite de espacio en disco!');
		}
		else if (empty($_FILES['file']['name'])) {
			return $this->setJsonResponse(
					array(
						'error' => 'No ha enviado ningún archivo o ha enviado un archivo no soportado, por favor verifique la información'
						)
					, 400 , 'Archivo vacio o incorrecto');
		}
		else {
			$name = $_FILES['file']['name'];
			$size = $_FILES['file']['size'];
			$type = $_FILES['file']['type'];
			$tmp_dir = $_FILES['file']['tmp_name'];
			
			try {
				$assetObj = new AssetObj($this->user->account);
				$assetObj->createImage($name, $type, $tmp_dir, $size);
			} 
			catch (InvalidArgumentException $e) {
				return $this->setJsonResponse(
					array(
						'error' => 'Ha ocurrido un error mientras se cargaba la imágen, por favor asegurese
									de que el archivo que intenta subir realmente sea una imágen (jpeg, jpg, gif, png)
									y tenga un tamaño de archivo menor a 10 MB'
						)
					, 400 , 'Error en archivo!');
			}
			
			$array = array(
				'filelink' => $assetObj->getImagePrivateUrl(),
				'thumb' => $assetObj->getThumbnailUrl(),
				'title' => $assetObj->getFileName(),
				'id' => $assetObj->getIdAsset()
			);
			return $this->setJsonResponse($array);
		}
	}
	
	
	public function getSpaceInAccount()
	{
		$account = $this->user->account;
		
		$phql = "SELECT SUM(asset.fileSize) cnt FROM asset WHERE asset.idAccount = :idAccount:";
		$result = $this->modelsManager->executeQuery($phql, array('idAccount' => $account->idAccount));
		
		$space = ($result->getFirst()->cnt / 1048576 );
		
		if ($space >= $account->fileSpace) {
			return false;
		}
		return true;
	}
	
	
	
	public function listAction () 
	{
		$assets = AssetObj::findAllAssetsInAccount($this->user->account);
		
		if (count($assets) < 1) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontraron la imágenes!!');
		}
		
		$jsonImage = array();
		foreach ($assets as $a) {
			$jsonImage[] = array ('thumb' => $a->getThumbnailUrl(), 
								'image' => $a->getImagePrivateUrl(),
								'title' => $a->getFileName());
		}
		return $this->setJsonResponse($jsonImage);
	}
	
	public function showAction ($idAsset) 
	{
		$idAccount = $this->user->account->idAccount;
		
		$asset = Asset::findFirst(array(
			"conditions" => "idAccount = ?1 AND idAsset = ?2",
			"bind" => array(1 => $idAccount,
							2 => $idAsset)
		));
		
		if (!$asset) {
			return $this->setJsonResponse(array('Error' => 'not found'), 404, 'No se encontro la imágen!!');
		}
		
		$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
		
		$img = $this->asset->dir . $this->user->account->idAccount . "/images/" . $asset->idAsset . "." .$ext;
		
		$this->response->setHeader("Content-Type", $asset->type);
//		$this->response->setHeader("Content-Length", $asset->size);
		
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function thumbnailAction ($idAsset) 
	{
		$idAccount = $this->user->account->idAccount;
		
		$asset = Asset::findFirst(array(
			"conditions" => "idAccount = ?1 AND idAsset = ?2",
			"bind" => array(1 => $idAccount,
							2 => $idAsset)
		));
		
		if (!$asset) {
			return $this->setJsonResponse(array('Error' => 'not found'), 404, 'No se encontró la imágen!!');
		}
		
		$img = $this->asset->dir . $this->user->account->idAccount . "/images/" . $asset->idAsset . "_thumb.png";
	
		$this->response->setHeader("Content-Type", "image/png");
//		$this->response->setHeader("Content-Length", $asset->size);
		
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}

}
