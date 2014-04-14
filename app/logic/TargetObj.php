<?php
class TargetObj 
{
	protected $logger;
	protected $modelsManager;
	protected $idsDbase;
	protected $idsContactlist;
	protected $idsSegment;
	protected $target;
	protected $byEmail;
	protected $byOpen;
	protected $byClick;
	protected $byExclude;
			
	function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
	}
	
	public function setIdsDbase($idsDbase)
	{
		$this->idsDbase = $idsDbase;
	}
	
	public function setIdsContactlist($idsContactlist)
	{
		$this->idsContactlist = $idsContactlist;
	}
	
	public function setIdsSegment($idsSegment)
	{
		$this->idsSegment = $idsSegment;
	}

	public function setFilters($byEmail, $byOpen, $byClick, $byExclude)
	{
		$this->byEmail = $byEmail;
		$this->byOpen = $byOpen;
		$this->byClick = $byClick;
		$this->byExclude = $byExclude;
	}

	public function createTargetObj()
	{	
		//1. Creamos el objeto target y creamos una consulta SQL para validar si existen contactos
		$targetInfo = $this->collectTargetInfo();
		
		$this->logger->log('SQL: ' . $targetInfo['phql']);
		$contacts = $this->modelsManager->executeQuery($targetInfo['phql']);
		
		$this->target = new stdClass();
		$this->target->target = json_encode($targetInfo['destinationJson']);
		$this->target->totalContacts = count($contacts);
	}
	
	protected function processFilter()
	{
		$filter = '';
		
		if ($this->byEmail !== null || !empty($this->byEmail)) { 
			$filter = array(
				'type' => 'email',
				'criteria' => $this->byEmail
			);
		}
		else if ($this->byOpen !== '' || !empty($this->byOpen)) {
			$this->logger->log("Open: {$this->byOpen}");
			$filter = array(
				'type' => 'open',
				'criteria' => $this->byOpen
			);
		}
		else if ($this->byClick !== '' || !empty($this->byClick)) {
			$filter = array(
				'type' => 'click',
				'criteria' => $this->byClick
			);
		}
		else if ($this->byExclude !== '' || !empty($this->byExclude)) {
			$filter = array(
				'type' => 'mailExclude',
				'criteria' => $this->byExclude
			);
		}
		
		return $filter;
	}

	protected function collectTargetInfo()
	{
		$filter = $this->processFilter();
		$destinationJson = new stdClass();
		
		if ($this->idsDbase != null) {
			
			$destinationJson->destination = "dbases";
			$destinationJson->ids = explode(",", $this->idsDbase);
			
			$type = 'dbase';
			$phql = "SELECT Contact.idContact FROM Contact WHERE Contact.idDbase IN ({$this->idsDbase})";
		}

		else if ($this->idsContactlist != null) {
			$destinationJson->destination = "contactlists";
			$destinationJson->ids = explode(",", $this->idsContactlist);
			
			$type = 'list';
			$phql= "SELECT Coxcl.idContact FROM Coxcl WHERE Coxcl.idContactlist IN ({$this->idsContactlist})";
		}

		else if ($this->idsSegment != null) {
			$destinationJson->destination = "segments";
			$destinationJson->ids = explode(",", $this->idsSegment);
			
			$type = 'segment';
			$phql .= "SELECT Sxc.idContact FROM Sxc WHERE Sxc.idSegment IN ({$this->idsSegment})";
		}
		
		$destinationJson->filter = $filter;
		
		$targetInfo = array('destinationJson' => $destinationJson, 'type' => $type, 'phql' => $phql);
		
		return $targetInfo;
	}
	
	public function getTargetObject()
	{
		if ($this->target->totalContacts < 0) {
			return null;
		}
		return $this->target;
	}
}