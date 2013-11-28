<?php
class ContactIterator implements Iterator
{
	public function __construct($idMail) 
	{
		$this->mail = $idMail;
		$start = 0;
		
		$this->contacts = $this->getContacts($start);
		
//		while ($this->contacts !== 0) {
//			$start += $ultimo = end($this->contacts['idContact']);
//			$this->contacts = $this->getContacts($start);
//		}
	}
	
	public function getContacts($start)
	{
		$sql1 = 'SELECT idContact FROM mxc WHERE idMail = ' . $this->mail . ' AND idContact > ' . $start . ' ORDER BY idContact LIMIT 100';
		$sql2 = 'SELECT c.idContact, c.name, c.lastName, e.email, f.idCustomField, f.textValue, f.numberValue 
					FROM (' . $sql1 . ') AS l 
						JOIN contact AS c ON(l.idContact = c.idContact)
						JOIN email AS e ON(c.idEmail = e.idEmail)
						LEFT JOIN fieldinstance AS f ON(c.idContact = f.idContact)';

		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($sql2);
		$contacts = $result->fetchAll();

		return $contacts;
	}
	
	public function current()
	{
		$contacts = array();
		$obj = new stdClass();
		
		$curr = current($this->contacts);
		
		$obj->idContact = $curr['idContact'];
		$obj->name = $curr['name'];
		$obj->lastName = $curr['lastName'];
		
		$contacts['contact'] = $obj;
        return $contacts;
	}
	
	public function key()
	{
		return key($this->contacts);
	}
	
	public function rewind()
	{
		reset($this->contacts);
	}
	
	public function next()
	{
		$var = next($this->contacts);
	}
	
	public function valid()
	{
		$key = key($this->contacts);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
	}
}