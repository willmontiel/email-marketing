<?php

require_once '../../logic/MailField.php';

class MailFieldTest extends PHPUnit_Framework_TestCase
{
    protected $object;

	public function __construct() {

	}
    protected function setUp()
    {
    }

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptySubjectIsNotAllowed()
	{
		$obj = new MailField('html', 'texto', '');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptySubjectIsNotNull()
	{
		$obj = new MailField('html', 'texto', null);
	}
	
	public function testReturnCorrectCustomFields()
	{
		$result = array(
			'standard' => null,
			'custom' => 'nombre, apellido, edad'
		);
		
		$obj = new MailField('Mi nombre es: %%NOMBRE%%, mi apellido es :%%APELLIDO%%, mi edad: %%EDAD%%', 'texto', 'subject');
		$fields = $obj->getCustomFields();
		$this->assertEquals($result, $fields);
		
		$obj2 = new MailField('Mi nombre es: %%NOMBRE%%, mi apellido es :%%APELLIDO%%, mi edad: %%EDAD%%', 'Este es su asunto Sr. %%APELLIDO%%', 'Este es su asunto Sr. %%APELLIDO%%');
		$fields2 = $obj2->getCustomFields();
		$this->assertEquals($result, $fields2);
//		
		$obj3 = new MailField('Mi nombre es: %%NOMBRE%%, mi apellido es :%%APELLIDO, mi edad: %%EDAD%%', 'Este es su asunto Sr. %%APELLIDO%%', 'Este es su asunto Sr. %%APELLIDO%%');
		$fields3 = $obj3->getCustomFields();
		$result3 = array(
			'standard' => null,
			'custom' => 'nombre, edad, apellido'
		);
		$this->assertEquals($result3, $fields3);
//		
		$obj4 = new MailField('Mi nombre es: %%NAME%%, %%LASTNAME%% %%NOMBRE%%%%NOMBRE_ÑA%%, mi apellido es :%%APELLIDO, mi edad: %%EDAD_A%%', 'Este es su asunto Sr. %%APELLIDO/%%', 'Este es su asunto Sr. %%APELLIDO56%%');
		$fields4 = $obj4->getCustomFields();
		$result4 = array(
			'standard' => 'name, lastname',
			'custom' => 'nombre, edad_a, apellido56'
		);
		$this->assertEquals($result4, $fields4);
	}	
	
	public function testReturnMailWithCorrectCustomFieldsReplace()
	{	
		$obj = new MailField('Mi correo es: %%EMAIL%%, Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%%', 'text', 'subject');
		
		$contact = array(
			'email' => 'jose@xxx.com',
			'name' => 'José de jesús',
			'lastName' => 'De la santisima sangre del señor',
			'edad' => '21',
		);
		
		$obj->getCustomFields();
		$result = $obj->processCustomFields($contact);
		
		$content = array(
			'html' => 'Mi correo es: jose@xxx.com, Mi nombre es: José de jesús, mi apellido es :De la santisima sangre del señor, mi edad: 21',
			'text' => 'text',
			'subject' => 'subject'
		);
		
		$this->assertEquals($content, $result);
		
		/*.......................................................................................................................................*/
		/*Atributo del arreglo de informacion de contacto como vacío*/
		
		$obj2 = new MailField('Mi correo es: %%EMAIL%%, Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%%', 'text', 'subject');
		
		$contact2 = array(
			'email' => 'jose@xxx.com',
			'name' => 'José de jesús',
			'lastName' => 'De la santisima sangre del señor',
			'edad' => '',
		);
		
		$obj2->getCustomFields();
		$result2 = $obj2->processCustomFields($contact2);
//		
		$content2 = array(
			'html' => 'Mi correo es: jose@xxx.com, Mi nombre es: José de jesús, mi apellido es :De la santisima sangre del señor, mi edad:  ',
			'text' => 'text',
			'subject' => 'subject'
		);
//		
		$this->assertEquals($content2, $result2);
		
		/*.......................................................................................................................................*/
		/*Atributo de arreglo de informacion de contacto como null*/
		
		$obj3 = new MailField('Mi correo es: %%EMAIL%%, Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%%', 'text', 'subject');
		
		$contact3 = array(
			'email' => 'jose@xxx.com',
			'name' => 'José de jesús',
			'lastName' => 'De la santisima sangre del señor',
			'edad' => null,
		);
		
		$obj3->getCustomFields();
		$result3 = $obj3->processCustomFields($contact3);
		
		$content3 = array(
			'html' => 'Mi correo es: jose@xxx.com, Mi nombre es: José de jesús, mi apellido es :De la santisima sangre del señor, mi edad:  ',
			'text' => 'text',
			'subject' => 'subject'
		);
		
		$this->assertEquals($content3, $result3);

	}
	
}
