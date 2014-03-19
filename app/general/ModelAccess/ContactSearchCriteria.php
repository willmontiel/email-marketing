<?php

namespace EmailMarketing\General\ModelAccess;

/**
 * This function divides the search criteria. returns emails, domains and free text found
 */
class ContactSearchCriteria
{
	public $emails = array();
	public $domains = array();
	public $freeText = array();
	public $criteria;
	
	public function __construct($text) 
	{
            if (trim($text) === '') {
                    throw new \InvalidArgumentException('Search criteria empty!!');
            }
            
            \Phalcon\DI::getDefault()->get('logger')->log("ContactSearchCriteria recibio esto: " . $text);
            
            $this->criteria = $text;

            $criterias = explode(' ', $text);

            $values = array();
            foreach ($criterias as $criteria) {
                    if (!empty($criteria) && !in_array($criteria, $values)) {
                            //Validamos si es un email válido. En caso de ser asi lo agregamos al arreglo de emails
                            if (filter_var($criteria, FILTER_VALIDATE_EMAIL)) {
                                    list($user, $edomain) = preg_split("/@/", $criteria, 2);
                                    //Buscamos que no haya un dominio idéntico al del correo, de ser asi lo quitamos, porque el correo sería
                                    //un criterio de búsqueda mas efectivo
                                    $d = '@' . $edomain;
                                    if (in_array($d, $values)) {
                                            $key = array_search($edomain, $this->domains);
                                            echo $key;
                                            unset($this->domains[$key]);
                                    }
                                    $values[] = $criteria;
                                    $this->emails[] = $criteria;
                            }
                            //Válidamos si el criterio empieza por "@" si es asi es un dominio 
                            else if (substr($criteria, 0, 1) == '@') {
                                    $domain = substr($criteria, 1);
                                    $domains = array();
                                    //Buscamos que no haya un correo con el mismo dominio. De ser así no lo agregamos por el correo es un 
                                    //criterio de busqueda mas eféctivo
                                    foreach ($values as $value) {
                                            if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                                    list($user, $edomain) = preg_split("/@/", $value, 2);
                                                    $domains[] = $edomain;
                                            }
                                    }

                                    if (!in_array($domain, $domains)) {
                                            $values[] = $criteria;
                                            $this->domains[] = $domain;
                                    }
                            }
                            //Si no es un dominio, ni un correo es un texto libre(Un nombre, apellido, campo personalizado) y lo agregamos
                            // al arreglo freeText
                            else {
                                    $values[] = $criteria;
                                    $this->freeText[] = $criteria;
                            }
                    }
            }
	}
	
	/*
	 * Returns the emails found in the search criteria
	 * @return array
	 */
	public function getEmails()
	{
		return $this->emails;
	}
	
	/**
	 * Returns the domains found in the search criteria with no "@"
	 * @return array
	 */
	public function getDomains()
	{
		return $this->domains;
	}
	
	/**
	 * Returns the free text (names. lastNames, customfields) found in the search criteria
	 * @return array
	 */
	public function getFreeText()
	{
		return $this->freeText;
	}
	
	/**
	 * Returns the original criteria
	 * @return string
	 */
	public function getCriteria()
	{
		return $this->criteria;
	}
}