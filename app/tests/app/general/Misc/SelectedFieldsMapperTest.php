<?php

//namespace EmailMarketing\General\Misc;

date_default_timezone_set('America/Bogota');

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-03-20 at 14:49:39.
 */
class SelectedFieldsMapperTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var SelectedFieldsMapper
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new \EmailMarketing\General\Misc\SelectedFieldsMapper;
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testThrowsExceptionWhenNoDatabase()
	{
		$this->object->processMapping();
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFailureWhenNoEmail()
	{
		$this->object->assignMapping(array('a' => 0, 'b' => 1));
		$this->object->setDbase($this->createDbaseMock());
		
		$this->object->processMapping();
			
	}

	/*
	 * ********************************************************************
	 * Pruebas con el 1st dataset
	 * Son pruebas sencillas que revisan el funcionamiento completo
	 * de la clase
	 */
	public function testSimpleMappingNames()
	{
		$this->prepareFirstDataSet();
		// Verify names match
		$this->assertEquals($this->getDataSet1_Names(), $this->object->getFieldnames(), 'Nombres de campos no coinciden');
	}
	
	public function testSimpleMappingExtrafieldsDefinitions()
	{
		$this->prepareFirstDataSet();
		$this->assertEquals($this->getDataSet1_Extrafields(), $this->object->getAdditionalFields());
		$this->assertEquals($this->getDataSet1_Extrafields_Insert(), $this->object->getAdditionalFieldsForInsert());
	}

//	public function testSimpleMappingVars()
//	{
//		// Para examinar un atributo del objeto
//		$this->prepareFirstDataSet();
//		$this->assertAttributeEquals(array(), 'transformations', $this->object);
//	}
	
	public function testSimpleMappingCSVMapping()
	{
		$this->prepareFirstDataSet();
		$data = $this->getDataSet1_CSVMapping();
		
		foreach ($data as $i => $r) {
			$this->assertEquals($r['out'], $this->object->mapValues($r['in']), 'Validating [' . $i . '] record');
		}
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Invalid Email Address
	 */
	public function testSimpleEmailValidation()
	{
		$this->prepareFirstDataSet();
		$data = $this->getDataSet1_InvalidEmails();
		
		$this->object->mapValues($data);
		
	}
	
	protected function prepareFirstDataSet()
	{
		$this->object->setDbase($this->createDbaseMock());
		$this->object->assignMapping($this->getDataSet1_Input());
		$this->object->processMapping();
	}

	
	protected function getDataSet1_Input()
	{
		// 
		return array(
			'email' => 1,
			'name'  => 3,
			'3'     => 4,
			'12'	=> null,
			'17'    => 5,
			'6'		=> 7,
			'24'	=> 8,
		);
	}
	
	protected function getDataSet1_CSVMapping()
	{
		// Entrada de datos
		// Salida esperada
		return array(
			array(
				'in' => array(
					'dummy0',
					'email@somewhere.com',
					'dummy2',
					'some name',
					'Custom field 3',
					'1234A23',
					'dummy 5',
					'2014-01-01 23:00:00',
					'dummy extra'
				),
				'out' => array(
					'email@somewhere.com',
					'somewhere.com',
					'some name',
					'Custom field 3',
					1234,
					1388635200,
				)
			),
			array(
				'in' => array(
					'dummy0',
					'SomeOtherEmail@SomeWhereElse.com',
					'dummy2',
					'some name',
					'Custom field 3',
					'Invalid custom field 17',
					'dummy 5',
					'2013-12-31',
					'dummy extra'
				),
				'out' => array(
					'someotheremail@somewhereelse.com',
					'somewhereelse.com',
					'some name',
					'Custom field 3',
					0,
					1388466000,
				)
			)
		);
	}

	protected function getDataSet1_InvalidEmails()
	{
		// Entrada de datos
		// Salida esperada
		return array(
			'dummy0',
			'invalidemail.com',
			'dummy2',
			'some name',
			'Custom field 3',
			'1234A23',
			'dummy 5',
			'2014-01-01 23:00:00',
			'dummy extra'
		);
	}
	
	protected function getDataSet1_Names()
	{
		return array(
			'email',
			'domain',
			'name',
			'cf_3',
			'cf_17',
			'cf_6',
		);
	}
	
	protected function getDataSet1_Extrafields()
	{
		return array(
			'cf_3'  => ' VARCHAR(100) DEFAULT NULL',
			'cf_17' => ' VARCHAR(100) DEFAULT NULL',
			'cf_6'  => ' INT(10) DEFAULT 0',
		);
	}

	protected function getDataSet1_Extrafields_Insert()
	{
		return array(
			'cf_3'  => array(3, 'textValue'),
			'cf_17' => array(17, 'textValue'),
			'cf_6'  => array(6, 'numberValue'),
		);
	}

	protected function createDbaseMock()
	{
		$mock = $this->getMock('\Dbase');
		// CustomFields
		$cf = json_decode('[{"idCustomField": 3, "type": "Text"},{"idCustomField": 17, "type": "Numerical"},{"idCustomField": 6, "type": "Date"},{"idCustomField": 12, "type": "Text"},{"idCustomField": 40, "type": "Select"}]');

		// El campo customFields es dinamico
		$mock->expects($this->any())
				->method('__get')
				->will($this->returnValue($cf));
		
		return $mock;
	}
}
