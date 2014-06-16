<?php
//require_once "../app/library/swiftmailer/lib/swift_required.php";
require_once "../../library/swiftmailer/lib/swift_required.php";
class AdministrativeMessages
{
	protected $msg;
	protected $subject;
	protected $from;
	protected $html;
	protected $to;
	protected $text;
	
	public function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->mta = $di['mtadata'];
		$this->logger = $di['logger'];
	}
	
	public function createRecoverpassMessage($to, $url = null)
	{
		$msg = Adminmsg::findFirst(array(
			'conditions' => 'type = ?1',
			'bind' => array(1 => 'Recoverpass')
		));

		if ($msg) {
			$msg->msg = str_replace('tmpurl', $this->url, $msg->msg);
			$msg->text = str_replace('tmpurl', $this->url, $msg->text);
			
			$this->msg = $msg;
			$this->to = $to;
			$this->url = $url;
		}
		else {
			throw new Exception('Administrative message not found!');
		}
		
	}
	
	public function createLimitExceededMessage($to)
	{
		$msg = Adminmsg::findFirst(array(
			'conditions' => 'type = ?1',
			'bind' => array(1 => 'LimiteExceeded')
		));

		if ($msg) {
			$this->msg = $msg;
			$this->to = $to;
		}
		else {
			throw new Exception('Administrative message not found!');
		}
	}
	
	public function sendMessage()
	{
		$transport = Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
		$swift = Swift_Mailer::newInstance($transport);
		
		$message = new Swift_Message($this->msg->subject);
		
		/*Cabeceras de configuración para evitar que Green Arrow agregue enlaces de tracking*/
		$headers = $message->getHeaders();
		$headers->addTextHeader('X-GreenArrow-MailClass', 'SIGMA_NEWEMKTG_DEVEL');
		
		$message->setFrom($this->msg->from);
		$message->setBody($this->msg->msg, 'text/html');
		$message->setTo($this->to);
		$message->addPart($this->msg->text, 'text/plain');
		
		$this->logger->log("Preparandose para enviar mensaje a: {$this->to}");
		
		$recipients = $swift->send($message, $failures);
		
		if ($recipients){
			Phalcon\DI::getDefault()->get('logger')->log('Limit exceeded message successfully sent!');
		}
		else {
			throw new Exception('Error while sending message: ' . $failures);
		}
	}
}