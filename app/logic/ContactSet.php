<?php

namespace EmailMarketing\General\ContactsSearcher;

class ContactSet
{
	protected $account;
	protected $search;
	protected $idDbase;
	protected $idContactlist;
	protected $idSegment;

	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setSearchCriteria($search)
	{
		$this->search = $search;
	}
	
	public function setDbaseID($idDbase)
	{
		$this->idDbase = $idDbase;
	}
	
	public function setContactListID($idContactlist)
	{
		$this->idContactlist = $idContactlist;
	}
	
	public function setSegmentID($idSegment)
	{
		$this->idSegment = $idSegment;
	}
	
	public function load()
	{
		$search = $this->validateAndGetSearchPatterns();
	}
	
	private function validateAndGetSearchPatterns()
	{
//		Phalcon\DI::getDefault()->get('logger')->log("Text: " . $text);
		$array = explode(' ', $this->search);
		
		$emails = array();
		$domains = array();
		$texts = array();
		$values = array();
		foreach ($array as $a) {
			if (!empty($a) && !in_array($a, $values)) {
				if (filter_var($a, FILTER_VALIDATE_EMAIL)) {
					list($user, $edomain) = preg_split("/@/", $a, 2);
					if (in_array($edomain, $values)) {
						$key = array_search($edomain, $values);
						 unset($values[$key]);
					}
					$values[] = $a;
					$emails[] = $a;
				}
				else if (substr($a, 0, 1) == '@') {
					$domain = substr($a, 1);
					$emails = array();
					foreach ($values as $v) {
						if(filter_var($v, FILTER_VALIDATE_EMAIL)) {
							list($user, $edomain) = preg_split("/@/", $v, 2);
							$emails[] = $edomain;
						}
					}
					
					if (!in_array($domain, $emails)) {
						$values[] = $a;
						$domains[] = $domain;
					}
				}
				else {
					$values[] = $a;
					$texts[] = $a;
				}
			}
		}
		
		$search = new stdClass();
		
		$search->email = $emails;
		$search->domain = $domains;
		$search->text = $texts;
		
		Phalcon\DI::getDefault()->get('logger')->log("Search: " . print_r($search, true));
		return $search;
	}
}
