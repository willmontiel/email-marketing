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
	
	public function setPreviewImage($image)
	{
		$this->image = $image;
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
	
	public function updateAutomaticSendStatus($status)
	{
		$new_status = ($status == 'true') ? 1 : 0 ;
		$this->autosend->activated = $new_status;
		
		if (!$this->autosend->save()) {
			foreach ($this->utosend->getMessages() as $msg) {
				throw new Exception("Error while updating status of automatic campaign, {$msg}!");
			}
		}
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
		
		if($this->image) {
			$autosend->previewData = $this->createCampaignPreviewImage($this->image);
		}
		
		if (!$autosend->save()) {
			foreach ($autosend->getMessages() as $msg) {
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
		$this->autosend->previewData = $this->createCampaignPreviewImage($image);

		if (!$this->autosend->save()) {
			foreach ($this->autosend->getMessages() as $msg) {
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
	
}
