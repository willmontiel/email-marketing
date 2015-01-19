<?php

class CodificXml implements IElementProcessorRow 
{
	private $xmldoc;
	private $root;
	private $appPath;
	private $pdf;
	private $logger;
	
	public function __construct()
	{
		$this->xmldoc = new DomDocument();
		$this->xmldoc->formatOutput = true;
		$this->root = $this->xmldoc->createElement('documentos');
		$this->root = $this->xmldoc->appendChild($this->root);
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->pdf = Phalcon\DI::getDefault()->get('pdf');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}


	/**
	 * @param $data array
	 * @return string
	 */
	public function processline($numRow, $row)
	{	
		//Convertimos los caracteres del nombre en mayuscula y quitamos las tildes
		$row[1] = $this->convertirMayusculaSinTilde($row[1]);
		$row[2] = $this->convertirMayusculaSinTilde($row[2]);
		
		// Guardamos la informacion en un array para generar el XML
		$extracto = $this->crearelemento($this->xmldoc, $this->root, 'documento');

		$primary = $this->crearelemento($this->xmldoc, $extracto, 'primarydata');
		
		$this->createTextNode($this->xmldoc, $primary, 'id', $row[0]);
		$this->createTextNode($this->xmldoc, $primary, 'name', $row[1]);
		$this->createTextNode($this->xmldoc, $primary, 'lastname', $row[2]);
		$this->createTextNode($this->xmldoc, $primary, 'email', $row[3]);
		$this->createTextNode($this->xmldoc, $primary, 'phone', $row[4]);
		$this->createTextNode($this->xmldoc, $primary, 'address', $row[5]);
		$this->createTextNode($this->xmldoc, $primary, 'city', $row[6]);
		$this->createTextNode($this->xmldoc, $primary, 'state', $row[6]);
//		$this->createTextNode($this->xmldoc, $primary, 'barcode', $this->crearBarcode($row[7]));
		$this->createTextNode($this->xmldoc, $primary, 'barcode', $row[7]);
		$this->createTextNode($this->xmldoc, $primary, 'password', $row[8]);


		$secondarydata = $this->crearelemento($this->xmldoc, $extracto, 'secondarydata');
		
		$total = count($row);
		if ($total > 9) {
			for ($i = 9; $i < $total; $i++) {
				$this->createTextNode($this->xmldoc, $secondarydata, "field{$i}", $row[$i]);
			}
		}
	}
	
	
	private function createTextNode($doc, $section, $key, $value)
	{
		$obj = $this->crearelemento($doc, $section, $key);
		$this->crearnodo($doc, $obj, $value);
	}
	
	public function save($pdf)
	{	
		$source = "{$this->appPath->path}{$this->pdf->sourcebatch}/{$pdf->idAccount}/";
		if (!file_exists($source)) {
			mkdir($source, 0777, true);
		}
		
		$this->xmldoc->save( "{$source}/{$pdf->idPdfbatch}.xml");
	}
	
	private function crearnodo($doc, $node, $text)
	{
		$nodo = $doc->createTextNode($text);
		$node->appendChild($nodo);
	}

	private function crearelemento($doc, $parent, $childname)
	{
		$element = $doc->createElement($childname);
		return $parent->appendChild($element);
	}
	
	private function crearBarcode ($data) {
		$parte1 = '415' . $data[1];
		
		$parte2 = $this->largotexto($data[2], 22);
		$parte2 = '8020' . $parte2;
		
		$parte3 = $this->largotexto($data[24], 14);
		$parte3 = '3900' . $parte3;
			
		return ($parte1. $parte2 .  mb_convert_encoding('&#241;', 'UTF-8', 'HTML-ENTITIES') . $parte3);
	}
	
	//para validar que el texto siempre tenga 22 caracteres
	private function largotexto($texto, $logitud) {
		$texto = str_replace("-", "", $texto);
		$largo = strlen($texto);
		$diferencia = ($logitud - $largo);
		
		if ($diferencia > 0) {
			$texto = str_pad($texto, $logitud, "0", STR_PAD_LEFT);
		}
		return $texto;
	}
	
	private function convertirMayusculaSinTilde($subject)
	{
		$search  = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ñ');
		$replace = array('A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'N', 'N');
		$txtResult = str_ireplace($search, $replace, $subject);
		return strtoupper($txtResult);
	}
	
}
