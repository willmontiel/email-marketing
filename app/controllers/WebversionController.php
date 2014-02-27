<?php
class WebversionController extends ControllerBase 
{
	public function showAction($parameters)
	{
		$info = $_SERVER['HTTP_USER_AGENT'];
		$idenfifiers = explode("-", $parameters);
		list($idLink, $idMail, $idContact, $md5) = $idenfifiers;
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $urlManager->getBaseUri(true) . 'webversion/show/1-' . $idMail . '-' . $idContact;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		if ($md5 == $md5_2) {
			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			)); 
			
			$mailContent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			)); 
			
			$contact = Contact::findFirst(array(
				'conditions' => 'idContact = ?1',
				'bind' => array(1 => $idContact)
			));
			if ($mail && $mailContent && $contact) {
				$account = Account::findFirstByIdAccount($mail->idAccount);
				$domain = Urldomain::findFirstByIdUrlDomain($account->idUrlDomain);
				$dbase = Dbase::findFirstByIdDbase($contact->idDbase);
				
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
				$urlManager = $this->urlManager;
				$imageService = new ImageService($account, $domain, $urlManager);
				$linkService = new LinkService($account, $mail, $urlManager);
				$prepareMail = new PrepareMailContent($linkService, $imageService);
				list($content, $links) = $prepareMail->processContent($html);
				$mailField = new MailField($content, $mailContent->plainText, $mail->subject, $dbase->idDbase);
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
				$htmlWithTracking = $trackingObj->getTrackingUrl($html, $idMail, $contact['idContact'], $links);
				$this->view->setVar('html', $htmlWithTracking);
			}
		}
	}
}