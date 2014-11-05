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
			$mailapiwrapper = new MailApiWrapper($this->logger, $this->modelsManager);
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
			$response = $this->response_new_mail($mail);
			
			$db->commit();
			$this->traceSuccess("Create mail, idMail: {$mail->idMail}");
			
			return $this->setJsonResponse($response, 200);
		}
		catch (\InvalidArgumentException $e) {
			$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
			$this->logger->log("InvalidArgumentException: {$e}");
			$db->rollback();
			return $this->setJsonResponse(array('errors' => $e->getMessage(), 'status' => 'fail'));
		}
		catch (\Exception $e) {
			$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
			$this->logger->log("Exception: {$e}");
			$db->rollback();
			return $this->setJsonResponse(array('errors' => 'Ha ocurrido un error contacte al administrador', 'status' => 'fail'));
		}
	}
	
	protected function response_new_mail($mail)
	{
		$obj = array(
						"mail" => array(
											"idMail" => $mail->idMail,
											"status" => $mail->status,
											"name" => $mail->name,
											"subject" => $mail->subject,
											"sender" => $mail->fromEmail . "/" . $mail->fromName,
											"replyTo" => $mail->replyTo
										),
						"status" => "ok"
					);
		return $obj;
	}
}
