<?php
require_once "../app/library/swiftmailer/lib/swift_required.php";
class AdministrativeMessages
{
	public $subject;
	public $from;
	public $html;
	public $to;
	public $text;
	
	public function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->mta = $di['mtadata'];
	}
	
	public function createRecoverpassMessage($url, $to)
	{
		$msg = Adminmsg::findFirst(array(
			'conditions' => 'type = ?1',
			'bind' => array(1 => 'Recoverpass')
		));
		
		Phalcon\DI::getDefault()->get('logger')->log("Url: " . $url);
		
		if ($msg) {
			$message = str_replace('tmpurl', $url, $msg->msg);
			$plainText = str_replace('tmpurl', $url, $msg->text);
			
			$this->subject = $msg->subject;
			$this->from = $msg->from;
			$this->html = $message;
			$this->to = $to;
			$this->text = $plainText;
		}
		else {
			throw new InvalidArgumentException('Administrative message not found!');
		}
	}
	
	public function sendMessage()
	{
		$transport = Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
		$swift = Swift_Mailer::newInstance($transport);
		
		$message = new Swift_Message($this->subject);
		
		/*Cabeceras de configuración para evitar que Green Arrow agregue enlaces de tracking*/
		$headers = $message->getHeaders();
		$headers->addTextHeader('X-GreenArrow-MailClass', 'SIGMA_NEWEMKTG_DEVEL');
		
		$message->setFrom($this->from);
		$message->setBody($this->html, 'text/html');
		$message->setTo($this->to);
		$message->addPart($this->text, 'text/plain');

		$recipients = $swift->send($message, $failures);
		
		if ($recipients){
			Phalcon\DI::getDefault()->get('logger')->log('Recovery pass message successfully sent!');
		}
		else {
			throw new InvalidArgumentException('Error while sending message: ' . $failures);
		}
	}
}