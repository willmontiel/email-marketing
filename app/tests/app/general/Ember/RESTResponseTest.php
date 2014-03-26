<?php

require_once '../../../../general/Ember/RESTResponse.php';

class RESTResponseTest extends PHPUnit_Framework_TestCase {

	protected $object;

	public function __construct() {
		
	}

	protected function setUp() {
		
	}

	protected function getSourceData() {
		$c1 = array(
			"activatedOn" => "07/03/2014 14:03",
			"bouncedOn" => "",
			"createdOn" => "07/03/2014 14:03",
			"email" => "william.montiel@sigmamovil.com",
			"id" => 1,
			"ipActivated" => "127.0.0.1",
			"ipSubscribed" => "127.0.0.1",
			"isActive" => true,
			"isBounced" => false,
			"isEmailBlocked" => false,
			"isSpam" => false,
			"isSubscribed" => true,
			"lastName" => "Montiel",
			"name" => "Will",
			"spamOn" => "",
			"subscribedOn" => "07/03/2014 14:03",
			"unsubscribedOn" => "",
			"updatedOn" => "07/03/2014 14:03",
		);
		
		$contacts = array($c1);
		
		return $contacts;
	}

	protected function getDataOutPut() {
		$c1 = array(
			"activatedOn" => "07/03/2014 14:03",
			"bouncedOn" => "",
			"createdOn" => "07/03/2014 14:03",
			"email" => "william.montiel@sigmamovil.com",
			"id" => 1,
			"ipActivated" => "127.0.0.1",
			"ipSubscribed" => "127.0.0.1",
			"isActive" => true,
			"isBounced" => false,
			"isEmailBlocked" => false,
			"isSpam" => false,
			"isSubscribed" => true,
			"lastName" => "Montiel",
			"name" => "Will",
			"spamOn" => "",
			"subscribedOn" => "07/03/2014 14:03",
			"unsubscribedOn" => "",
			"updatedOn" => "07/03/2014 14:03",
		);

		$data = array($c1);
		$pagination = array('pagination' => array(
			'page' => 1,
			'limit' => 20,
			'total' => 1,
			'availablepages' => 1,		
		));
		
		$contacts = array(
			'contacts' => $data,
			'meta' => $pagination
		);
		
		return $contacts;
	}
	
	protected function getSourceDataForContacts() {
		
		$c1 = array(
			"activatedOn" => "07/03/2014 14:03",
			"bouncedOn" => "",
			"createdOn" => "07/03/2014 14:03",
			"email" => "william.montiel@sigmamovil.com",
			"id" => 1,
			"ipActivated" => "127.0.0.1",
			"ipSubscribed" => "127.0.0.1",
			"isActive" => true,
			"isBounced" => false,
			"isEmailBlocked" => false,
			"isSpam" => false,
			"isSubscribed" => true,
			"lastName" => "Montiel",
			"name" => "Will",
			"spamOn" => "",
			"subscribedOn" => "07/03/2014 14:03",
			"unsubscribedOn" => "",
			"updatedOn" => "07/03/2014 14:03",
		);
		
		$c2 = array(
			"activatedOn" => "07/03/2014 14:03",
			"bouncedOn" => "",
			"createdOn" => "07/03/2014 14:03",
			"email" => "fernando.gonzalez@sigmamovil.com",
			"id" => 1,
			"ipActivated" => "127.0.0.1",
			"ipSubscribed" => "127.0.0.1",
			"isActive" => true,
			"isBounced" => false,
			"isEmailBlocked" => false,
			"isSpam" => false,
			"isSubscribed" => true,
			"lastName" => "Gonzalez",
			"name" => "Fernando",
			"spamOn" => "",
			"subscribedOn" => "07/03/2014 14:03",
			"unsubscribedOn" => "",
			"updatedOn" => "07/03/2014 14:03",
		);
		
		$c3 = array(
			"activatedOn" => "07/03/2014 14:03",
			"bouncedOn" => "",
			"createdOn" => "07/03/2014 14:03",
			"email" => "rrorro.pirrorro@sigmamovil.com",
			"id" => 1,
			"ipActivated" => "127.0.0.1",
			"ipSubscribed" => "127.0.0.1",
			"isActive" => true,
			"isBounced" => false,
			"isEmailBlocked" => false,
			"isSpam" => false,
			"isSubscribed" => true,
			"lastName" => "pirrorro",
			"name" => "rrorro",
			"spamOn" => "",
			"subscribedOn" => "07/03/2014 14:03",
			"unsubscribedOn" => "",
			"updatedOn" => "07/03/2014 14:03",
		);
		
		$contacts = array($c1, $c2, $c3);
		
		return $contacts;
	}
	
	protected function getSourceDataForDbases()
	{
		$c1 = array(
			'idDbase' => 15,
			'idAccount' => 14,
			'name' => 'My data base',
			'description' => 'This is my data base'
		);
		
		$c2 = array(
			'idDbase' => 40,
			'idAccount' => 14,
			'name' => 'My data base 2',
			'description' => 'This is my data base number two'
		);
		
		$dbases = array($c1, $c2);
		
		return $dbases;
	}

	protected function getSourceDataForLists()
	{
		$c1 = array(
			'idContactlist' => 1,
			'idDbase' => 15,
			'name' => 'My contact list',
			'description' => 'This is my contact list'
		);
		
		$dbases = array($c1);
		
		return $dbases;
	}
	
	protected function getDataOutPutForMultiplesDataSources() {
	
		$pagination = array('pagination' => array(
			'page' => 1,
			'limit' => 20,
			'total' => 3,
			'availablepages' => 1,		
		));
		
		$contacts = array(
			'contacts' => $this->getSourceDataForContacts(),
			'dbases' => $this->getSourceDataForDbases(),
			'lists' => $this->getSourceDataForLists(),
			'meta' => $pagination
		);
		
		return $contacts;
	}
	
	protected function getMockUpDataSource($name, $rows, $currentPage, $pages, $records)
	{
		$datasourceMockup = $this->getMock('EmailMarketing\General\ModelAccess\DataSource', array('getName', 'getRows', 'getCurrentPage', 'getTotalPages', 'getTotalRecords'));

		$datasourceMockup->expects($this->any())
				->method('getName')
				->will($this->returnValue($name));

		$datasourceMockup->expects($this->any())
				->method('getRows')
				->will($this->returnValue($rows));

		$datasourceMockup->expects($this->any())
				->method('getCurrentPage')
				->will($this->returnValue($currentPage));

		$datasourceMockup->expects($this->any())
				->method('getTotalPages')
				->will($this->returnValue($pages));

		$datasourceMockup->expects($this->any())
				->method('getTotalRecords')
				->will($this->returnValue($records));
		
		return $datasourceMockup;
	}
	
	/*
	 * Probar la estructura de datos devuelta por el RecorSet con un DataSource
	 */
	public function testStructureDataForOneDataSource() {
		
		$dataSource = $this->getMockUpDataSource('contacts', $this->getSourceData(), 1, 1, 1);
		
		$object = new \EmailMarketing\General\Ember\RESTResponse();
		
		$dss = array($dataSource);
		
		foreach ($dss as $ds) {
			$object->addDataSource($ds);
		}

		$this->assertEquals($object->getRecords(), $this->getDataOutPut(), 'Datos de contactos');
	}

	/*
	 * Probar la estructura de datos devuelta por el RecorSet con varios DataSources
	 */
	public function testStructureDataForMultiplesDataSource() {
		
		$dataSource1 = $this->getMockUpDataSource('contacts', $this->getSourceDataForContacts(), 1, 2, 6);
		$dataSource2 = $this->getMockUpDataSource('dbases', $this->getSourceDataForDbases(), 1, 2, 1);
		$dataSource3 = $this->getMockUpDataSource('lists', $this->getSourceDataForLists(), 1, 1, 3);
		
		$object = new \EmailMarketing\General\Ember\RESTResponse();
		
		$dss = array($dataSource1, $dataSource2, $dataSource3);
		
//		$count = count($dss);
//		for ($i = 0; $i < $count; $i ++) {
//			$last = false;
//			if ($i == ($count - 1)) {
//				echo 'Es el ultimo' . $last . PHP_EOL;
//				$object->addDataSource($dss[$i], true);
//			}
//			else {
//				echo 'No es el ultimo' . $last . PHP_EOL;
//				$object->addDataSource($dss[$i], false);
//			}
//			
//		}
		
		foreach ($dss as $ds) {
			$object->addDataSource($ds);
		}

		$this->assertEquals($object->getRecords(), $this->getDataOutPutForMultiplesDataSources(), 'Datos de contactos');
	}
}
