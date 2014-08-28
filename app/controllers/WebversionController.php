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
			$this->logger->log('1');
			$html = $this->startWebVersionProcess($idLink, $idMail, $idContact, FALSE);
			$this->logger->log('2');
			if(!$html) {
				return $this->response->redirect('error/link');
			}
			$this->logger->log('3');
//			$this->logger->log("HTML: {$html}");
//			$this->logger->log("Lala");
			$l = "LALA";
			$this->view->setVar('html', $l);
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
				$this->logger->log("1.1");
				$html = $webversionobj->createWebVersion($mail, $mailContent, $contact, $social);
				$this->logger->log("1.2");
			}
			catch (\Exception $e) {
				$this->logger->log('Exception ' . $e);
			}
			catch (\InvalidArgumentException $e) {
				$this->logger->log('Exception ' . $e);
			}
			$this->logger->log("1.3");
			return $html;
		}
		else {
			return FALSE;
		}
	}
}