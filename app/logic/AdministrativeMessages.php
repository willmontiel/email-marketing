<?php
$path =  \Phalcon\DI\FactoryDefault::getDefault()->get('path');
require_once "{$path->path}app/library/swiftmailer/lib/swift_required.php";

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
			'bind' => array(1 => 'RecoverPass')
		));

		if ($msg) {
			$msg->msg = str_replace('tmpurl', $url, $msg->msg);
			$msg->text = str_replace('tmpurl', $url, $msg->text);
			
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
			'bind' => array(1 => 'LimitExceeded')
		));

		if ($msg) {
			$this->msg = $msg;
			$this->to = $to;
		}
		else {
			throw new Exception('Administrative message not found!');
		}
	}
	
	public function createServerConnectionMessage($to, $connection, $campaign_name, $campaign_url , Account $account)
	{
		$msg = Adminmsg::findFirst(array(
			'conditions' => 'type = ?1',
			'bind' => array(1 => 'ServerConnection')
		));

		if ($msg) {
			
			$msg->msg = str_replace('%%ACCOUNT_NAME%%', $account->companyName, $msg->msg);
			$msg->text = str_replace('%%ACCOUNT_NAME%%', $account->companyName, $msg->text);
			
			$msg->msg = str_replace('%%ACCOUNT_URL%%', $campaign_url, $msg->msg);
			$msg->text = str_replace('%%ACCOUNT_URL%%', $campaign_url, $msg->text);
			
			$msg->msg = str_replace('%%CAMPAIGN_NAME%%', $campaign_name, $msg->msg);
			$msg->text = str_replace('%%CAMPAIGN_NAME%%', $campaign_name, $msg->text);
			
			if($connection){
				$msg->msg = str_replace('%%PING_SIGMA%%', $connection, $msg->msg);
				$msg->text = str_replace('%%PING_SIGMA%%', $connection, $msg->text);
			}
			else {
				$msg->msg = str_replace('%%PING_SIGMA%%', 0, $msg->msg);
				$msg->text = str_replace('%%PING_SIGMA%%', 0, $msg->text);
			}
			
			$this->msg = $msg;
			$this->to = $to;
		}
		else {
			throw new Exception('Administrative message not found!');
		}
	}
	
	public function createTemporarySuccessMessage($to)
	{
		$msg = new stdClass();
		$msg->subject = "Se envio el Newsletter de El Pais";
		$msg->from = "noreply@sigmamovil.com";
		$msg->msg = '<html><head><title></title></head><body><h2><span style="font-family: Helvetica;"><strong>Senores de Sigma Movil:<br /></strong></span></h2><table><tbody><tr><td></td></tr><tr><td><span style="font-family: Helvetica;">Se fue el envio de EL PAIS el dia ' . date('d/m/Y', time()) . ' a las ' . date('H:i a', time()) . '.<br /><br /></span></td></tr></tbody></table></body></html>';
		$msg->text = 'Senores de Sigma Movil:=========================================================================Se fue el envio de EL PAIS el dia ' . date('d/m/Y', time()) . ' a las ' . date('H:i a', time());
		
		$this->msg = $msg;
		$this->to = $to;
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
			Phalcon\DI::getDefault()->get('logger')->log('Message successfully sent!');
		}
		else {
			throw new Exception('Error while sending message: ' . $failures);
		}
	}
}