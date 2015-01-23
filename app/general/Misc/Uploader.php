<?php

namespace EmailMarketing\General\Misc;

class Uploader 
{
	private $logger;
	private $data;
	private $dir;
	private $account;
	private $mail;
	private $folder;
	private $source;

	public function __construct()
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->dir = $di['asset'];
		$this->logger = $di['logger'];
	}

	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	public function setMail(\Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new \InvalidArgumentException("Error invalid data object...");
		}

		$this->data = $data;
	}

	
	public function validateExt($ext)
	{
		$e = implode('|', $ext);
		$expr = "%\.({$e})$%i";
		$isValid = preg_match($expr, $this->data->originalName);
		if (!$isValid) {
			throw new \InvalidArgumentException('Extensión de archivo invalida, por favor valide la información');
		}
	}
	
	/*
	* Esta función valida que el archivo que se intenta subir no sobrepase el tamaño máximo configurado en kilobytes
	*/
	public function validateSize($size)
	{
		$bytes = 1024*$size;
		$kb = $this->data->size/1024;
		if ($kb > $bytes) {
			throw new \InvalidArgumentException("El peso del archivo ({$kb} KB), excede el limite determinado ({$size} KB), por favor valide la información");
		}
	}
	
	public function uploadFile($dir)
	{
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$this->folder = $dir;
		
		$dir = $dir . $this->data->name;
		if (!move_uploaded_file($this->data->tmp_dir, $dir)){ 
			throw new \Exception('File could not be uploaded on the server');
		}
		
		$this->source = $dir;
	}

	
	public function decompressFile($source, $destination)
	{
		//Creamos un objeto de la clase ZipArchive()
		$enzip = new \ZipArchive();
		
		//Abrimos el archivo a descomprimir
		$enzip->open($source);
		
		//Extraemos el contenido del archivo dentro de la carpeta especificada
		$extracted = $enzip->extractTo($destination);

		/* Si el archivo se extrajo correctamente listamos los nombres de los
		 * archivos que contenia de lo contrario mostramos un mensaje de error
		*/
		if(!$extracted) {
			throw new Exception("Error while unziping file!");
		}
	}

	
	public function changeNameOfFile($source, $destination)
	{
		rename($source, $destination);
	}

	/**
	* Esta función elimina un archivo cargado en caso de que haya ocurrido un error
	*/
	public function deleteFileFromServer($dir)
	{
		unlink($dir);
	}
	
	public function getSource()
	{
		return $this->source;
	}
	
	public function getFolder()
	{
		return $this->folder;
	}
}