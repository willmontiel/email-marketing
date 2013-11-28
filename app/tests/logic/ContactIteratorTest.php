<?php

require_once '../../logic/ContactIterator.php';

class ContactIteratorTest extends PHPUnit_Framework_TestCase {

	protected $object;

	protected function setUp() 
	{
		
	}
	
	public function testReturnCorrectContacts()
	{
		$contactIterator = new ContactIterator(113);
		
		foreach ($contactIterator as $contact) {
			$x = array();
			$obj = new stdClass();
		
			$obj->idContact = 197;
			$obj->name = 'Fulano5';
			$obj->lastName = 'Perez5';

			$x['contact'] = $obj;

			$this->assertEquals($x, $contact);
		}
		
	}

}
