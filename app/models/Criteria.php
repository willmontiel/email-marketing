<?php
class Criteria extends \Phalcon\Mvc\Model
{
	public $idSegment;
	
	public function initialize()
	{
		$this->belongsTo("idSegment", "Segment", "idSegment", array(
			"foreignKey" => true
		));
	}
	
}