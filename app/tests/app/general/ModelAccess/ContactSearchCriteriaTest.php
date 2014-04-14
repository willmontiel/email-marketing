<?php

require_once '../../../../general/ModelAccess/ContactSearchCriteria.php';

class ContactSearchCriteriaTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		
	}
	
	/**
	* @expectedException InvalidArgumentException
	*/
	public function testEmptySearchCriteriaIsNotAllowed()
	{
		$search = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria('');
	}

	/**
	 * Probar que retorne los emails encontrados en el texto
	 */
	public function testEmailsFoundInText() 
	{
		$emails = array('justlookat@hotmail.com', 'william.montiel@sigmamovil.com');
		
		$search = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria('justlookat@hotmail.com william.montiel@sigmamovil.com');
		
		$this->assertEquals($search->getEmails(), $emails, 'Emails encontrados');
	}
	
	/**
	 * Probar que retorne los dominios (los retornará sin "@") encontrados en el texto
	 */
	public function testDomainsFoundInText() 
	{
		$domains = array('hotmail.com', 'sigmamovil.com');
		
		$search = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria('@hotmail.com @sigmamovil.com');
		
		$this->assertEquals($search->getDomains(), $domains, 'Domains encontrados');
	}
	
	/**
	 * Probar que retorne el texto libre (nombres, apellidos, campos personalizados) encontrados en el texto
	 */
	public function testFreeTextFoundInText() 
	{
		$freetext = array('Will', 'Montiel', 'Gonzalez');
		
		$search = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria('Will    Montiel  Gonzalez');
		
		$this->assertEquals($search->getFreeText(), $freetext, 'Free text encontrados');
	}
	
	/**
	 * Probar que retorne el texto libre, dominios y correos elétronicos encontrados en el texto
	 */
	public function testFreeTextAndDomainsAndEmailsFoundInText() 
	{
		$freetext = array('Will', 'Montiel', 'Gonzalez');
		$domains = array('gmail.com', 'yahoo.com');
		$emails = array('justlookat@hotmail.com', 'william.montiel@sigmamovil.com');
		
		$search = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria('@gmail.com @yahoo.com Will justlookat@hotmail.com   Montiel  william.montiel@sigmamovil.com Gonzalez');
		
		$this->assertEquals($search->getFreeText(), $freetext, 'Free text encontrados');
		$this->assertEquals($search->getDomains(), $domains, 'Domains encontrados');
		$this->assertEquals($search->getEmails(), $emails, 'Emails encontrados');
	}
	
	/**
	 * Probar que retorne el texto libre, dominios y correos elétronicos encontrados en el texto y en caso de que
	 * vaya un correo "lala@hotmail.com" y un dominio "@hotmail.com" solo retorne el correo por ser un criterio de busqueda
	 * mas efectivo
	 */
	public function testFreeTextAndDomainsAndEmailsFoundInTextMoreEfective() 
	{
		$freetext = array();
		$domains = array(1 => 'yahoo.com');
		$emails = array('justlookat@hotmail.com', 'william.montiel@sigmamovil.com');
		
		$search = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria('@hotmail.com @yahoo.com justlookat@hotmail.com  william.montiel@sigmamovil.com');
		
		$this->assertEquals($search->getFreeText(), $freetext, 'Free text encontrados');
		$this->assertEquals($search->getDomains(), $domains, 'Domains encontrados');
		$this->assertEquals($search->getEmails(), $emails, 'Emails encontrados');
	}
}
