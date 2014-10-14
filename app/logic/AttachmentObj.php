<?php

class AttachmentObj
{
	protected $logger;
	protected $account;
	protected $mail;
	protected $data;
	protected $attachment;

	protected $files_not_allowed = array (
		'ade','adp','bat','chm','cmd','com','cpl','exe','hta','ins','isp','jse','lib','lnk','mde','msc','msp',
		'ksh','msh','reg','mst','pif','scr','sct','shb','sys','vb','vbe','vbs','vxd','wsc','wsf','wsh','apk','app',
		'csh','gadget','js','zip','tar','tgz','taz','z','gz','rar'
	);
	
	function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->logger = $di['logger'];
		$this->assetsrv = $di['asset'];
		$this->uploadConfig = $di['uploadConfig'];
		$this->url = $di['url'];
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function setAttachment(Attachment $attachment)
	{
		$this->attachment = $attachment;
	}
	
	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new InvalidArgumentException("Error invalid file data");
		}
		
		$this->data = $data;
	}	
	
	public function uploadAttachment()
	{
		$this->validateFile();
		$this->moveFileToServer(true);
		$this->saveAttachmentInfoInDb(true);
	}
	
	public function deleteAttachment($db = true)
	{
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/attachments/' . $this->mail->idMail . '/';
		$attachment = $dir . $this->attachment->fileName;
		
		if (!unlink($attachment)) {
			throw new Exception('File could not delete from server!');
		}
		
		if (!rmdir($dir)) {
			throw new Exception('Dir could not delete from server!');
		}
		
		if ($db) {
			if (!$this->attachment->delete()) {
				foreach ($this->attachment->getMessages() as $msg) {
					$this->logger->log("Error while deleting attachment: {$msg}");
				}
				throw new \Exception("Could not delete attachment with idMail {$this->mail->idMail}");
			}
		}
	}
	
	public function cloneAttachment() 
	{
		$this->saveAttachmentInfoInDb(false);
		$this->moveFileToServer(false);
	}
	
	/**
	 * Esta función guarda los datos del archivo en la tabla attachment de la base de datos
	 * @throws InvalidArgumentException
	 */
	private function saveAttachmentInfoInDb($uploaded = true)
	{
		if ($uploaded) {
			$name = $this->data->fileName;
			$size = $this->data->fileSize;
			$type = $this->data->fileType;
		}
		else {
			$name = $this->attachment->fileName;
			$size = $this->attachment->fileSize;
			$type = $this->attachment->type;
		}
		
		$attachment = new Attachment();
		$attachment->idMail = $this->mail->idMail;
		$attachment->fileName = $name;
		$attachment->fileSize = $size;
		$attachment->type = $type;
		$attachment->createdon = time();
		
		if (!$attachment->save()) {
			foreach ($attachment->getMessages() as $msg) {
				$this->logger->log("Error while saving attachment: {$msg}");
			}
			throw new InvalidArgumentException('Info file could not be saved on database');
		}
	}
	
	/**
	 * Esta función mueve el archivo del directorio temporal al servidor como tal
	 * @throws InvalidArgumentException
	 */
	private function moveFileToServer($uploaded = true)
	{
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/attachments/' . $this->mail->idMail . '/';
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$name = ($uploaded == true ? $this->data->fileName : $this->attachment->fileName);
		
		$dir .= $name;
		
		if (!move_uploaded_file($this->data->tmpDir, $dir)){ 
			throw new InvalidArgumentException('File could not be uploaded on the server');
		}
	}
	
	
	/**
	 * Funcion que valida que el archivo este correcto
	 */
	private function validateFile() 
	{	
		$ext = implode('|', $this->files_not_allowed);
		$expr = "%\.({$ext})$%i";
		
		$isValid = preg_match($expr, $this->data->fileName);
		
		if ($isValid) {
			throw new InvalidArgumentException('Invalid extension for file...');
		}
		
		if ($this->data->fileSize > $this->uploadConfig->attachmentSize) {
			throw new InvalidArgumentException('File size exceeds maximum: ' . $this->uploadConfig->attachmentSize . ' bytes');
		}
	}
}