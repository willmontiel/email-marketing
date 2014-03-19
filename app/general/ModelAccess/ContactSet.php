<?php

namespace EmailMarketing\General\ModelAccess;

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContactSet implements \EmailMarketing\General\ModelAccess\DataSource
{
	protected $searchCriteria;
	protected $queryCriteria;
	protected $account;
	protected $dbase;
	protected $contactlist;
	protected $segment;
	protected $paginator;
	
	protected $rows;
	protected $name;
	
	protected $id;

	public function setSearchCriteria(\EmailMarketing\General\ModelAccess\ContactSearchCriteria $search)
	{
		$this->searchCriteria = $search;
	}
	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	public function setDbase(\Dbase $dbase = null)
	{
		$this->dbase = $dbase;
	}
	
	public function setContactlist(\Contactlist $contactlist = null)
	{
		$this->contactlist = $contactlist;
	}
	
	public function setSegment(\Segment $segment = null)
	{
		$this->segment = $segment;
	}
	
	public function setPaginator(\PaginationDecorator $paginator)
	{
		$this->paginator = $paginator;
	}
	
	public function load()
	{   
                $this->validateQueryCriteria();
		$contactIds = $this->findContactIds($this->createCoreQuery());
		$query = $this->createQuery($contactIds);
		
		$this->findContacts($query);
	}
	
	private function validateQueryCriteria()
	{
		if ($this->dbase == null && $this->contactlist == null && $this->segment == null) {
			$this->queryCriteria = 'account';
		}
		else if ($this->dbase !== null) {
			$this->queryCriteria = 'dbase';
		}
		else if ($this->contactlist !== null) {
			$this->queryCriteria = 'contactlist';
		}
		else if ($this->segment !== null){
			$this->queryCriteria = 'segment';
		}
	}
	
	private function createCoreQuery()
	{
		$queryCriteria = $this->getSqlByQueryCriteria();
		
		$c1 = $this->createSqlForEmailAndDomainSearch($queryCriteria);
		$c2 = $this->createSqlForFreeTextSearch($queryCriteria);
		
		$limit = " LIMIT " . $this->paginator->getRowsPerPage() . ' OFFSET ' . $this->paginator->getStartIndex();
		
		if ($c1 == '') {
			$sql = $c2 . $limit;
		}
		else if ($c2 == '') {
			$sql = "SELECT c.idContact FROM contact AS c JOIN (" . $c1 . ") AS c1 ON (c1.idEmail = c.idEmail) " . $limit;
		}
		else {
			$sql = "SELECT idContact FROM (" . $c1 . ") AS c1 JOIN (" . $c2 . ") AS c2 ON (c1.idEmail = c2.idEmail) " . $limit;
		}
		
		\Phalcon\DI::getDefault()->get('logger')->log("Core query: " . $sql);
		
		return $sql;
	}
	
        
        private function findContactIds($sql)
	{
		$cache = \Phalcon\DI::getDefault()->get('cache');
		$queryKey = $this->getQueryKey();
		$contactIds = $cache->get($queryKey);
		
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
			$cache->save($queryKey, $contactIds, 1800);
		}
		return $contactIds;
	}
        
        
	private function createQuery($contactIds)
	{
		$sql = '';
		if (count($contactIds) > 0) {
			$ids = implode(', ', $contactIds);
		
			$sql .= "SELECT c.*, e.email, e.blocked, d.idDbase, d.name AS dbase
					FROM Contact c
						JOIN Email e ON(e.idEmail = c.idEmail)
						JOIN Dbase d ON(d.idDbase = c.idDbase)
					WHERE c.idContact IN (" . $ids . ")";
		}
                
                \Phalcon\DI::getDefault()->get('logger')->log("Final query: " . $sql);
		return $sql;
	}
	
	
	private function findContacts($phql)
	{
		$manager = \Phalcon\DI::getDefault()->get('modelsManager');
		
                $result = $manager->executeQuery($phql);
		
		$count = count($result);
		
		if ($count > 0) {
//                        \Phalcon\DI::getDefault()->get('logger')->log("Matches: " . print_r($result, true));
			$this->createStructureForReturns($result);
		}
	}
	

	/**
	 * Returns the first CoreSql part created with emails and domains found in the search text
	 */
	private function createSqlForEmailAndDomainSearch($queryKey)
	{
		$sqlEmail = '';
		$emails = $this->searchCriteria->getEmails();
		
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
						 WHERE e.email = '" . $email . "' AND e.idAccount = " . $this->account->idAccount . " " . $queryKey->and;
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
						 WHERE d.name = '" . $domain . "' AND e.idAccount = " . $this->account->idAccount . " " . $queryKey->and;
			}
		}
		
		if ($sqlEmail == '' || $sqlDomain == '') {
			$union = '';
		}
		else {
			$union = ' UNION ';
		}
		
		$sql = $sqlEmail . $union . $sqlDomain;
		
//                 \Phalcon\DI::getDefault()->get('logger')->log("Domains and email sql: " . $sql);
                 
		return $sql;
	}
	
	private function createSqlForFreeTextSearch($queryKey)
	{
		$sql = '';
		$freeText = $this->searchCriteria->getFreeText();
		
		if (count($freeText) > 0) {
			$criteriaText = implode(' ', $freeText);
//			Phalcon\DI::getDefault()->get('logger')->log("Criteria: " . $criteria);
			
			$sql .= "SELECT c.idContact, c.idEmail
					FROM contact AS c
						JOIN dbase AS b ON(b.idDbase = c.idDbase) " . $queryKey->joinForFreeText . " 
					WHERE 
						MATCH(c.name, c.lastname) AGAINST ('" . $criteriaText . "' IN BOOLEAN MODE) 
						AND b.idAccount = " . $this->account->idAccount . " " . $queryKey->andForFreeText;
		}
                
//                \Phalcon\DI::getDefault()->get('logger')->log("Free text sql: " . $sql);
		return $sql;
	}
	
	
	private function getSqlByQueryCriteria()
	{
		$query = new \stdClass();
		
		switch ($this->queryCriteria) {
			case 'account':
				$query->join = '';
				$query->and = '';
				$query->joinForFreeText = '';
				$query->andForFreeText = '';
				
				$this->name = 'contacts';
				$this->id = $this->account->idAccount;
				break;

			case 'dbase':
				$query->join = ' JOIN dbase AS db ON (db.idAccount = d.idAccount) ';
				$query->and = ' AND db.idDbase = ' . $this->dbase->idDbase;
				
				$query->joinForFreeText = '';
				$query->andForFreeText = ' AND b.idDbase = ' . $this->dbase->idDbase;
				
				$this->name = 'contacts';
				$this->id = $this->dbase->idDbase;
				break;
			
			case 'contactlist':
				$query->join = ' JOIN contact AS a ON (c.idEmail = e.idEmail) 
									      JOIN coxcl AS cl ON (cl.idContact = c.idContact) ';
				$query->and = ' AND cl.idContactlist = ' . $this->contactlist->idContactlist;
				
				$query->joinForFreeText = ' JOIN coxcl AS cl ON (cl.idContact = c.idContact) ';
				$query->andForFreeText = ' AND cl.idContactlist = ' . $this->contactlist->idContactlist;
				
				$this->name = 'contacts';
				$this->id = $this->contactlist->idContactlist;
				break;
			
			case 'segment':
				$query->join = ' JOIN contact AS c ON (c.idEmail = e.idEmail) 
										  JOIN sxc AS sc ON (sc.idContact = c.idContact) ';
				$query->and = ' AND sc.idSegment = ' . $this->segment->idSegment;
				
				$query->joinForFreeText = ' JOIN sxc AS sc ON (sc.idContact = c.idContact) ';
				$query->andForFreeText = ' AND sc.idSegment = ' . $this->segment->idSegment;
				
				$this->name = 'contacts';
				$this->id = $this->segment->idSegment;
				break;
		}
		
		return $query;
	}
	
	protected function getQueryKey()
	{
		$key = $this->queryCriteria . $this->id . '_' . $this->searchCriteria->getCriteria() . '_pag_' . $this->paginator->getCurrentPage() . '_' . $this->paginator->getTotalPages();
		return $key;
	}
	
	private function createStructureForReturns($contacts)
	{   
                $object = array();
                foreach ($contacts as $contact) {
                    $c = array();
                    $c['id'] = $contact->idContact;
                    $c['email'] = $contact->email;
                    $c['name'] = $contact->name;
                    $c['lastName'] = $contact->lastName;
                    $c['isActive'] = ($contact->status != 0);
                    $c['activatedOn'] = (($contact->status != 0)?date('d/m/Y H:i', $contact->status):'');
                    $c['isSubscribed'] = ($contact->unsubscribed == 0);
                    $c['subscribedOn'] = (($contact->subscribedon != 0)?date('d/m/Y H:i', $contact->subscribedon):'');
                    $c['unsubscribedOn'] = (($contact->unsubscribed != 0)?date('d/m/Y H:i', $contact->unsubscribed):'');
                    $c['isBounced'] = ($contact->bounced != 0);
                    $c['bouncedOn'] = (($contact->bounced != 0)?date('d/m/Y H:i', $contact->bounced):'');
                    $c['isSpam'] = ($contact->spam != 0);
                    $c['spamOn'] = (($contact->spam != 0)?date('d/m/Y H:i', $contact->spam):'');
                    $c['createdOn'] = (($contact->createdon != 0)?date('d/m/Y H:i', $contact->createdon):'');
                    $c['updatedOn'] = (($contact->updatedon != 0)?date('d/m/Y H:i', $contact->updatedon):'');

                    $c['ipSubscribed'] = (($contact->ipSubscribed)?long2ip($contact->ipSubscribed):'');
                    $c['ipActivated'] = (($contact->ipActivated)?long2ip($contact->ipActivated):'');

                    $c['isEmailBlocked'] = ($contact->blocked != 0);
                    
                    $object[] = $c;
                }
                
                $this->rows = $object;
	}

	/**
	 * Metodos de la interface DataSource
	 */
	public function getName()
	{
                \Phalcon\DI::getDefault()->get('logger')->log("Name: " . $this->name);
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
	
	public function getTotalPages()
	{
		return $this->paginator->getTotalPages();
	}
	
	public function getTotalRecords()
	{
		return $this->paginator->getTotalRecords();
	}
}
