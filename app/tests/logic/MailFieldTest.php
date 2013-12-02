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
		$obj = new MailField('html', 'texto', '', '9, 11');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptySubjectIsNotNull()
	{
		$obj = new MailField('html', 'texto', null, '9, 11');
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyIdDbasesIsNotNull()
	{
		$obj = new MailField('html', 'texto', 'subject', null);
	}
	
	public function testReturnCorrectCustomFields()
	{
		$result = "39, 40";
		
		$obj = new MailField('Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%% y mi pelcula es %%PELICULAS%%', 'texto', 'subject', "9, 11");
		$fields = $obj->getCustomFields();
		$this->assertEquals($result, $fields);
		
		$obj2 = new MailField('Mi nombre es: %%NOMBRE%%, mi apellido es :%%APELLIDO%%, mi edad: %%EDAD%% %%PELICULAS%%', 'Este es su asunto Sr. %%APELLIDO%%', 'Este es su asunto Sr. %%APELLIDO%%', "9, 11");
		$fields2 = $obj2->getCustomFields();
		$this->assertEquals($result, $fields2);
////		
		$obj3 = new MailField('Mi nombre es: %%NOMBRE%%, mi apellido es :%%APELLIDO, mi edad: %%EDAD%% %%PELICULAS%%', 'Este es su asunto Sr. %%APELLIDO%%', 'Este es su asunto Sr. %%APELLIDO%%', "9, 11");
		$fields3 = $obj3->getCustomFields();
		$this->assertEquals($result, $fields3);
////		
		$obj4 = new MailField('Mi nombre es: %%NAME%%, %%LASTNAME%% %%NOMBRE%%%%NOMBRE_ÑA%%, mi apellido es :%%APELLIDO, mi edad: %%EDAD_A%%', 'Este es su asunto Sr. %%APELLIDO/%%', 'Este es su asunto Sr. %%APELLIDO56%%', "9, 11");
		$fields4 = $obj4->getCustomFields();
		$this->assertEquals(false, $fields4);
	}	
	
	public function testReturnMailWithCorrectCustomFieldsReplace()
	{	
		$obj = new MailField('Mi correo es: %%EMAIL%%, Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%%', 'text', 'subject', "9, 11");
		
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
		
		$obj2 = new MailField('Mi correo es: %%EMAIL%%, Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%%', 'text', 'subject', "9, 11");
		
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
		
		/*Atributo de arreglo de informacion de contacto como null*/
		
		$obj3 = new MailField('Mi correo es: %%EMAIL%%, Mi nombre es: %%NAME%%, mi apellido es :%%LASTNAME%%, mi edad: %%EDAD%%', 'text', 'subject', "9, 11");
		
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
