<?php

require_once 'IElementProcessor.php';

class GFbarcode implements IElementProcessor {
	private $_cod = null;
	private $_nrocred = null;
	private $_vlr = null;
	
	/**
	 * Metodo para procesar el texto
	 * @param int $coddiac indice del codigo
	 * @param int $nrocredit indice del numero de credito
	 * @param int $valor  indice del valor a pagar
	 */
	public function __construct($coddiac, $nrocredit, $valor) {
		$this->_cod = $coddiac;
		$this->_nrocred = $nrocredit;
		$this->_vlr = $valor;
	}
	
	/**
	 * @param $data array
	 * @return string
	 */
	public function process ($data) {
		
		$parte1 = '415' . $data[$this->_cod];
		
		$parte2 = $this->largotexto($data[$this->_nrocred], 22);
		$parte2 = '8020' . $parte2;
		
		$parte3 = $this->largotexto($data[$this->_vlr], 14);
		$parte3 = '3900' . $parte3;
			
		return ($parte1. $parte2 .  '&#241;' . $parte3);
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
	
}