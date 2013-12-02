<?php
class ContactIterator implements Iterator
{
	public $mail;
	public $fields;
	public $contacts;
	public $start;
	public $offset;
	
	const ROWS_PER_FETCH = 1000;
	
	public function __construct(Mail $mail, $idFields) 
	{
		$this->mail = $mail->idMail;
		$this->fields = $idFields;
	}
	
	public function extractContactsFromDB($start = 0)
	{
		$sql1 = 'SELECT idContact FROM mxc WHERE idMail = ' . $this->mail . ' AND idContact > ' . $start . ' ORDER BY idContact LIMIT ' . self::ROWS_PER_FETCH;
		if (!$this->fields) {
			$sql = 'SELECT c.idContact, c.name, c.lastName, e.email, e.idEmail 
						FROM (' . $sql1 . ') AS l 
							JOIN contact AS c ON(l.idContact = c.idContact)
							JOIN email AS e ON(c.idEmail = e.idEmail)';
		}
		else {
			$sql = 'SELECT c.idContact, c.name, c.lastName, e.email, e.idEmail, f.name AS field, f.textValue, f.numberValue 
						FROM (' . $sql1 . ') AS l 
							JOIN contact AS c ON(l.idContact = c.idContact)
							JOIN email AS e ON(c.idEmail = e.idEmail)
							LEFT JOIN (SELECT cf.name, fi.idContact, fi.textValue, fi.numberValue FROM customfield AS cf JOIN fieldInstance AS fi ON (cf.idCustomField = fi.idCustomField) WHERE cf.idCustomField IN (' . $this->fields . ')) AS f ON(c.idContact = f.idContact)';
		}
		
		unset($this->contacts);
		
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($sql);
		$contacts = $result->fetchAll();
		Phalcon\DI::getDefault()->get('timerObject')->startTimer('Organizing', 'Organizing data');
		if (count($contacts) <= 0) {
			return false;
		}
//		Phalcon\DI::getDefault()->get('logger')->log('SQl: '. $sql);
		$this->offset = 0;
		
		if (!$this->fields) {
			$this->prepareContactsWithoutCustomFields($contacts);
		}
		else {
			$this->prepareContactsWithCustomFields($contacts);
		}
		$end = end($this->contacts);
		$this->start = $end['contact']['idContact'];
		Phalcon\DI::getDefault()->get('timerObject')->endTimer('Organizing Array');
		Phalcon\DI::getDefault()->get('logger')->log('Memory after Organize: ' . memory_get_peak_usage(true));
		
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
	
	protected function prepareContactsWithoutCustomFields($contacts)
	{
		Phalcon\DI::getDefault()->get('logger')->log('Sin Custom');
		$i = -1;
		$k = 0;
		$this->contacts = array();
		foreach ($contacts as $m) {
			if (count($this->contacts) == 0) {
				$c = array(
					'idContact' => $m['idContact'],
					'name' => $m['name'],
					'lastName' => $m['lastName']
				);

				$e = array(
					'email' => $m['email'],
					'idEmail' => $m['idEmail']
				);

				$f = array();
				
				$this->contacts[0]['contact'] = $c;
				$this->contacts[0]['email'] = $e;
				$this->contacts[0]['fields'] = $f;
			}
			else if ($this->contacts[$i]['email']['idEmail'] == $m['idEmail']) {
//				$i--;
//				$k--;
			}
			else {
				$c = array(
					'idContact' => $m['idContact'],
					'name' => $m['name'],
					'lastName' => $m['lastName']
				);

				$e = array(
					'email' => $m['email'],
					'idEmail' => $m['idEmail']
				);

				$f = array();
				
				$this->contacts[$k]['contact'] = $c;
				$this->contacts[$k]['email'] = $e;
				$this->contacts[$k]['fields'] = $f;
			}
			$i++;
			$k++;
		}
	}
	
	protected function prepareContactsWithCustomFields($contacts)
	{
		Phalcon\DI::getDefault()->get('logger')->log('Con Custom');
		$this->contacts = array();
		$i = -1;
		$k = 0;
		
		foreach ($contacts as $m) {
			if (count($this->contacts) == 0) {
				$c = array(
					'idContact' => $m['idContact'],
					'name' => $m['name'],
					'lastName' => $m['lastName']
				);

				$e = array(
					'email' => $m['email'],
					'idEmail' => $m['idEmail']
				);

				$f = array();
				if ($m['field'] !== null) {
					if ($m['textValue'] !== null) {
						$f[$m['field']] = $m['textValue'];
					}
					else if ($m['numberValue'] !== null) {
						$f[$m['field']] = $m['numberValue'];
					}
				}
				$this->contacts[0]['contact'] = $c;
				$this->contacts[0]['email'] = $e;
				$this->contacts[0]['fields'] = $f;
			}
			else if ($this->contacts[$i]['email']['idEmail'] == $m['idEmail']) {
				if ($m['textValue'] !== null) {
					$this->contacts[$i]['fields']['field'] = $m['textValue'];
				}
				else if ($m['numberValue'] !== null) {
					$this->contacts[$i]['fields']['field'] = $m['numberValue'];
				}
				$i--;
				$k--;
			}
			else {
				$c = array(
					'idContact' => $m['idContact'],
					'name' => $m['name'],
					'lastName' => $m['lastName']
				);

				$e = array(
					'email' => $m['email'],
					'idEmail' => $m['idEmail']
				);

				$f = array();
				if ($m['field'] !== null) {
					if ($m['textValue'] !== null) {
						$f[$m['field']] = $m['textValue'];
					}
					else if ($m['numberValue'] !== null) {
						$f[$m['field']] = $m['numberValue'];
					}
				}
				$this->contacts[$k]['contact'] = $c;
				$this->contacts[$k]['email'] = $e;
				$this->contacts[$k]['fields'] = $f;
			}
			$i++;
			$k++;
		}
	}
}