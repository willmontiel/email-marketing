<?php
class WebversionController extends ControllerBase 
{
	public function showAction($parameters)
	{
		$idenfifiers = explode("-", $parameters);
		list($idLink, $idMail, $idContact, $md5) = $idenfifiers;
		
		$src = $this->urlManager->getBaseUri(true) . 'webversion/show/1-' . $idMail . '-' . $idContact;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		if ($md5 == $md5_2) {
			$html = $this->startWebVersionProcess($idLink, $idMail, $idContact, FALSE);
			if(!$html) {
				return $this->response->redirect('error/link');
			}
			$this->logger->log("HTML: {$html}");
			$this->view->setVar('html', "lala");
		}
		else {
			return $this->response->redirect('error/link');
		}
	}
	
	public function shareAction($parameters)
	{
		$idenfifiers = explode("-", $parameters);
		list($idLink, $idMail, $idContact, $social, $md5) = $idenfifiers;
		
		$src = $this->urlManager->getBaseUri(true) . 'webversion/share/1-' . $idMail . '-' . $idContact . '-' .$social;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		if ($md5 == $md5_2) {
			$html = $this->startWebVersionProcess($idLink, $idMail, $idContact, $social);
			if(!$html) {
				return $this->response->redirect('error/link');
			}
			$this->view->setVar('html', $html);
		}
		else {
			return $this->response->redirect('error/link');
		}
	}
	
	protected function startWebVersionProcess($idLink, $idMail, $idContact, $social)
	{
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
			
		if ($mail && $mailContent) {
			
			$account = Account::findFirstByIdAccount($mail->idAccount);
			$domain = Urldomain::findFirstByIdUrlDomain($account->idUrlDomain);
			
			if(!$contact) {
				$contact = new Contact();
				$contact->idContact = 0;
				$dbase = new Dbase();
			}
			else {
				$dbase = Dbase::findFirstByIdDbase($contact->idDbase);
			}

			try{
				$webversionobj = new WebVersionObj();
				$webversionobj->setAccount($account);
				$webversionobj->setDbase($dbase);
				$webversionobj->setUrlDomain($domain);
				$html = $webversionobj->createWebVersion($mail, $mailContent, $contact, $social);
			}
			catch (\Exception $e) {
				$this->logger->log('Exception ' . $e);
			}
			catch (\InvalidArgumentException $e) {
				$this->logger->log('Exception ' . $e);
			}
			
			return $html;
		}
		else {
			return FALSE;
		}
	}
}