<?php

namespace EmailMarketing\General\Misc;

class PdfManager 
{
	private $logger;
	private $source;
	private $destination;
	private $mail;
	private $total = 0;

	public function __construct()
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->logger = $di['logger'];
	}
	
	public function setMail(\Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function setSource($source)
	{
		$this->source = $source;
	}
	
	public function setDestination($destination)
	{
		$this->destination = $destination;
	}
	
	public function extract()
	{
		//Creamos un objeto de la clase ZipArchive()
		$enzip = new \ZipArchive();
		
		//Abrimos el archivo a descomprimir
		$enzip->open($this->source);
		
		//Extraemos el contenido del archivo dentro de la carpeta especificada
		$extracted = $enzip->extractTo($this->destination);

		/* Si el archivo se extrajo correctamente listamos los nombres de los
		 * archivos que contenia de lo contrario mostramos un mensaje de error
		*/
		if(!$extracted) {
			throw new Exception("Error while unziping file!");
		}
		
		$this->total = $enzip->numFiles;
	}
	
	public function save()
	{
		$files = glob($this->destination . "{*.pdf}",GLOB_BRACE);
		
		$v = array();
		foreach ($files as $file) { 
			$path_parts = pathinfo($file);
			$size = filesize($file);
			$size = $size/1024;
			$v[] = "(null, {$this->mail->idMail}, 0, '{$path_parts['basename']}', {$size}, '{$path_parts['extension']}', " . time() .")"; 
		}
		
		if (count($v) > 0) {
			$values = implode(',', $v);
			
			$sql = "INSERT IGNORE INTO pdfmail (idPdfmail, idMail, idContact, name, size, type, createdon) 
					VALUES {$values}";
			try {
				$this->logger->log("SQL: {$sql}");		
				$executer = new \EmailMarketing\General\Misc\SQLExecuter();
				$executer->instanceDbAbstractLayer();
				$executer->setSQL($sql);
				$executer->executeAbstractLayer();
			}	
			catch (\Exception $ex) {
				$this->logger->log("Exception: {$ex}");
				throw new \Exception("Exception: {$ex}");
			}
			
		}
	}
	
	public function getTotal()
	{
		return $this->total;
	}
}