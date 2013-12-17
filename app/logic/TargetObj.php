<?php
class TargetObj 
{
	function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		
		$this->log = $di['logger'];
		$this->modelsManager = $di['modelsManager'];
	}
	
	public function setDbases ($dbases)
	{
		$this->dbases = $dbases;
	}
	
	public function setContactlists ($contactlists)
	{
		$this->contactlists = $contactlists;
	}
	
	public function setSegments ($segments)
	{
		$this->segments = $segments;
	}
	
	public function createTargetObj ($idDbases, $idContactlists, $idSegments, Mail $mail)
	{	
		$targetInfo = $this->collectTargetInfo($idDbases, $idContactlists, $idSegments);
		
		$contacts = $this->modelsManager->executeQuery($targetInfo['phql']);
		
		$totalContacts = count($contacts);
		if ($totalContacts < 1) {
			return false;
		}
		
//		$targetsName = $this->findTargetsName($idDbases, $idContactlists, $idSegments, $targetInfo['type']);
		$response = $this->saveTargetDataInDB($mail, $targetInfo['destinationJson'], $totalContacts);
		
		return $response;
	}
	
	protected function collectTargetInfo($idDbases, $idContactlists, $idSegments)
	{
		$destinationJson = new stdClass();
		
		if ($idDbases != null) {
			$dbase = implode(',', $idDbases);
			
			$destinationJson->destination = "dbases";
			$destinationJson->ids = $idDbases;
			$destinationJson->filter = "";
			
			$type = 'dbase';
			$phql = "SELECT Contact.idContact FROM Contact WHERE Contact.idDbase IN (" . $dbase . ")";
		}

		else if ($idContactlists != null) {
			$contactlist = implode(',', $idContactlists);
			
			$destinationJson->destination = "contactlists";
			$destinationJson->ids = $idContactlists;
			$destinationJson->filter = "";
			
			$type = 'list';
			$phql= "SELECT Coxcl.idContact FROM Coxcl WHERE Coxcl.idContactlist IN (" . $contactlist . ")";
		}

		else if ($idSegments != null) {
			$segment = implode(',', $idSegments);
			
			$destinationJson->destination = "segments";
			$destinationJson->ids = $idSegments;
			$destinationJson->filter = "";
			
			$type = 'segment';
			$phql .= "SELECT Sxc.idContact FROM Sxc WHERE Sxc.idSegment IN (" . $segment . ")";
		}
		
		$targetInfo = array('destinationJson' => $destinationJson, 'type' => $type, 'phql' => $phql);
		return $targetInfo;
	}
	
	protected function findTargetsName($idDbases, $idContactlists, $idSegments, $target)
	{
		$targetsName = array();
		switch ($target) {
			case 'dbase':
				foreach ($idDbases as $id) {
					foreach ($this->dbases as $dbase)
					if ($id == $dbase->idDbase) {
						$targetsName[] = $dbase->name;
					}
				}
				break;
			
			case 'list':
				foreach ($idContactlists as $id) {
					foreach ($this->contactlists as $contactlist)
					if ($id == $contactlist->idContactlist) {
						$targetsName[] = $contactlist->name;
					}
				}
				break;;
				
			case 'segment':
				foreach ($idSegments as $id) {
					foreach ($this->segments as $segment)
					if ($id == $segment->idSegment) {
						$targetsName[] = $segment->name;
					}
				}
				break;
		}
		return $targetsName;
	}


	protected function saveTargetDataInDB(Mail $mail, $destinationJson, $totalContacts)
	{
		if ($mail->wizardOption == 'target' || $mail->wizardOption == 'source') {
			$wizardOption = 'target';
		}
		else{
			$wizardOption = $mail->wizardOption;
		}
		
		$mail->wizardOption = $wizardOption;
		$mail->target = json_encode($destinationJson);
		$mail->totalContacts = $totalContacts;
		
		if (!$mail->save()) {
			throw new InvalidArgumentException("Error while saving targetObj in db");
		}
		
		return true;
	}
}