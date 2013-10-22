<?php
class AssetController extends ControllerBase
{
	const MAX_FILE_SIZE = 10000000;
	
	
	public function uploadAction ()
	{
		if (empty($_FILES['file']['name'])) {
			$this->flashSession->error("No ha enviado ningún archivo");
		}
		else {
			$this->idAccount = $this->user->account->idAccount;
			$this->file = $_FILES['file'];
			try {
				$this->processFile();
				$asset = $this->saveImage();
			} 
			catch (InvalidArgumentException $e) {
				return $this->flashSession->error("Ha ocurrido un error intente de nuevo");
			}
			
			$array = array(
				'filelink' => "/emarketing/asset/show/" .$asset->idAsset
			);
			echo stripslashes(json_encode($array));
		}
	}
	
	private function processFile () 
	{	
		$name = $this->file['name']; 
		$size = $this->file['size']; 
		
		$ext= "%\.(gif|jpe?g|png)$%i";
		
		$isValid = preg_match($ext, $name);
		
		if ($size > self::MAX_FILE_SIZE) {
			throw new InvalidArgumentException('File size exceeds maximum: ' . self::MAX_FILE_SIZE . ' bytes');
		}
		else if (!$isValid) {
			throw new InvalidArgumentException('Invalid extension for file...');
		}
	}
	
	private function saveImage ()
	{
		$dir = $this->asset->dir . $this->idAccount . "/images/" ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		
		$ext = $this->returnExt($this->file['name']);
		$asset = $this->saveAssetInDb();
		
		$nameThumb = $asset->idAsset . "_thumb." .$ext;
		$nameImage = $asset->idAsset . "." .$ext;
		$dirThumb = $dir . $nameThumb;
		$dirImage = $dir . $nameImage;
		
		if (!move_uploaded_file($this->file['tmp_name'], $dirImage)){ 
			throw new InvalidArgumentException('Image could not be uploaded on the server');
		}
		else {
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
			
			if (!imagejpeg($thumb, $dirThumb)) {
				throw new InvalidArgumentException('thumb could not be saved on the server');
			}
			return $asset;
		}
	}
	
	private function returnExt ($name) 
	{
		$fileName = strtolower($name);
		$tmp = (explode('.', $fileName));
		$ext = end($tmp);
		$ext = strtolower($ext);
		
		return $ext;
	}
	
	private function saveAssetInDb () 
	{
		$info = getimagesize($this->file['tmp_name']);
		$dimensions = $info[0] . " x " . $info[1];
		
		$asset = new Asset();

		$asset->idAccount = $this->idAccount;
		$asset->fileName = $this->file['name'];
		$asset->fileSize = $this->file['size'];
		$asset->dimensions = $dimensions;
		$asset->type = $this->file['type'];
		$asset->createdon = time();

		if (!$asset->save()) {
			foreach ($asset->getMessages() as $msg) {
				$this->flashSession->error($msg);
				throw new InvalidArgumentException('could not be saved on the database');
			}
		}
		return $asset;
	}
	
	public function listAction () 
	{
		$log = $this->logger;
		$this->view->disable();
		
		$assets = Asset::find(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $this->user->account->idAccount)
		));
		
		if (!$assets) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontroraron la imágenes!!');
		}
		
		$jsonImage = array();
		foreach ($assets as $asset) {
			$img = "/emarketing/asset/show/" . $asset->idAsset;
			$thumb ="/emarketing/asset/thumbnail/" . $asset->idAsset;
			$title = $asset->fileName;
			
			$jsonImage[] = array ('thumb' => $thumb, 
								'image' => $img,
								'title' => $title);
		}
		
		$log->log("Este es jason: " . print_r($jsonImage, true));
		echo stripslashes(json_encode($jsonImage));
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
}
