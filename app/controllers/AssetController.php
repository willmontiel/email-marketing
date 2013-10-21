<?php
class AssetController extends ControllerBase
{
	const MAX_FILE_SIZE = 10000000;
	
	
	public function uploadAction ()
	{
		$log = $this->logger;
		
		if (empty($_FILES['file']['name'])) {
			$this->flashSession->error("No ha enviado ningún archivo");
			$log->log("No subió");
		}
		else {
			$this->idAccount = $this->user->account->idAccount;
			$this->file = $_FILES['file'];
			try {
				$dir = $this->processFile();
			} 
			catch (InvalidArgumentException $e) {
				$log->log("Error");
			}
			
			$info = getimagesize($dir);
			$dimensions = $info[0] . " x " . $info[1];
			$log->log("Dimensiones: " .$dimensions);
			$log->log("Guardar imagen");
			$asset = new Asset();
		
			$asset->idAccount = $this->idAccount;
			$asset->fileName = $this->file['name'];
			$asset->fileSize = $this->file['size'];
			$asset->dimensions = $dimensions;
			$asset->type = $this->file['type'];
			$asset->createdon = time();

			if (!$asset->save()) {
				foreach ($asset->getMessages() as $msg) {
					$log->log("Error: ". $msg);
					$this->flashSession->error($msg);
				}
			}
			
			$array = array(
				'filelink' => "/emarketing/asset/show/" .$asset->idAsset
			);
			
			echo stripslashes(json_encode($array));
//			return $this->setJsonResponse(array('filelink' => $img), 201, 'Success');
			
		}
	}
	
	private function processFile () 
	{	
		$log = $this->logger;
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

		$save = $this->saveImage();
		
		if (!$save) {
			throw new InvalidArgumentException('could not be saved on the server');
		}
		
		return $save;
	}
	
	private function saveImage ()
	{
		$log = $this->logger;
		
		$dir = $this->asset->dir . $this->idAccount . "/images/" ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$fileName = strtolower($this->file['name']);
		$tmp = (explode('.', $fileName));
		$ext = end($tmp);
		$ext = strtolower($ext);
		
		$name = $this->idAccount . "_" . uniqid() . "." .$ext;
		$dir .= $name;
		if (!move_uploaded_file($this->file['tmp_name'], $dir)){ 
			return false;
		}
		else {
			switch ($ext) {
				case "jpg":
				case "jpeg":
					$img = @imagecreatefromjpeg($dir);
					break;

				case "png":
					$img = @imagecreatefrompng($dir);
					break;

				case "gif":
					$img = @imagecreatefromgif($dir);
					break;
			}

			$width = imagesx ($img);
			$heigh =imagesy($img);
			$thumb = imagecreatetruecolor(100 ,100);
			
			ImageCopyResized($img, $thumb, 0, 0, 0, 0, 100, 100, $width, $heigh);
			$dirThumb = $this->asset->dir . $this->idAccount . "/images/" . "thumb_" . uniqid() . "." .$ext;
			$log->log("Imagen: " . $dirThumb);
			imagejpeg($img, $dirThumb);
			
			return $dir;
		}
	}

	public function listAction () 
	{
		
		
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
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la imágen!!');
		}
		
		$img = $this->asset->dir . $this->user->account->idAccount . "/images/" . $asset->fileName;
		
		$this->response->setHeader("Content-Type", $asset->type);
		$this->response->setHeader("Content-Length", $asset->size);
		
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function thumbAction () 
	{
		
	}
}
