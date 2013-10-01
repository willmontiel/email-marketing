<?php
class Sxc extends \Phalcon\Mvc\Model
{
	public $idContact;
	public $idSegment;
	
	public function initialize()
	{
		$this->belongsTo("idContact", "Contact", "idContact", array(
			"foreignKey" => true,
		));
		
		$this->belongsTo("idSegment", "Segment", "idSegment", array(
			"foreignKey" => true,
		));
	}
}
