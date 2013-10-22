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
				$assetObj->createImage($name, $size, $type, $tmp_dir);
			} 
			catch (InvalidArgumentException $e) {
				return $this->setJsonResponse(
					array(
						'error' => 'Ha ocurrido un error mientras se cargaba la imágen, por favor asegurese
									de que el archivo que intenta subir realmente sea una imágen (jpeg, jpg, gif, png)
									y tenga un peso menor a 10 MB'
						)
					, 400 , 'Error en archivo!');
			}
			
			$array = array(
				'filelink' => $assetObj->getUrlImage()
			);
			echo stripslashes(json_encode($array));
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
		$log = $this->logger;
		$this->view->disable();
		
		$a = new AssetObj($this->user->account);
		$assets = $a->allAssetInAccount();
//		$log->log("Este es jason: " . print_r($assets , true));
//		$assets = Asset::find(array(
//			"conditions" => "idAccount = ?1",
//			"bind" => array(1 => $this->user->account->idAccount)
//		));
		
		if (!$assets) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontroraron la imágenes!!');
		}
		
		$jsonImage = array();
		foreach ($assets as $asset) {
			$img = $a->getUrlImage($asset);
			
			$thumb = $a->getUrlThumbnail($asset);
			if (!file_exists($thumb)) {
				$a->createThumbnail($thumb, $img, "jpg");
			}
			
			$title = $asset->fileName;
			
			$log->log("Este es thumb: " . $thumb);
			$log->log("Este es img: " . $img);
			
			$jsonImage[] = array ('thumb' => $thumb, 
								'image' => $img,
								'title' => $title);
		}
		$log->log("Este es jason: " . print_r($jsonImage , true));
//		echo stripslashes(json_encode($jsonImage));
	}
	
	public function showAction ($idAsset) 
	{
		$this->view->disable();
		$idAccount = $this->user->account->idAccount;
		
		$asset = Asset::findFirst(array(
			"conditions" => "idAccount = ?1 AND idAsset = ?2",
			"bind" => array(1 => $idAccount,
							2 => $idAsset)
		));
		
		if (!$asset) {
			return $this->setJsonResponse(array('status' => 'not found'), 404, 'No se encontro la imágen!!');
		}
		
		$ext = $this->returnExt($asset->fileName);
		
		$img = $this->asset->dir . $this->user->account->idAccount . "/images/" . $asset->idAsset . "." .$ext;
		
		$this->response->setHeader("Content-Type", $asset->type);
		$this->response->setHeader("Content-Length", $asset->size);
		
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function thumbnailAction ($idAsset) 
	{
		$log = $this->logger;
		$this->view->disable();
		$idAccount = $this->user->account->idAccount;
		
		$asset = Asset::findFirst(array(
			"conditions" => "idAccount = ?1 AND idAsset = ?2",
			"bind" => array(1 => $idAccount,
							2 => $idAsset)
		));
		
		if (!$asset) {
			return $this->setJsonResponse(array('status' => 'not found'), 404, 'No se encontro la imágen!!');
		}
		
		$ext = $this->returnExt($asset->fileName);
		
		$img = $this->asset->dir . $this->user->account->idAccount . "/images/" . $asset->idAsset . "_thumb." .$ext;
		
		$this->response->setHeader("Content-Type", $asset->type);
		$this->response->setHeader("Content-Length", $asset->size);
		
		return $this->response->setContent(file_get_contents($img));
	}
	
	private function returnExt ($name) 
	{
		$fileName = strtolower($name);
		$tmp = (explode('.', $fileName));
		$ext = end($tmp);
		$ext = strtolower($ext);
		
		return $ext;
	}
}
