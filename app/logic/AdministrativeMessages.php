<?php
require_once "swiftmailer-5.0.3/lib/swift_required.php";
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
		
		$link = '<a href="http://localhost';
		$link .= $url;
		$link .= '" style="text-decoration: underline;">';
		$link .= 'Click aqui</a>';
		
		Phalcon\DI::getDefault()->get('logger')->log("Url: " . $link);
		
		if ($msg) {
			
			$message = str_replace('tmpurl', $link, $msg->msg);
			$plainText = str_replace('tmpurl', $link, $msg->text);
			
			$this->subject = $msg->subject;
			$this->from = $msg->from;
			$this->html = $message;
			$this->to = $to;
			$this->text = $plainText;
		}
		else {
			Phalcon\DI::getDefault()->get('logger')->log("There's no data!");
		}
	}
	
	public function sendMessage()
	{
//		Phalcon\DI::getDefault()->get('logger')->log("Subject " . $this->subject);
//		Phalcon\DI::getDefault()->get('logger')->log("From " . $this->from);
//		Phalcon\DI::getDefault()->get('logger')->log("Html " . $this->html);
//		Phalcon\DI::getDefault()->get('logger')->log("To " . $this->to);
//		Phalcon\DI::getDefault()->get('logger')->log("Text " . $this->text);
//		Phalcon\DI::getDefault()->get('logger')->log("1 ");
		$transport = Swift_SmtpTransport::newInstance($this->mta->domain, $this->mta->port);
//		Phalcon\DI::getDefault()->get('logger')->log("2 ");
		$swift = Swift_Mailer::newInstance($transport);
//		Phalcon\DI::getDefault()->get('logger')->log("3 ");
		
		$message = new Swift_Message($this->subject);
		$message->setFrom($this->from);
		$message->setBody($this->html, 'text/html');
		$message->setTo($this->to);
		$message->addPart($this->text, 'text/plain');

		$recipients = $swift->send($message, $failures);
		
		if ($recipients){
			Phalcon\DI::getDefault()->get('logger')->log('Message successfully sent!');
		}
		else {
			Phalcon\DI::getDefault()->get('logger')->log('Error while sending messages');
			Phalcon\DI::getDefault()->get('logger')->log('Failures: ' . $failures);
		}
	}
}