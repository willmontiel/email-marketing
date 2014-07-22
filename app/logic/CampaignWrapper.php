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
	
	public function setAutosend(Autosend $autosend)
	{
		$this->autosend = $autosend;
	}
	
	public function createAutosend($content, $category, $type)
	{
		$autosend = new Autosend();
		
		$autosend->idAccount = $this->account->idAccount;
		$autosend->category = $category;
		$autosend->type = $type;
		$autosend->createdon = time();
		
		$nextmailing = new NextMailingObj();
		$nextmailing->setSendTime($content['hour'] . ':' . $content['minute'] . ' ' . $content['meridian']);
		$nextmailing->setFrequency('Daily');
		$nextmailing->setLastSentDate(null);
		
		$this->populateAutoSendObj($autosend, $nextmailing, $content);
		
	}
	
	public function updateAutomaticSend($content)
	{
		$nextmailing = new NextMailingObj();
		$nextmailing->setSendTime($content['hour'] . ':' . $content['minute'] . ' ' . $content['meridian']);
		$nextmailing->setFrequency('Daily');
		$nextmailing->setLastSentDate(null);
		
		$this->populateAutoSendObj($this->autosend, $nextmailing, $content);
	}
	
	protected function populateAutoSendObj(Autosend $autosend, NextMailingObj $nextmailing, $content)
	{
		$time = array(
			'hour' => $content['hour'],
			'minute' => $content['minute'],
			'meridian' => $content['am_pm'],
		);

		$days = array();
		$real_days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

		foreach ($real_days as $rd) {
			if( isset($content[$rd]) ) {
				$days[] = $rd;
			}
		}
		
		$nextmailing->setDaysAllowed($days);
		
		$autosend->activated = ( isset($content['activated']) ) ? 1 : 0 ;
		$autosend->name = $content['name'];
		$autosend->target = 'Nothing yet';
		$autosend->subject = $content['subject'];
		$autosend->from = $content['from'];
		$autosend->reply = $content['reply'];
		$autosend->time = json_encode($time);
		$autosend->days = json_encode($days);
		$autosend->content = $content['content'];
		$autosend->nextMailing = $nextmailing->getNextSendTime();
		
		if (!$autosend->save()) {
			foreach ($autosend->getMessages() as $msg) {
				throw new Exception("Error while saving automatic campaign, {$msg}!");
			}
		}
	}
}
