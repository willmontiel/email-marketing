<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
class Contactlist extends Modelbase 
{
	public $idDbase;
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		
	}
	
	public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "name",
				"message" => "Debes ingresar un nombre para la lista"
            )
        ));
	}
	
	public function beforeSave()
    {
        if ($this->description == NULL) {
			$this->description = "Sin Descripcion";
        }
    }
	
	public static function findContactListsInAccount(Account $account, $conditions = null, $bind = null, $limits = null) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT Contactlist.* FROM Contactlist JOIN Dbase WHERE idAccount = :idaccount:';
		if ($conditions != null) {
			$phql .= ' AND ' . $conditions;
			$options = $bind;
		}
		if ($limits != null && is_array($limits) && isset($limits['number']) ) {
			$number = $limits['number'];
			$start = isset($limits['offset'])?$limits['offset']:0;
			$phql .= ' LIMIT ' . $number . ' OFFSET ' . $start;
		}
		$options['idaccount'] = $account->idAccount;
		$query = $mm->executeQuery($phql, $options);
		
		return $query;
		
	}
	
	public static function countContactListsInAccount(Account $account, $conditions = null, $bind = null ) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT COUNT(*) cnt FROM Contactlist JOIN Dbase WHERE idAccount = :idaccount:';
		if ($conditions != null) {
			$phql .= ' AND ' . $conditions;
			$options = $bind;
		}
		$options['idaccount'] = $account->idAccount;
		$query = $mm->executeQuery($phql, $options);
		
		if ($query) {
			return $query[0]->cnt;
		}
		return 0;
		
	}

	public function getInactiveContacts()
	{
		return $this->Ctotal - ($this->Cactive + $this->Cunsubscribed + $this->Cbounced + $this->Cspam);
	}
	
	public function updateCountersInContactlist()
	{
		$sql = "SELECT COUNT(*) AS cnt,
					SUM( IF(c.status != 0, IF(c.unsubscribed = 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0),0)) AS activecnt,
					SUM( IF(c.unsubscribed != 0, IF(c.bounced = 0, IF(c.spam = 0,1,0), 0),0)) AS unsubscribedcnt,
					SUM( IF(c.bounced != 0, IF(c.spam = 0,1,0), 0)) AS bouncedcnt,
					SUM( IF(c.spam != 0,1,0)) AS spamcnt
				FROM contact c JOIN coxcl x ON (c.idContact = x.idContact)
				WHERE x.idContactlist = $this->idContactlist";
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$r = $db->fetchAll($sql);
		
		$counter = array();
		
		foreach ($r as $cntr) {
			$counter[0] = (isset($cntr['cnt']))?$cntr['cnt']:0;
			$counter[1] = (isset($cntr['activecnt']))?$cntr['activecnt']:0;
			$counter[2] = (isset($cntr['unsubscribedcnt']))?$cntr['unsubscribedcnt']:0;
			$counter[3] = (isset($cntr['bouncedcnt']))?$cntr['bouncedcnt']:0;
			$counter[4] = (isset($cntr['spamcnt']))?$cntr['spamcnt']:0;
		}

		$sql2 = "UPDATE contactlist SET Ctotal = $counter[0], Cactive = $counter[1], Cunsubscribed = $counter[2], Cbounced = $counter[3], Cspam = $counter[4] WHERE idContactlist = $this->idContactlist";

		$db->execute($sql2);
	}
}