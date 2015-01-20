<?php

class ProcessFile
{
	protected $lineprocessors;
	
	public function __construct()
	{
		$this->lineprocessors = array();
	}

	public function addElementProcessor(IElementProcessorRow $obj)
	{
		$this->lineprocessors[] = $obj;
	}

	public function setSourceFile($inputFilename)
	{
		$this->inputFilename = $inputFilename;
	}

	public function process()
	{	
		// Abrimos el archivo csv para lectura
		$inputFile  = fopen($this->inputFilename, 'rt');
		
		if (!$inputFile)
			return false;

		//conseguimos los encabezados
		fgetcsv($inputFile);
		
		$numRow = 1;
		// Loop para leer cada fila del archivo
		while (($row = fgetcsv($inputFile)) !== FALSE) {

			foreach ($this->lineprocessors as $obj) {
				$pagenum = str_pad($numRow, 4, '0', STR_PAD_LEFT);
				$obj->processline($pagenum, $row);
			}
			
			$numRow++;
		}
		
		return true;
	}
}