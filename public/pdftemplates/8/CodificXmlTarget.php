<?php

require_once '../IElementProcessorRow.php';

class CodificXml implements IElementProcessorRow {

	
	public function __construct()
	{
		$this->xmldoc = new DomDocument();
		$this->xmldoc->formatOutput = true;
		$this->root = $this->xmldoc->createElement('documentos');
		$this->root = $this->xmldoc->appendChild($this->root);
	}


	/**
	 * @param $data array
	 * @return string
	 */
	public function processline($numRow, $row)
	{	
		//Convertimos los caracteres del nombre en mayuscula y quitamos las tildes
		$row[1] = $this->convertirMayusculaSinTilde($row[1]);
		
		// Guardamos la informacion en un array para generar el XML
		
		
		$extracto = $this->crearelemento($this->xmldoc, $this->root, 'documento');

		$header = $this->crearelemento($this->xmldoc, $extracto, 'infopersonal');
		
		$this->createTextNode($this->xmldoc, $header, 'titulo', $row[0]);
		$this->createTextNode($this->xmldoc, $header, 'nombre', $row[1]);
		$this->createTextNode($this->xmldoc, $header, 'documento', $row[2]);
		$this->createTextNode($this->xmldoc, $header, 'contrasena', $row[6]);


		$pagoant = $this->crearelemento($this->xmldoc, $extracto, 'expedicion');
		
		$this->createTextNode($this->xmldoc, $pagoant, 'mesexpedicion', $row[3]);
		$this->createTextNode($this->xmldoc, $pagoant, 'anoexpedicion', $row[4]);
		$this->createTextNode($this->xmldoc, $pagoant, 'ciudadexpedicion', $row[5]);
		
		$pagoant = $this->crearelemento($this->xmldoc, $extracto, 'barcode');
		
		$this->createTextNode($this->xmldoc, $pagoant, 'barcode', $row[7]);
		
	}
	
	
	private function createTextNode($doc, $section, $key, $value)
	{
		$obj = $this->crearelemento($doc, $section, $key);
		$this->crearnodo($doc, $obj, $value);
	}
	
	public function save($idenvio)
	{	
		
		// grabar archivo XML
		$this->xmldoc->save( "xml/{$idenvio}.xml");
		
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
	
	private function convertirMayusculaSinTilde($subject)
	{
		$search  = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ñ');
		$replace = array('A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'N', 'N');
		$txtResult = str_ireplace($search, $replace, $subject);
		return strtoupper($txtResult);
	}
	
}
