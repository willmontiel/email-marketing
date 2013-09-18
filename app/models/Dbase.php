<?php
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class Dbase extends Modelbase
{

    public $idAccount;
	public $idDbase;


	public function initialize()
    {
        $this->hasMany("idDbase", "Customfield", "idDbase", array('alias' => 'CustomFields'));
		
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));

        $this->hasMany("idDbase", "Contact", "idDbase", array('alias' => 'Contacts'));
		
		$this->hasMany("idDbase", "Contactlist", "idDbase");
    }
    
    public function getMessages()
    {
        $messages = array();
        foreach (parent::getMessages() as $message) {
            switch ($message->getType()) {
                case 'InvalidCreateAttempt':
                    $messages[] = 'The record cannot be created because it already exists';
                    break;
                case 'InvalidUpdateAttempt':
                    $messages[] = 'The record cannot be updated because it already exists';
                    break;
                case 'PresenceOf':
                    if ($message->getField() == 'name') {
                        $messages[] = 'Oye! Debes ingresar un nuevo nombre para tu Base de Datos';
                    }
                    else {
                        $messages[] = $message;
                    }
                    break;
            }
        }
        return $messages;
    }
	
	public function updateCountersInDbase()
	{
		$sql = "SELECT COUNT(*) AS cnt,	
					SUM( IF(c.status != 0, IF(c.unsubscribed = 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0),0)) AS activecnt,
					SUM( IF(c.unsubscribed != 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0)) AS unsubscribedcnt,
					SUM( IF(c.bounced != 0, IF(c.spam = 0,1,0),	0)) AS bouncedcnt,
					SUM( IF(c.spam != 0,1,0)) AS spamcnt
				FROM contact c
				WHERE c.idDbase = $this->idDbase";
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$r = $db->fetchAll($sql);
		
		$counter = array();
		
		foreach ($r as $cntr) {
			$counter[0] = $cntr['cnt'];
			$counter[1] = $cntr['activecnt'];
			$counter[2] = $cntr['unsubscribedcnt'];
			$counter[3] = $cntr['bouncedcnt'];
			$counter[4] = $cntr['spamcnt'];
		}

		$sql2 = "UPDATE dbase SET Ctotal = $counter[0], Cactive = $counter[1], Cunsubscribed = $counter[2], Cbounced = $counter[3], Cspam = $counter[4] WHERE idDbase = $this->idDbase";
		
		$db->begin();
		
		$db->execute($sql2);
		
		$db->commit();
	}
	
}