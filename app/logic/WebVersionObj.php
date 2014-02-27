<?php
class WebVersionObj extends BaseWrapper
{
	function __construct() {
		$this->urlManager = Phalcon\DI::getDefault()->get('urlManager');
	}
	
	public function setDbase(Dbase $dbase) {
		$this->dbase = $dbase;
	}
	
	public function setDomain(Domain $domain) {
		$this->domain = $domain;
	}

	public function createWebVersion(Mail $mail, Mailcontent $mailContent, Contact $contact)
	{
		if (trim($mailContent->content) === '') {
			throw new \InvalidArgumentException("Error mail's content is empty");
		}
		else if ($mail->type == 'Editor') {
			$htmlObj = new HtmlObj();
			$htmlObj->assignContent(json_decode($mailContent->content));
			$html = $htmlObj->render();
		}
		else {
			$html =  html_entity_decode($mailContent->content);
		}
		$imageService = new ImageService($this->account, $this->domain, $this->urlManager);
		$linkService = new LinkService($this->account, $mail, $this->urlManager);
		$prepareMail = new PrepareMailContent($linkService, $imageService);
		list($content, $links) = $prepareMail->processContent($html);
		$mailField = new MailField($content, $mailContent->plainText, $mail->subject, $this->dbase->idDbase);
		$cf = $mailField->getCustomFields();

		switch ($cf) {
			case 'No Fields':
				$customFields = false;
				$fields = false;
				break;
			case 'No Custom':
				$fields = true;
				$customFields = false;
				break;
			default:
				$fields = true;
				$customFields = $cf;
				break;
		}
		$contact = get_object_vars($contact);
		if ($fields) {
			$c = $mailField->processCustomFields($contact);
			$html = $c['html'];
		}
		else {
			$html = $content->html;
		}
		$trackingObj = new TrackingUrlObject();
		$htmlWithTracking = $trackingObj->getTrackingUrl($html, $mail->idMail, $contact['idContact'], $links);

		return $htmlWithTracking;
	}
}