<?php
class Contactlist extends \Phalcon\Mvc\Model 
{
	public $idDbase;
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		
	}
	public function beforeCreate()
    {
        $this->createdon = time();
        $this->updatedon = time();
    }

    public function beforeUpdate()
    {
        $this->updatedon = time();
    }
}
