<?php
class TotalContactIterator implements Iterator
{
	protected $logger;
	protected $customfields;
	protected $data;
	protected $contacts;
	protected $start;
	protected $offset;
	protected $from;
	protected $join;
	protected $where;
	protected $customNames;
	protected $cfidentifiers = null;
	protected $customFieldsQuery = null;
	protected $cfObject = null;
	protected $cfs = array();
	protected $c = array();

	const ROWS_PER_FETCH = 25000;
	
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setCustomFields($customfields = false)
	{
		$this->customfields = $customfields;
	}

	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new InvalidArgumentException("export data is not valid...");
		}
		$this->data = $data;
	}
	
	public function initialize()
	{
		$this->createQuery();
		if ($this->customfields) {
			$this->getCustomfieldsIdentifiers();
			$this->createQueryForCustomFields();
		}
	}


	public function extractContactsFromDB($start = 0)
	{
		$sql = "SELECT c.idContact, IF(e.spam != 0, 'Spam', IF(e.bounced != 0, 'Rebotado', IF(e.blocked != 0, 'Bloqueado', IF(c.unsubscribed != 0, 'Des-suscrito', IF(c.status != 0, 'Activo', 'Inactivo'))))) AS status, e.email, c.name, c.lastName, c.birthDate, c.createdon {$this->customNames}
				   FROM {$this->from}
					    {$this->join}
						JOIN email AS e ON (e.idEmail = c.idEmail)
						{$this->customFieldsQuery}
				   WHERE {$this->where} {$this->conditions} AND c.idContact > {$start} ORDER BY c.idContact LIMIT " . self::ROWS_PER_FETCH;
				   
		unset($this->contacts);
		
//		$this->logger->log("SQL: {$sql}");
		
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($sql);
		$contacts = $result->fetchAll();
		
		if (count($contacts) <= 0) {
			return false;
		}

		$this->offset = 0;
		
		if (!$this->customfields) {
			$this->prepareContactsWithoutCustomFields($contacts);
		}
		else {
			$this->prepareContactsWithCustomFields($contacts);
		}
		
		unset($contacts);
		$end = end($this->contacts);
		$this->start = $end['idContact'];
		
		return true;
	}
	
	public function rewind()
	{
		$this->start = 0;
		$this->contacts = array();
		$this->offset = 0;
	}
	
	public function current()
	{
		return $this->contacts[$this->offset];
	}
	
	public function key()
	{
		return $this->contact[$this->offset]['idContact'];
	}
	
	public function next()
	{
		$this->offset++;
	}
	
	public function valid()
	{
		$cnt = count($this->contacts);
		if (($cnt - $this->offset) <= 0) {
			if ($this->extractContactsFromDB($this->start)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}
	
	protected function prepareContactsWithoutCustomFields($contacts)
	{
		$i = -1;
		$k = 0;
		$this->contacts = array();
		foreach ($contacts as $m) {
			if (count($this->contacts) == 0) {
				$c = array(
					'idContact' => $m['idContact'],
					'email' => $m['email'],
					'name' => $m['name'],
					'lastName' => $m['lastName'],
					'birthDate' => (empty($m['birthDate']) ? null : $m['birthDate']),
					'status' => $m['status'],
					'createdon' => $m['createdon']
				);
				
				$this->contacts[0] = $c;
			}
			else if ($this->contacts[$i]['idContact'] == $m['idContact']) {
//				$i--;
//				$k--;
			}
			else {
				$c = array(
					'idContact' => $m['idContact'],
					'email' => $m['email'],
					'name' => $m['name'],
					'lastName' => $m['lastName'],
					'birthDate' => (empty($m['birthDate']) ? null : $m['birthDate']),
					'status' => $m['status'],
					'createdon' => $m['createdon']
				);

				$this->contacts[$k] = $c;
			}
			$i++;
			$k++;
		}
	}
	
	protected function prepareContactsWithCustomFields($contacts)
	{
		$this->contacts = array();
		$i = -1;
		$k = 0;
		
		$c = array(
			'idContact' => null,
			'email' => null,
			'name' => null,
			'lastName' => null,
			'birthDate' => null,
			'status' => null,
			'createdon' => null
		);
		
		foreach ($this->cfs as $cfs) {
			$c[$cfs] = null;
		}
		
		foreach ($contacts as $m) {
			if (count($this->contacts) == 0) {
				$c = array(
					'idContact' => $m['idContact'],
					'email' => $m['email'],
					'name' => $m['name'],
					'lastName' => $m['lastName'],
					'birthDate' => $m['birthDate'],
					'status' => $m['status'],
					'createdon' => $m['createdon']
				);
				
				if (isset($m['field']) && !empty($m['field'])) {
					$name = $this->cleanSpaces($m['field']);
					$c[$name] = (!empty($m['value']) ? $m['value'] : null);
				}
				else {
					$c[$name] = null;
				}
				
				$this->contacts[0] = $c;
//				$this->logger->log(print_r($c, true));
				$c[$name] = null;
			}
			else if (isset($this->contacts[$i]) && $this->contacts[$i]['idContact'] == $m['idContact']) {
				if (isset($m['field']) && !empty($m['field'])) {
					$name = $this->cleanSpaces($m['field']);
					$this->contacts[$i][$name] = (!empty($m['value']) ? $m['value'] : null);
				}
//				$this->logger->log(print_r($this->contacts[$i], true));
				$i--;
				$k--;
			}
			else {
				$c = array(
					'idContact' => $m['idContact'],
					'email' => $m['email'],
					'name' => $m['name'],
					'lastName' => $m['lastName'],
					'birthDate' => $m['birthDate'],
					'status' => $m['status'],
					'createdon' => $m['createdon']
				);

				if (isset($m['field']) && !empty($m['field'])) {
					$name = $this->cleanSpaces($m['field']);
					$c[$name] = (!empty($m['value']) ? $m['value'] : null);
				}
				else {
					$c[$name] = null;
				}
				
//				$this->logger->log(print_r($c, true));
				$this->contacts[$k] = $c;
				$c[$name] = null;
			}
			$i++;
			$k++;
		}
	}
	
	protected function createQuery()
	{
		switch ($this->data->criteria) {
			case 'contactlist':
				$this->from = " contactlist AS cl ";
				$this->join = " JOIN coxcl AS co ON (co.idContactlist = cl.idContactlist) 
								JOIN contact AS c ON (c.idContact = co.idContact)";
				$this->where = " cl.idContactlist = {$this->data->id} ";
				break;
			
			case 'dbase':
				$this->from = " contact AS c ";
				$this->join = "";
				$this->where = " c.idDbase = {$this->data->id} ";
				break;
			
			case 'segment':
				$this->from = " segment AS s ";
				$this->join = " JOIN sxc AS sc ON (sc.idSegment = s.idSegment) 
							    JOIN contact AS c ON (c.idContact = sc.idContact)";
				$this->where = "s.idSegment = {$this->data->id} ";
				break;
		}
		
		switch ($this->data->contacts) {
			case 'active':
				$this->conditions = " AND c.unsubscribed = 0 AND e.bounced = 0 AND e.spam = 0 AND c.status != 0";
				break;
			
			case 'unsuscribed':
				$this->conditions = " AND c.unsubscribed != 0 ";
				break;
			
			case 'bounced':
				$this->conditions = " AND e.bounced != 0 ";
				break;
			
			case 'spam':
				$this->conditions = " AND e.spam != 0 ";
				break;
			
			case 'blocked':
				$this->conditions = " AND e.blocked != 0 ";
				break;
			
			default:
				$this->conditions = "";
				break;
		}
	}
	
	protected function getCustomfieldsIdentifiers()
	{
		$cfields = Customfield::find(array(
			'conditions' => 'idDbase = ?1' ,
			'bind' => array(1 => $this->data->model->idDbase)
		));
		
		$cfidentifiers = array();
		$cfnames = array();
		$this->cfs = array();
		
		if (count($cfields) > 0) {
			foreach ($cfields as $cfield) {
				$cfidentifiers[] = $cfield->idCustomField;
				
				$type = ($cfield->type == 'Numerical' ? 'INT(100)' : 'VARCHAR(100)');
				$name = $this->cleanSpaces($cfield->name);
				
				$this->cfs[] = $name;
				$cfnames[] = "{$name} {$type}";
			}
			
			$this->cfidentifiers = implode(',', $cfidentifiers);
			
			$this->cfObject = new stdClass();
			$this->cfObject->arrayCustomfieldsNames = $this->cfs;
			$this->cfObject->customfieldsNames = ',';
			$this->cfObject->customfieldsNames .= implode(', ', $this->cfs);
			$this->cfObject->customfieldsColumns = implode(', ', $cfnames);
		}
	}
	
	protected function createQueryForCustomFields()
	{
		if ($this->cfidentifiers != null) {
			$this->customNames = ", f.idCustomField, f.name AS field, f.value ";
			
			$this->customFieldsQuery = "LEFT JOIN (SELECT cf.idCustomField, cf.name, fi.idContact, IF(fi.textValue = null, fi.numberValue, fi.textValue) AS value
									     FROM customfield AS cf 
										     JOIN fieldinstance AS fi ON (cf.idCustomField = fi.idCustomField) 
									     WHERE cf.idCustomField IN ({$this->cfidentifiers})) AS f ON(c.idContact = f.idContact)";
		}
		
	}
	
	protected function cleanSpaces($cadena){
//		$cadena = str_replace($cadena, " ", "");
//		$cadena = ereg_replace( "([ ]+)", "", $cadena );
		$cadena = preg_replace("([ ]+)", "", $cadena);
		return $cadena;
	}
	
	public function getCustomFieldsData()
	{
		return $this->cfObject;
	}
}