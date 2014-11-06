<?php

class MailApiWrapper extends BaseWrapper
{
	function __construct($logger, $modelsManager)
	{
		$this->logger = $logger;
		$this->modelsManager = $modelsManager;
	}
	
	public function createTarget($target)
	{
		try{
			switch ($target->type) {
				case 'dbases':
					$query = $this->modelsManager->createQuery("SELECT name FROM Dbase WHERE idDbase IN (" . implode(',',$target->ids) . ")");
					$result = $query->execute();
					break;

				case 'contactlists':
					$query = $this->modelsManager->createQuery("SELECT name FROM Contactlist WHERE idContactlist IN (" . implode(',',$target->ids) . ")");
					$result = $query->execute();
					break;

				case 'segments':
					$query = $this->modelsManager->createQuery("SELECT name FROM Segment WHERE idSegment IN (" . implode(',',$target->ids) . ")");
					$result = $query->execute();
					break;
				default :
					throw new InvalidArgumentException('Type of target undefined');
					break;
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
							"serialization" => array("items" => array( $target->ids ), "names" => $names, "conditions" => "all")
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
					$getHtml = new LoadHtml();
					$content = $getHtml->gethtml($contentobj->content, false, false, $this->account, true);
				}
				catch(Exception $e) {
					throw new Exception($e->getMessage());
				}
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
}
