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
				
				try{
					$webversionobj = new WebVersionObj();
					$webversionobj->setAccount($account);
					$webversionobj->setDbase($dbase);
					$webversionobj->setUrlDomain($domain);
					$html = $webversionobj->createWebVersion($mail, $mailContent, $contact);
				}
				catch (Exception $e) {
					$this->logger->log('Exception ' . $e);
				}
				catch (InvalidArgumentException $e) {
					$this->logger->log('Exception ' . $e);
				}
				
				$this->view->setVar('html', $html);
			}
			else {
				return $this->response->redirect('error/link');
			}
		}
		else {
			return $this->response->redirect('error/link');
		}
	}
}