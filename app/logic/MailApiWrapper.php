<?php

class MailApiWrapper extends BaseWrapper
{
	function __construct($logger, $modelsManager, $assetsrv)
	{
		$this->logger = $logger;
		$this->modelsManager = $modelsManager;
		$this->assetsrv = $assetsrv;
	}
	
	public function createTarget($target)
	{
		try{
			switch ($target->type) {
				case 'dbases':
					$query = $this->modelsManager->createQuery("SELECT name FROM Dbase WHERE idDbase IN (" . implode(',',$target->ids) . ") AND idAccount = " . $this->account->idAccount);
					$result = $query->execute();
					break;

				case 'contactlists':
					$query = $this->modelsManager->createQuery("SELECT Contactlist.name FROM Contactlist JOIN Dbase ON (Contactlist.idDbase = Dbase.idDbase) WHERE Contactlist.idContactlist IN (" . implode(',',$target->ids) . ") AND Dbase.idAccount = " . $this->account->idAccount);
					$result = $query->execute();
					break;

				case 'segments':
					$query = $this->modelsManager->createQuery("SELECT Segment.name FROM Segment JOIN Dbase ON (Segment.idDbase = Dbase.idDbase) WHERE Segment.idSegment IN (" . implode(',',$target->ids) . ") AND Dbase.idAccount = " . $this->account->idAccount);
					$result = $query->execute();
					break;
				default :
					throw new InvalidArgumentException('Type of target undefined');
					break;
			}
			
			if(count($result) <= 0) {
				throw new InvalidArgumentException('Target undefined');
			}

			$names = array();

			foreach ($result as $r) {
				$names[] = $r->name; 
			}
			
			$obj = array(
						array(
							"type" => "top-panel",
							"serialization" => array("criteria" => $target->type),
							"totalContacts" => "0"
						),
						array(
							"type" => "list-panel",
							"serialization" => array("items" => $target->ids, "names" => $names, "conditions" => "all")
						)
					);

			return json_encode($obj);
		} catch (\InvalidArgumentException $e) {
			throw new InvalidArgumentException($e->getMessage());
		} catch (\Exception $e) {
			$this->logger->log("Exception: {$e}");
			throw new InvalidArgumentException('wrong target');
		}
	}
	
	public function getContent($contentobj)
	{
		switch ($contentobj->type) {
			case 'url':
				if(!filter_var($contentobj->content, FILTER_VALIDATE_URL)) {
					throw new InvalidArgumentException('wrong URL');
				}
				try {
					$dir = $this->assetsrv->dir . $this->account->idAccount . "/images";
					$getHtml = new LoadHtml();
					$content = $getHtml->gethtml($contentobj->content, 'load', $dir, $this->account, true);
				}
				catch(Exception $e) {
					throw new Exception($e->getMessage());
				}
				break;
			case 'html':
					$content = $contentobj->content;
				break;
			default :
				throw new InvalidArgumentException('wrong type of content');
				break;
		}
				
		return $content;
	}
	
	public function validateContent($content)
	{
		if(!isset($content->name) || empty($content->name)) {
			throw new InvalidArgumentException('wrong name');
		}
		if(!isset($content->subject) || empty($content->subject)) {
			throw new InvalidArgumentException('wrong subject');
		}
		if(!isset($content->replyTo) || empty($content->replyTo)) {
			throw new InvalidArgumentException('wrong replyTo');
		}
		if(!isset($content->scheduleDate) || empty($content->scheduleDate)) {
			throw new InvalidArgumentException('wrong scheduleDate');
		}
		if(!isset($content->sender) || empty($content->sender)) {
			throw new InvalidArgumentException('wrong sender');
		}
		else {
			$parts = explode('/', $content->sender);
			if(!((isset($parts[0]) && !empty($parts[0])) && (\filter_var(trim(strtolower($parts[0])), FILTER_VALIDATE_EMAIL)) && (isset($parts[1]) && !empty($parts[1])))) {
				throw new InvalidArgumentException('wrong sender');
			}
		}
	}
	
	public function findMailPerContact(Mail $mail, Contact $contact)
	{
		$mxc = Mxc::findFirst(array(
			"conditions" => "idMail = ?1 and idContact = ?2",
			"bind" => array(1 => $mail->idMail, 2 => $contact->idContact)
		));
		
		if(!$mxc) {
			throw new InvalidArgumentException('no statistics');
		}
		
		$response = array(
							"idContact" => $mxc->idContact,
							"idMail" => $mxc->idMail,
		);
		
		$response["opening"] = ($mxc->opening > 0) ? date('d-m-Y H:i a', $mxc->opening) : 0;
		$response["clicks"] = $mxc->clicks;
		$response["bounced"] = ($mxc->bounced > 0) ? date('d-m-Y H:i a', $mxc->bounced) : 0;
		$response["spam"] = ($mxc->spam > 0) ? date('d-m-Y H:i a', $mxc->spam) : 0;
		$response["unsubscribe"] = ($mxc->unsubscribe > 0) ? date('d-m-Y H:i a', $mxc->unsubscribe) : 0;
		
		return $response;
	}
	
	public function attachment_mail($content, Mail $mail)
	{
		if(isset($content->attachment) && !empty($content->attachment)) {

			$path_parts = pathinfo($content->attachment);
			
			$dir = tempnam(sys_get_temp_dir(),'AF');
			
			$data = new stdClass();
			$data->fileName = $path_parts['basename'];
			$data->fileSize = $this->curl_get_file_size($content->attachment);
			$data->fileType = $path_parts['extension'];
			$data->tmpDir = $dir;
			
			$file = file_get_contents($content->attachment);
			
			file_put_contents($dir, $file);
			
			$att = new AttachmentObj();
			$att->setAccount($this->account);
			$att->setMail($mail);
			$att->setData($data);
			$att->uploadAttachment(false);

			$mail->attachment = 1;
			if (!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log("Error while updating attachment in mail: {$msg}");
				}
				throw new InvalidArgumentException('Mail attachment could not be updated');
			}

//			$dir = $this->assetsrv->dir . $this->account->idAccount . '/attachments/';
//			if (!file_exists($dir)) {
//				mkdir($dir, 0777, true);
//			}
//			$dir.= $path_parts['basename'];
//			copy($content->attachment, $dir)
		}
	}
	
	public function curl_get_file_size( $url ) {

		$result = -1;
		$curl = curl_init( $url );

		curl_setopt( $curl, CURLOPT_NOBODY, true );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		$data = curl_exec( $curl );
		curl_close( $curl );

		if( $data ) {
		  $content_length = "unknown";
		  $status = "unknown";

		  if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
			$status = (int)$matches[1];
		  }

		  if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
			$content_length = (int)$matches[1];
		  }
		  
		  if( $status == 200 || ($status > 300 && $status <= 308) ) {
			$result = $content_length;
		  }
		}

		return $result;
  }
	
	public function response_new_mail($mail)
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
	
	public function send_mail_to_process($mail)
	{
		$schedule = Mailschedule::findFirstByIdMail($mail->idMail);

		$mailwrapper = new MailController();
		$mailwrapper->sendMailToProcess($mail, $schedule);
	}
}
