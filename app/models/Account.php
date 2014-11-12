<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\StringLength;
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class Account extends Modelbase
{
    public $idAccount;
	public $idUrlDomain;
	public $idMailClass;
	
    public function initialize()
    {
		$this->belongsTo("idUrlDomain", "Urldomain", "idUrlDomain",
			array("foreignKey" => true)
		);
		
		$this->belongsTo("idMailClass", "Mailclass", "idMailClass",
			array("foreignKey" => true)
		);
		
        $this->hasMany("idAccount", "User", "idAccount");
		$this->hasMany("idAccount", "Importfile","idAccount");
		$this->hasMany("idAccount", "Importprocess", "idAccount");
		$this->hasMany("idAccount", "Asset", "idAccount");
		$this->hasMany("idAccount", "Dbase", "idAccount", array('alias' => 'Dbases'));
		$this->hasMany("idAccount", "Template", "idAccount");
		$this->hasMany("idAccount", "Maillink", "idAccount");
		$this->hasMany("idAccount", "Socialnetwork", "idAccount");
		$this->hasMany("idAccount", "Remittent", "idAccount");
		$this->hasMany("idAccount", "Accountinghistory", "idAccount");
		$this->hasMany("idAccount", "Autoresponder", "idAccount");
		$this->hasMany("idAccount", "Score", "idAccount");
		$this->hasMany("idAccount", "Scorehistory", "idAccount");
    }
    
    public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "companyName",
				"message" => "Debe ingresar un nombre para la cuenta"
            )
        ));
		
		$this->validate(new StringLength(
			array(
				"field" => "companyName",
				"min" => 4,
				"message" => "El nombre de la cuenta es muy corto, debe tener al menos 4 caracteres"
			)
		));
		
		$this->validate(new PresenceOf(
            array(
                "field"   => "virtualMta",
				"message" => "Debe ingresar un nombre para el MTA virtual de la cuenta"
            )
        ));
		
		$this->validate(new Uniqueness(
				array(
                "field"   => "companyName",
                "message" => "El nombre de la cuenta ya existe, por favor verifique la información"
        )));
		
        $this->validate(new PresenceOf(
            array(
                "field"   => "fileSpace",
                "message" => "Debe indicar la cuota de espacio para archivos"
            )
        ));
        
        $this->validate(new PresenceOf(
            array(
                "field"   => "messageLimit",
                "message" => "Debe indicar el limite de mensajes"
            )
        ));
		
		$this->validate(new PresenceOf(
            array(
                "field"   => "contactLimit",
                "message" => "Debe indicar la cantidad total de mensajes"
            )
        ));

        if ($this->validationHasFailed() == true) {
			return false;
        }
    }
	
	
	public function countActiveContactsInAccount()
	{
		$sql = 'SELECT SUM(Dbase.Cactive) cnt FROM Dbase WHERE Dbase.idAccount = :idAccount:';
		$r =  Phalcon\DI::getDefault()->get('modelsManager')->executeQuery($sql, array('idAccount' => $this->idAccount));
		if (!$r) {
			return 0;
		}
		return $r->getFirst()->cnt;
	}
			
	public function countTotalMessagesSent()
	{
		$t = time();
		$month = date('M', $t);
		$year = date('Y', $t);

		$time1 = strtotime("1 {$month} {$year}");
		$time2 = strtotime("+1 month", $time1);
		$time2 = strtotime("-1 day", $time2);
			
		$sql = "SELECT a.idAccount, COUNT(mc.idContact) AS total
					FROM Mail AS m
					LEFT JOIN Mxc AS mc ON ( mc.idMail = m.idMail ) 
					JOIN Account AS a ON ( a.idAccount = m.idAccount ) 
				WHERE m.finishedon >= {$time1}
					AND m.finishedon <= {$time2}
					AND m.status = 'sent'
					AND a.idAccount = :idAccount:
				GROUP BY 1 ";
		
		$r =  Phalcon\DI::getDefault()->get('modelsManager')->executeQuery($sql, array('idAccount' => $this->idAccount));
		
		if (!$r) {
			return 0;
		}
		return $r->getFirst()->total;
	}
           
}   
