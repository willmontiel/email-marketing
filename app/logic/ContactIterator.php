<?php
class ContactIterator implements Iterator
{
	public $mail;
	public $fields;
	public $contacts;
	public $start;
	public $offset;
	
	const ROWS_PER_FETCH = 1000;
	
	public function __construct($idMail, $idFields) 
	{
		$this->mail = $idMail;
		$this->fields = $idFields;
	}
	
	public function extractContactsFromDB($start = 0)
	{
		$sql1 = 'SELECT idContact FROM mxc WHERE idMail = ' . $this->mail . ' AND idContact > ' . $start . ' ORDER BY idContact LIMIT ' . self::ROWS_PER_FETCH;
//		$sql1 = 'SELECT idContact FROM mxc WHERE idMail = ' . $this->mail . '  ORDER BY idContact LIMIT 50 OFFSET ' .  $start;
		$sql2 = ' SELECT cf.name, fi.idContact, fi.textValue, fi.numberValue FROM customfield AS cf JOIN fieldInstance AS fi ON (cf.idCustomField = fi.idCustomField) WHERE cf.idCustomField IN (' . $this->fields . ')';
		$sql3 = 'SELECT c.idContact, c.name, c.lastName, e.email, f.name AS field, f.textValue, f.numberValue 
					FROM (' . $sql1 . ') AS l 
						JOIN contact AS c ON(l.idContact = c.idContact)
						JOIN email AS e ON(c.idEmail = e.idEmail)
						LEFT JOIN (' . $sql2 . ') AS f ON(c.idContact = f.idContact)';

		unset($this->contacts);
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($sql3);
//		$db = Phalcon\DI::getDefault()->get('logger')->log('Memory before fetch: ' . memory_get_peak_usage(true));
//		Phalcon\DI::getDefault()->get('timerObject')->startTimer('fetching', 'Fetching data');
		$this->contacts = $result->fetchAll();
//		Phalcon\DI::getDefault()->get('timerObject')->endTimer('fetching');
//		$db = Phalcon\DI::getDefault()->get('logger')->log('Memory after fetch: ' . memory_get_peak_usage(true));
		
		if (count($this->contacts) <= 0) {
			return false;
		}
		$this->offset = 0;
		$end = end($this->contacts);
		$this->start = $end['idContact'];
//		$this->start += count($this->contacts);
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
		$contacts = array();
		$obj = new stdClass();
		
		$curr = $this->contacts[$this->offset];
		
		$obj->idContact = $curr['idContact'];
		$obj->name = $curr['name'];
		$obj->lastName = $curr['lastName'];
		
		$contacts['contact'] = $obj;
        return $contacts;
	}
	
	public function key()
	{
		return $this->contact[$this->offset]['idContact'];
	}
	
	
	
	public function next()
	{
		$this->offset++;
		//array_shift($this->contacts);
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
}