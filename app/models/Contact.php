<?php
class Contact extends \Phalcon\Mvc\Model
{
	public $unsubscribed;
	public $status;
	public $subscribedon;
	public $ipSubscribed;
	public $ipActivated;
	
	public function initialize()
    {
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idEmail", "Email", "idEmail", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
		
		/* Inicializacion de valores de campos */
		$this->unsubscribed = 0;
		$this->status = 0;
		$this->subscribedon = 0;
		$this->spam = 0;
		$this->bounced = 0;
    }
	
	/**
	 * Se ejecuta antes de la insercion
	 */
	public function beforeCreate()
    {
		// Asignar la fecha y hora de creacion del registro
        $this->createdon = time();
        $this->updatedon = time();
		if ($this->subscribedon == 0) {
			$this->subscribedon = $this->createdon;
		}
    }

	/**
	 * Se ejecuta antes de cada update
	 */
    public function beforeUpdate()
    {
		// Asignar fecha y hora de ultima actualizacion
        $this->updatedon = time();
    }	
}
