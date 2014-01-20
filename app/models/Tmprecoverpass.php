<?php
class Tmprecoverpass extends \Phalcon\Mvc\Model
{
	public $idUser;
	public function initialize()
	{
		$this->belongsTo("idUser", "User", "idUser", array(
            "foreignKey" => true,
        ));
	}
}