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
	
	public function setIdsContactlist($idsContactlist = null)
	{
		$this->idsContactlist = $idsContactlist;
	}
	
	public function setIdsSegment($idsSegment = null)
	{
		$this->idsSegment = $idsSegment;
	}

	public function setFilters($byEmail = null, $byOpen = null, $byClick = null, $byExclude = null)
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
		$contacts = $this->modelsManager->executeQuery($targetInfo['phql']);
		$total = $contacts->getFirst()->total;
		
		$this->logger->log('Target: ' . $total);
		$this->target = new stdClass();
		$this->target->target = json_encode($targetInfo['destinationJson']);
		$this->target->totalContacts = $total;
	}
	
	protected function processFilter()
	{
		$filter = new stdClass();
		
		$filter->filter = "";
		$filter->join = "";
		$filter->and = "";
		
		if ($this->byEmail != null || !empty($this->byEmail)) { 
			$filter->filter = array(
				'type' => 'email',
				'criteria' => $this->byEmail
			);
			
			$filter->join = " JOIN Email AS e ON e.idEmail = c.idEmail ";
			$filter->and = " AND e.email = '{$this->byEmail}'";
		}
		else if ($this->byOpen != '' || !empty($this->byOpen)) {
			$filter->filter = array(
				'type' => 'open',
				'criteria' => $this->byOpen
			);
		}
		else if ($this->byClick != '' || !empty($this->byClick)) {
			$filter->filter = array(
				'type' => 'click',
				'criteria' => $this->byClick
			);
		}
		else if ($this->byExclude != '' || !empty($this->byExclude)) {
			$filter->filter = array(
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
			$phql = "SELECT COUNT(DISTINCT c.idContact) AS total 
						FROM Contact AS c {$filter->join} 
					 WHERE c.idDbase IN ({$this->idsDbase}) {$filter->and} ";
		}

		else if ($this->idsContactlist != null) {
			$destinationJson->destination = "contactlists";
			$destinationJson->ids = explode(",", $this->idsContactlist);
			
			$type = 'list';
			$phql= "SELECT COUNT(DISTINCT c.idContact) AS total 
				    FROM Contact AS c 
						JOIN Coxcl as cl ON cl.idContact = c.idContact {$filter->join} 
					WHERE cl.idContactlist IN ({$this->idsContactlist}) {$filter->and} ";
		}

		else if ($this->idsSegment != null) {
			$destinationJson->destination = "segments";
			$destinationJson->ids = explode(",", $this->idsSegment);
			
			$type = 'segment';
			$phql = "SELECT COUNT(DISTINCT c.idContact) AS total 
					 FROM Contact AS c 
						JOIN Sxc AS s ON s.idContact = c.idContact {$filter->join} 
					 WHERE s.idSegment IN ({$this->idsSegment}) {$filter->and} ";
		}
		
		$destinationJson->filter = $filter->filter;
		
		$this->logger->log("PHQL: {$phql}");
		$targetInfo = array('destinationJson' => $destinationJson, 'type' => $type, 'phql' => $phql);
		
		return $targetInfo;
	}
	
	public function getTargetObject()
	{
		return $this->target;
	}
}