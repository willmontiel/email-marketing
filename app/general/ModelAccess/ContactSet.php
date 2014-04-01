<?php

namespace EmailMarketing\General\ModelAccess;

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContactSet implements \EmailMarketing\General\ModelAccess\DataSource
{
	protected $searchCriteria;
	protected $searchFilter;
	protected $mailHistory;
	protected $queryCriteria;
	protected $account;
	protected $dbase;
	protected $database;
	protected $contactlist;
	protected $segment;
	protected $paginator;
	
	protected $rows = array();
	protected $name;
	
	protected $id;
	
	protected $cfields;
	protected $finstances;
	
	public function setSearchCriteria(\EmailMarketing\General\ModelAccess\ContactSearchCriteria $search = null)
	{
		$this->searchCriteria = $search;
	}
	
	public function setSearchFilter(\EmailMarketing\General\ModelAccess\ContactSearchFilter $filter)
	{
		$this->searchFilter = $filter->getFilter();
	}
	
	public function setContactMailHistory(\EmailMarketing\General\ModelAccess\ContactMailHistory $mailhistory)
	{
		$this->mailHistory = $mailhistory;
	}
	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	public function setDbase(\Dbase $dbase = null)
	{
		$this->dbase = $dbase;
		$this->database = $dbase;
	}
	
	public function setContactlist(\Contactlist $contactlist = null)
	{
		$this->contactlist = $contactlist;
		$this->database = $contactlist->dbase;
	}
	
	public function setSegment(\Segment $segment = null)
	{
		$this->segment = $segment;
		$this->database = $segment->dbase;
	}
	
	public function setPaginator(\PaginationDecorator $paginator)
	{
		$this->paginator = $paginator;
	}
	
	public function load()
	{   
		//1. Se crea el core query con los parametros de busqueda
		$coreQuery = $this->createCoreQuery();
		
		//2. Ejecutamos el coreQuey para obtener las identificaciones de los contactos que coinciden con los
		//parametros de búsqueda
		$contactIds = $this->findContactIds($coreQuery);
		
		//4. Buscamos los campos personalizados que pertenecen a cada contacto. Estos se cargaran a una variable global
		//para despues compararlos con cada contacto
		$this->findCustomFields($contactIds);
		
		//5. Creamos el query final con las identificaciones de contactos que nos arrojó el coreQuery
		$query = $this->createQuery($contactIds);
		
		//5. Ejecutamos el query final y obtenemos todos los contactos
		$contacts = $this->findContacts($query);
		
		//6. validamos que se hayan encontrado contactos y luego creamos la estructura final emparejando 
		//cada contacto con su respectivo campo personalizado
		if (count($contacts) > 0) {
			$this->createStructureForReturns($contacts);
		}
		
	}
	
	private function createCoreQuery()
	{
		$sql = '';
		$sqlTotalRecords = '';
		$limit = " LIMIT {$this->paginator->getRowsPerPage()} OFFSET {$this->paginator->getStartIndex()} ";
		
		$queryCriteria = $this->getSqlByQueryCriteria();
		
		if ($this->searchCriteria != null) {
			$c1 = $this->createSqlForEmailAndDomainSearch($queryCriteria);
			$c2 = $this->createSqlForFreeTextSearch($queryCriteria);

			if ($c1 == '') {
				$sqlTotalRecords = str_replace('c.idContact, c.idEmail', 'COUNT(*) AS total', $c2);
				$sqlTotalRecords .= " AND c.idDbase = {$this->database->idDbase} ";
				$sql = $c2 . " AND c.idDbase = {$this->database->idDbase} " . $limit;
			}
			else if ($c2 == '') {
				$sqlTotalRecords = "SELECT COUNT(*) AS total FROM contact AS c JOIN ({$c1}) AS c1 ON (c1.idEmail = c.idEmail) WHERE c.idDbase = {$this->database->idDbase}";
				$sql = "SELECT c.idContact FROM contact AS c JOIN ({$c1}) AS c1 ON (c1.idEmail = c.idEmail) WHERE c.idDbase = {$this->database->idDbase} {$limit} ";
			}
			else {
				$sqlTotalRecords = "SELECT COUNT(*) FROM ({$c1}) AS c1 JOIN ({$c2}) AS c2 ON (c1.idEmail = c2.idEmail) WHERE idDbase = {$this->database->idDbase}";
				$sql = "SELECT idContact FROM ({$c1}) AS c1 JOIN ({$c2}) AS c2 ON (c1.idEmail = c2.idEmail) WHERE idDbase = {$this->database->idDbase} {$limit} ";
			}
		}
		else {
			$filter = "";
			if ($this->searchFilter[0] != 'all') {
				if ($this->searchFilter[0] == 'blocked') {
					$filter .= " AND e.{$this->searchFilter[0]} != {$this->searchFilter[1]} ";
				}
				else{
					$filter .= " AND c.{$this->searchFilter[0]} != {$this->searchFilter[1]} ";
				}
			}
			
			$sqlTotalRecords = "SELECT COUNT(*) AS total 
						FROM contact as c
						JOIN email as e ON(e.idEmail = c.idEmail) {$queryCriteria->joinFilter}
					WHERE e.idAccount = {$this->account->idAccount} {$queryCriteria->andFilter} $filter";
			
			$sql = "SELECT c.idContact 
						FROM contact as c
						JOIN email as e ON(e.idEmail = c.idEmail) {$queryCriteria->joinFilter}
					WHERE e.idAccount = {$this->account->idAccount} {$queryCriteria->andFilter} $filter $limit";
		}
		
		\Phalcon\DI::getDefault()->get('logger')->log("Core query: " . $sql);
		\Phalcon\DI::getDefault()->get('logger')->log("Total query: " . $sqlTotalRecords);
		
		$this->setTotalMatches($sqlTotalRecords);
		
		return $sql;
	}
	
        
    private function findContactIds($sql)
	{
//		$cache = \Phalcon\DI::getDefault()->get('cache');
//		$queryKey = $this->getQueryKey();
//		$contactIds = $cache->get($queryKey);
		$contactIds = null;
		
		if (!$contactIds) {
			$contactIds = array();
			$db = \Phalcon\DI::getDefault()->get('db');
			
			$query = $db->query($sql);
			$result = $query->fetchAll();
			$count = count($result);

			if ($count > 0) {
				foreach ($result as $r) {
					$contactIds[] = $r['idContact'];
				}
			}
//			$cache->save($queryKey, $contactIds, 1800);
		}
		return $contactIds;
	}
        
        
	private function createQuery($contactIds)
	{
		$sql = '';
		if (count($contactIds) > 0) {
			$ids = implode(', ', $contactIds);
		
			$sql .= "SELECT c.*, e.email, e.blocked, d.idDbase, d.name AS dbase
					FROM contact AS c
						JOIN email AS e ON(e.idEmail = c.idEmail)
						JOIN dbase AS d ON(d.idDbase = c.idDbase)
					WHERE c.idContact IN (" . $ids . ")";
			
		}      
        \Phalcon\DI::getDefault()->get('logger')->log("Final query: " . $sql);
		return $sql;
	}
	
	/**
	 * Funcion para encontrar los campos personalizados en la base datos
	 * @param type $contactIds
	 */
	private function findCustomFields($contactIds)
	{
		if(!empty($contactIds)) {
			// Consultar la lista de campos personalizados para esos contactos
			$finstancesO = \Fieldinstance::findInstancesForMultipleContacts($contactIds);
			
			// Consultar lista de campos personalizados de la base de datos
			$cfieldsO = \Customfield::findCustomfieldsForDbase($this->database);
			
			// Convertir la lista de campos personalizados y de instancias a arreglos
			$this->cfields = array();
			foreach ($cfieldsO as $cf) {
				$this->cfields[$cf->idCustomField] = array('id' => $cf->idCustomField, 'type' => $cf->type, 'name' => 'campo' . $cf->idCustomField);
			}
			unset($cfieldsO);
			
			$this->finstances = $this->createFieldInstanceMap($finstancesO);
		}
	}
	
	
	private function createFieldInstanceMap($finstancesO)
	{
		$finstances = array();
		foreach ($finstancesO as $fi) {
			$key = $fi->idContact . ':' . $fi->idCustomField;
			$finstances[$key] = array('numberValue' => $fi->numberValue, 'textValue' => $fi->textValue);
		}
		return $finstances;
	}
	
	private function findContacts($sql)
	{
		if ($sql != '') {
			$db = \Phalcon\DI::getDefault()->get('db');
			$query = $db->query($sql);
			$result = $query->fetchAll();
			
			$contacts = $result;
		}
		else {
			$contacts = array();
		}
		
		return $contacts;
	}
	
	
	private function setTotalMatches($sql)
	{
		if ($sql != '') {
			$db = \Phalcon\DI::getDefault()->get('db');
			
			$query = $db->query($sql);
			$result = $query->fetch();
			\Phalcon\DI::getDefault()->get('logger')->log("Total: " . $result['total']);
			$this->paginator->setTotalRecords($result['total']);
		}
	}

	/**
	 * Returns the first CoreSql part created with emails and domains found in the search text
	 */
	private function createSqlForEmailAndDomainSearch($queryKey)
	{
		$queryFilter = '';
		$sqlEmail = '';
		$emails = $this->searchCriteria->getEmails();
		
		if ($this->searchFilter[0] != 'all') {
			if ($this->searchFilter[0] == 'unsubscribed') {
				$queryFilter .= " AND c.{$this->searchFilter[0]} != {$this->searchFilter[1]} ";
			}
			else {
				$queryFilter .= " AND e.{$this->searchFilter[0]} != {$this->searchFilter[1]} ";
			}
		}
		
		if (count($emails) > 0) {
			$union = false;
			foreach ($emails as $email) {
				if (!$union) {
					$union = true;
				}
				else {
					$sqlEmail .= " UNION ";
				}
				$sqlEmail .= "SELECT e.idEmail
						 FROM email AS e " . $queryKey->join . " 
						 WHERE e.email = '" . $email . "'" . $queryFilter . " AND e.idAccount = " . $this->account->idAccount . " " . $queryKey->and;
			}
		}
		
		$sqlDomain = '';
		$domains = $this->searchCriteria->getDomains();
		
		if (count($domains) > 0) {
			$union = false;
			foreach ($domains as $domain) {
				if (!$union) {
					$union = true;
				}
				else {
					$sqlDomain .= " UNION ";
				}
				$sqlDomain .= "SELECT e.idEmail
						 FROM domain AS d
							JOIN email AS e ON (e.idDomain = d.idDomain) " . $queryKey->join . " 
						 WHERE d.name = '" . $domain . "'" . $queryFilter . " AND e.idAccount = " . $this->account->idAccount . " " . $queryKey->and;
			}
		}
		
		if ($sqlEmail == '' || $sqlDomain == '') {
			$union = '';
		}
		else {
			$union = ' UNION ';
		}
		
		$sql = $sqlEmail . $union . $sqlDomain;
		
		return $sql;
	}
	
	private function createSqlForFreeTextSearch($queryKey)
	{
		$sql = '';
		$queryFilter = '';
		$joinFilter = '';
		$freeText = $this->searchCriteria->getFreeText();
		
		if ($this->searchFilter[0] != 'all') {
			if ($this->searchFilter[0] == 'blocked') {
				$joinFilter .= "JOIN email AS e ON (e.idEmail = c.idEmail)";
				$queryFilter .= " AND e.{$this->searchFilter[0]} != {$this->searchFilter[1]} ";
			}
			else {
				$queryFilter .= " AND c.{$this->searchFilter[0]} != {$this->searchFilter[1]} ";
			}
		}
		
		if (count($freeText) > 0) {
			$criteriaText = implode(' ', $freeText);
//			Phalcon\DI::getDefault()->get('logger')->log("Criteria: " . $criteria);
			
			$sql .= "SELECT c.idContact, c.idEmail
					FROM contact AS c 
						$joinFilter 
						JOIN dbase AS b ON(b.idDbase = c.idDbase) {$queryKey->joinForFreeText}  
					WHERE 
						MATCH(c.name, c.lastName) AGAINST ('" . $criteriaText . "' IN BOOLEAN MODE) 
						AND b.idAccount = " . $this->account->idAccount . " " . $queryFilter . " " . $queryKey->andForFreeText;
		}
                
//                \Phalcon\DI::getDefault()->get('logger')->log("Free text sql: " . $sql);
		return $sql;
	}
	
	
	private function getSqlByQueryCriteria()
	{
		$query = new \stdClass();
		
		if ($this->dbase == null && $this->contactlist == null && $this->segment == null) {
			$query->join = '';
			$query->and = '';
			$query->joinForFreeText = '';
			$query->andForFreeText = '';
			$query->joinFilter = '';
			$query->andFilter = '';
			
			$this->name = 'contacts';
			$this->id = $this->account->idAccount;
			
			$this->queryCriteria = 'account';
		}
		else if ($this->dbase !== null) {
			$query->join = ' JOIN dbase AS db ON (db.idAccount = e.idAccount) ';
			$query->and = " AND db.idDbase = {$this->dbase->idDbase} ";

			$query->joinForFreeText = '';
			$query->andForFreeText = " AND b.idDbase = {$this->dbase->idDbase} ";
			
			$query->joinFilter = " JOIN dbase AS db ON (db.idDbase = c.idDbase) ";
			$query->andFilter = " AND db.idDbase = {$this->dbase->idDbase} ";
			
			$this->name = 'contacts';
			$this->id = $this->dbase->idDbase;
			
			$this->queryCriteria = 'dbase';
		}
		else if ($this->contactlist !== null) {
			$query->join = ' JOIN contact AS c ON (c.idEmail = e.idEmail) 
									  JOIN coxcl AS cl ON (cl.idContact = c.idContact) ';
			$query->and = ' AND cl.idContactlist = ' . $this->contactlist->idContactlist;

			$query->joinForFreeText = ' JOIN coxcl AS cl ON (cl.idContact = c.idContact) ';
			$query->andForFreeText = ' AND cl.idContactlist = ' . $this->contactlist->idContactlist;
			
			$query->joinFilter = " JOIN coxcl AS cl ON (cl.idContact = c.idContact) ";
			$query->andFilter = " AND cl.idContactlist = {$this->contactlist->idContactlist} ";
			
			$this->name = 'contacts';
			$this->id = $this->contactlist->idContactlist;
			
			$this->queryCriteria = 'contactlist';
		}
		else if ($this->segment !== null){
			$query->join = ' JOIN contact AS c ON (c.idEmail = e.idEmail) 
									  JOIN sxc AS sc ON (sc.idContact = c.idContact) ';
			$query->and = " AND sc.idSegment = {$this->segment->idSegment} ";

			$query->joinForFreeText = " JOIN sxc AS sc ON (sc.idContact = c.idContact) ";
			$query->andForFreeText = " AND sc.idSegment = {$this->segment->idSegment} ";
			
			$query->joinFilter = " JOIN sxc AS sc ON (sc.idContact = c.idContact) ";
			$query->andFilter = " AND sc.idSegment = {$this->segment->idSegment} ";
			
			$this->name = 'contacts';
			$this->id = $this->segment->idSegment;
			
			$this->queryCriteria = 'segment';
		}
		
		return $query;
	}
	
	protected function getQueryKey()
	{
		$key = $this->queryCriteria . $this->id . '_' . $this->searchCriteria->getCriteria() . '_pag_' . $this->paginator->getCurrentPage() . '_' . $this->paginator->getTotalPages();
		return $key;
	}
	
	/**
	 * Empareja cada contacto con su respectivo campo personalizado y crea la estructura final del arreglo que contiene los
	 * contactos
	 * @param array $contacts
	 */
	private function createStructureForReturns($contacts)
	{   
		$object = array();
		foreach ($contacts as $contact) {
			$c = array();
			$c['id'] = $contact['idContact'];
			$c['email'] = $contact['email'];
			$c['name'] = $contact['name'];
			$c['lastName'] = $contact['lastName'];
			$c['isActive'] = ($contact['status'] != 0);
			$c['activatedOn'] = (($contact['status'] != 0)?date('d/m/Y H:i', $contact['status']):'');
			$c['isSubscribed'] = ($contact['unsubscribed'] == 0);
			$c['subscribedOn'] = (($contact['subscribedon'] != 0)?date('d/m/Y H:i', $contact['subscribedon']):'');
			$c['unsubscribedOn'] = (($contact['unsubscribed'] != 0)?date('d/m/Y H:i', $contact['unsubscribed']):'');
			$c['isBounced'] = ($contact['bounced'] != 0);
			$c['bouncedOn'] = (($contact['bounced'] != 0)?date('d/m/Y H:i', $contact['bounced']):'');
			$c['isSpam'] = ($contact['spam'] != 0);
			$c['spamOn'] = (($contact['spam'] != 0)?date('d/m/Y H:i', $contact['spam']):'');
			$c['createdOn'] = (($contact['createdon'] != 0)?date('d/m/Y H:i', $contact['createdon']):'');
			$c['updatedOn'] = (($contact['updatedon'] != 0)?date('d/m/Y H:i', $contact['updatedon']):'');
								$this->mailHistory->findMailHistory($contact['idContact']);
			$c['mailHistory'] = $this->mailHistory->getMailHistory();
			$c['ipSubscribed'] = (($contact['ipSubscribed'])?long2ip($contact['ipSubscribed']):'');
			$c['ipActivated'] = (($contact['ipActivated'])?long2ip($contact['ipActivated']):'');

			$c['isEmailBlocked'] = ($contact['blocked'] != 0);
			
			if (count($this->cfields) > 0) {
				foreach ($this->cfields as $field) {
					$key = $contact['idContact'] . ':' . $field['id'];
					$value = '';
					if (isset($this->finstances[$key])) {
						$fvalue = $this->finstances[$key];
						switch ($field['type']) {
							case 'Date':
								if($fvalue['numberValue']) {
									$value = date('Y-m-d',$fvalue['numberValue']);
								} else {
									$value = "";
								}
								break;
							case 'Number':
								$value = $fvalue['numberValue'];
								break;
							default:
								$value = $fvalue['textValue'];
						}
					}
					$c[$field['name']] = $value;
				}
			}
			
			$object[] = $c;
		}
		$this->rows = $object;
	}

	/**
	 * Metodos de la interface DataSource
	 */
	public function getName()
	{
		return $this->name;
	}
	
	public function getRows()
	{
		return $this->rows;
	}
	
	public function getCurrentPage()
	{
		return $this->paginator->getCurrentPage();
	}
	
	public function getRowsPerPage()
	{
		return $this->paginator->getRowsPerPage();
	}
	
	public function getTotalPages()
	{
		return $this->paginator->getTotalPages();
	}
	
	public function getTotalRecords()
	{
		return $this->paginator->getTotalRecords();
	}
}
