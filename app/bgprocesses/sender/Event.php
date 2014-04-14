<?php
class Event {
	public $code;
	public $type;
	public $data;
	
	public function __construct($type, $data = NULL, $code = NULL) {
		$this->type = $type;
		$this->data = $data;
		$this->code = ( $code != NULL ) ? $code : $data;
	}
}