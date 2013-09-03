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
			'alias' => 'Email'
        ));

		$this->hasMany("idContact", "Coxcl", "idContact", array(
			'alias' => 'Lists'
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
	
	
	public static function findContactsInList(Contactlist $list, $conditions = null, $bind = null, $limits = null) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT Contact.* FROM Contact JOIN Coxcl WHERE idContactlist = :idcontactlist:';
		if ($conditions != null) {
			$phql .= ' AND ' . $conditions;
			$options = $bind;
		}
		if ($limits != null && is_array($limits) && isset($limits['number']) ) {
			$number = $limits['number'];
			$start = isset($limits['offset'])?$limits['offset']:0;
			$phql .= ' LIMIT ' . $number . ' OFFSET ' . $start;
		}
		$options['idcontactlist'] = $list->idContactlist;
		$query = $mm->executeQuery($phql, $options);
		
		return $query;
		
	}
	
	public static function countContactsInList(Contactlist $list, $conditions = null, $bind = null) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT COUNT(*) cnt FROM Contact JOIN Coxcl WHERE idContactlist = :idcontactlist:';
		if ($conditions != null) {
			$phql .= ' AND ' . $conditions;
			$options = $bind;
		}
		$options['idcontactlist'] = $list->idContactlist;
		$query = $mm->executeQuery($phql, $options);
		
		if ($query) {
			return $query[0]->cnt;
		}
		return 0;
		
	}
	
}
