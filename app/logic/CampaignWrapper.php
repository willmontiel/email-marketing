<?php

class CampaignWrapper extends BaseWrapper
{
	private $dbase;
	
	public function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setDbase(Dbase $dbase)
	{
		$this->dbase = $dbase;
	}
}
