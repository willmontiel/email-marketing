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
	
	public function setAutoresponder(Autoresponder $autoresponder)
	{
		$this->autoresponder = $autoresponder;
	}
	
	public function setPreviewImage($image)
	{
		$this->image = $image;
	}
	
	public function createAutoresponder($content, $type, $contentsource)
	{
		$wrapper = new MailWrapper();
		
		try {
			$wrapper->setAccount($this->account);
			$obj = new stdClass();
			$obj->sender = $content['from_email'] . '/' . $content['from_name'];
			$wrapper->setContent($obj);
			$sender = $wrapper->getSender();
		}
		catch (Exception $e) {
			$this->logger->log($e);
			throw new Exception($wrapper->getResponseMessageForEmber()->message);
		}
		
		$autoresponder = new Autoresponder();
		
		$autoresponder->idAccount = $this->account->idAccount;
		$autoresponder->type = $type;
		$autoresponder->contentsource = $contentsource;
		$autoresponder->createdon = time();
		
		$nextmailing = new NextMailingObj();
		$nextmailing->setSendTime($content['hour'] . ':' . $content['minute'] . ' ' . $content['meridian']);
		$nextmailing->setFrequency('Daily');
		$nextmailing->setLastSentDate(null);
		
		$this->populateAutoSendObj($autoresponder, $nextmailing, $content);
		
	}
	
	public function updateAutomaticSend($content)
	{
		$wrapper = new MailWrapper();
		
		try {
			$wrapper->setAccount($this->account);
			$obj = new stdClass();
			$obj->sender = $content['from_email'] . '/' . $content['from_name'];
			$wrapper->setContent($obj);
			$sender = $wrapper->getSender();
		}
		catch (Exception $e) {
			$this->logger->log($e);
			throw new Exception($wrapper->getResponseMessageForEmber()->message);
		}
		
		$nextmailing = new NextMailingObj();
		$nextmailing->setSendTime($content['hour'] . ':' . $content['minute'] . ' ' . $content['meridian']);
		$nextmailing->setFrequency('Daily');
		$nextmailing->setLastSentDate(null);
		
		$this->populateAutoSendObj($this->autoresponder, $nextmailing, $content);
	}
	
	public function updateAutomaticSendStatus($status)
	{
		$new_status = ($status == 'true') ? 1 : 0 ;
		$this->autoresponder->active = $new_status;
		
		if (!$this->autoresponder->save()) {
			foreach ($this->utosend->getMessages() as $msg) {
				throw new Exception("Error while updating status of automatic campaign, {$msg}!");
			}
		}
	}
	
	protected function populateAutoSendObj(Autoresponder $autoresponder, NextMailingObj $nextmailing, $content)
	{
		$time = array(
			'hour' => $content['hour'],
			'minute' => $content['minute'],
			'meridian' => $content['meridian'],
		);

		$days = array();
		$real_days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

		foreach ($real_days as $rd) {
			if( isset($content[$rd]) ) {
				$days[] = $rd;
			}
		}
		
		$nextmailing->setDaysAllowed($days);
				
		$autoresponder->active = ( isset($content['active']) ) ? 1 : 0 ;
		$autoresponder->name = $content['name'];
		
		if(empty($content['target']) || empty($content['target_selected'])){
			throw new Exception('Recuerde seleccionar destinatarios');
		}
		
		$autoresponder->target = json_encode(array('destination' => $content['target'], 'ids' => implode(",", $content['target_selected']), 'filter' => ''));
		
		if( empty($content['meta-tag']) ) {
			$autoresponder->subject = json_encode( array('mode' => 'static', 'text' => $content['subject']));
		}
		else {
			$autoresponder->subject = json_encode( array('mode' => 'dynamic', 'text' => 'Meta Tag', 'tag' => 'description' ) );
		}
		
		$autoresponder->from = json_encode(array('email' => $content['from_email'], 'name' => $content['from_name']));
		$autoresponder->reply = $content['reply'];
		$autoresponder->time = json_encode($time);
		$autoresponder->days = json_encode($days);
		$autoresponder->content = json_encode( array( 'url' => $content['content'] ) );
		$autoresponder->nextExecution = $nextmailing->getNextSendTime();
		
		if($this->image) {
			$autoresponder->previewData = $this->createCampaignPreviewImage($this->image);
		}
		
		if (!$autoresponder->save()) {
			foreach ($autoresponder->getMessages() as $msg) {
				throw new Exception("Error while saving automatic campaign, {$msg}!");
			}
		}
	}
	
	public function createCampaignPreviewImage($image)
	{
		$imgObj = new ImageObject();
		$imgObj->createFromBase64($image);
		$imgObj->resizeImage(200, 250);
		return $imgObj->getImageBase64();
	}
	
	public function updateCampaignPreviewImage($image)
	{
		$this->autoresponder->previewData = $this->createCampaignPreviewImage($image);

		if (!$this->autoresponder->save()) {
			foreach ($this->autoresponder->getMessages() as $msg) {
				$this->logger->log("Error: " . $msg);
			}
		}
	}
	
	public function insertCanvasHeader($id, $html, $url)
	{
		$script1 =  '<head>
						<title>Preview</title>
						<script type="text/javascript" src="' . $url->get('js/html2canvas.js'). '"></script>
						<script type="text/javascript" src="' . $url->get('js/jquery-1.8.3.min.js') .'"></script>
						<script>
							function createPreviewImage(img) {
							$.ajax({
								url: "' . $url->get('campaign/previewimage') . '/' . $id .'",
								type: "POST",			
								data: { img: img},
								success: function(){}
								});
							}
						</script>';

		$script2 = '<script> 
						html2canvas(document.body, { 
							onrendered: function (c) { 
								c.getContext("2d");	
								createPreviewImage(c.toDataURL("image/png"));
							},
						});
				   </script></body>';

		$search = array('<head>', '</body>');
		$replace = array($script1, $script2);

		return str_ireplace($search, $replace, $html);
	}
	
	protected function isAValidDomain($domain)
	{
		$invalidDomains = array(
			'yahoo',
			'hotmail',
			'live',
			'gmail',
			'aol'
		);
		
		$d = explode('.', $domain);
		
		foreach ($invalidDomains as $invalidDomain) {
			if ($invalidDomain == $d[0]) {
				return false;
			}
		}
		return true;
	}
	
}
