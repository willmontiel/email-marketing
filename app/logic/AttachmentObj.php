<?php

class AttachmentObj
{
	const MAX_FILE_SIZE = 16000000;
	protected $logger;
	protected $account;
	protected $mail;
	protected $data;

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
		$this->moveFileToServer();
		$this->saveAttachmentInfoInDb();
	}
	
	/**
	 * Esta función guarda los datos del archivo en la tabla attachment de la base de datos
	 * @throws InvalidArgumentException
	 */
	private function saveAttachmentInfoInDb()
	{
		$attachment = new Attachment();
		$attachment->idMail = $this->mail->idMail;
		$attachment->fileName = $this->data->fileName;
		$attachment->fileSize = $this->data->fileSize;
		$attachment->type = $this->data->fileType;
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
	private function moveFileToServer()
	{
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/attachments/' . $this->mail->idMail . '/';
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$dir .= $this->data->fileName;
		
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
		
		if ($this->data->fileSize > self::MAX_FILE_SIZE) {
			throw new InvalidArgumentException('File size exceeds maximum: ' . self::MAX_FILE_SIZE . ' bytes');
		}
	}
}