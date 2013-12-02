<?php
class IdentifyTarget
{
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	}
	
	public function identifyTarget($mail)
	{
		$target = json_decode($mail->target);
//		$this->log->log('Target: ' . print_r($target, true));
		$ids = implode(',', $target->ids);
//		$this->log->log('Ids: ' . $ids);
		
		if (!empty($target->filter)){
			$this->log->log('Hay filtro');
		}
		else {
			$this->log->log('No hay filtro');
		}
		
		$sql = "REPLACE INTO mxc (idMail, idContact)";
		
		switch ($target->destination) {
			case 'dbases':
				$phql = "SELECT Contact.idContact FROM Contact WHERE Contact.idDbase IN (" . $ids . ")";
				break;
			
			case 'lists':
				$phql= "SELECT Coxcl.idContact FROM Coxcl WHERE Coxcl.idContactlist IN (" . $ids . ")";
				break;
				
			case 'segments':
				$phql .= "SELECT Sxc.idContact FROM Sxc WHERE Sxc.idSegment IN (" . $ids . ")";
				break;
		}
		
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$contacts = $modelsManager->executeQuery($phql);
		
		$idContacts = $this->returnIds($contacts, $mail->idMail);
		
		$sql .= $idContacts;
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$destination = $db->execute($sql);
		
		if (!$destination) {
			throw new InvalidArgumentException('Error while consulting recipients');
		}
	}
	
	protected function returnIds($contacts, $idMail) 
	{
		$idContacts = " VALUES";
		$comma = false;
		foreach ($contacts as $id) {
			if (!$comma) {
				$idContacts .= " (" . $idMail . "," . $id->idContact . ") ";
			}
			$idContacts .= ", (" . $idMail . "," . $id->idContact . ") ";
			$comma = true;
		}
		return $idContacts;
	}
}