<?php
/**
 * @RoutePrefix("/api/mails")
 */
class ApimailController extends ControllerBase
{
	/**
	 * @Post("/")
	 */
	public function newmailAction()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$db->begin();
		
		$contentsraw = $this->getRequestContent();
		$contentsT = json_decode($contentsraw);
		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		try {
			$mailapiwrapper = new MailApiWrapper($this->logger, $this->modelsManager, $this->asset);
			$mailapiwrapper->setAccount($this->user->account);
			$mailapiwrapper->validateContent($contentsT->mail);
			$contentsT->mail->type = "Html";

			$contentsT->mail->target = $mailapiwrapper->createTarget($contentsT->mail->target);

			$MailWrapper = new MailWrapper();
			$MailWrapper->setMail(null);
			$MailWrapper->setContent($contentsT->mail);
			$MailWrapper->setAccount($this->user->account);
			$MailWrapper->setSocialsKeys($this->fbapp->iduser, $this->fbapp->token, $this->twapp->iduser, $this->twapp->token);
			
			$MailWrapper->processDataForMail();
			$mail = $MailWrapper->saveMail();
			
			$content = $mailapiwrapper->getContent($contentsT->content);
			$MailWrapper->createHtmlMailContent($content);
		
			$mailapiwrapper->attachment_mail($contentsT->mail, $mail);
			
			$db->commit();
			
			$mailapiwrapper->send_mail_to_process($mail);
			
			$response = $mailapiwrapper->response_new_mail($mail);
			
			$this->traceSuccess("Create mail, idMail: {$mail->idMail}");
			
			return $this->setJsonResponse($response, 200);
		}
		catch (\InvalidArgumentException $e) {
			$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
			$this->logger->log("InvalidArgumentException: {$e}");
			$db->rollback();
			return $this->setJsonResponse(array('error' => $e->getMessage()), 200);
		}
		catch (\Exception $e) {
			$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
			$this->logger->log("Exception: {$e}");
			$db->rollback();
			return $this->setJsonResponse(array('error' => 'Ha ocurrido un error contacte al administrador'), 200);
		}
	}
	
	/**
	 * @Get("/{idMail:[0-9]+}/statistics/{idContact:[0-9]+}")
	 */
	public function mailcontactstatisticsAction($idMail, $idContact)
	{
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 and idAccount = ?2",
			"bind" => array(1 => $idMail, 2 => $this->user->account->idAccount)
		));
		
		$contact = Contact::findFirst(array(
			"conditions" => "idContact = ?1",
			"bind" => array(1 => $idContact)
		));
		
		if(!$mail && !$contact) {
			return $this->setJsonResponse(array('error' => 'Ha ocurrido un error contacte al administrador'), 400, 'Ha ocurrido un error contacte al administrador');
		}
		
		try{
			$mailapiwrapper = new MailApiWrapper($this->logger, $this->modelsManager);
			$response = $mailapiwrapper->findMailPerContact($mail, $contact);
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log("InvalidArgumentException: {$e}");
			return $this->setJsonResponse(array('error' => $e->getMessage()), 200);
		}
		catch (\Exception $e) {
			$this->logger->log("Exception: {$e}");
			return $this->setJsonResponse(array('error' => 'Ha ocurrido un error, contacte al administrador'), 200);
		}
		
		return $this->setJsonResponse(array('contact' => $response), 200);
	}
	
	
	/**
	 * @Get("/{idMail:[0-9]+}/statisticslink")
	 */
	public function maillinkstatisticsAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail && $mail->status == 'Sent') {
			$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));

			$parameters2 = array(1, $mail->idMail, 'complete');
			$link = $linkdecoder->encodeLink('share/results', $parameters2);
			
			return $this->setJsonResponse(array('link' => $link), 200);
		}
		else {
			return $this->setJsonResponse(array('error' => 'Ha ocurrido un error contacte al administrador'), 200);
		}
		
		return $this->setJsonResponse(array('error' => "mail not found"), 200);
	}
}
