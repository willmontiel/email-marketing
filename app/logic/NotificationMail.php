<?php

require_once "../app/library/swiftmailer/lib/swift_required.php";

class NotificationMail extends TestMail
{
	public $content;
	
	function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function sendMailInForm(Contact $contact, $jsoncontent)
	{
		$fullcontent = json_decode($jsoncontent);
		$this->setBody($fullcontent->mail);
		$this->createPlaintext();
		
		$transport = Swift_SendmailTransport::newInstance();
		$swift = Swift_Mailer::newInstance($transport);
		
		$subject = $fullcontent->subject;
		$from = array($fullcontent->fromemail => $fullcontent->fromname);
		$to = array($contact->email->email => $contact->name);
		$content = $this->getBody();
		$text = $this->getPlainText();
		$replyTo = $fullcontent->reply;

		$message = new Swift_Message($subject);
		$message->setFrom($from);
		$message->setTo($to);
		$message->setBody($content, 'text/html');
		$message->addPart($text, 'text/plain');

		if ($replyTo != null) {
			$message->setReplyTo($replyTo);
		}

		$sendMail = $swift->send($message, $failures);

		if (!$sendMail){
			$this->logger->log("Error while sending mail: " . print_r($failures));
		}
			
		if ($sendMail){
			$this->flashSession->success("Se ha enviado el mensaje exitosamente");
		}
	}
	
	protected function setBody($contentobj)
	{
		$editorObj = new HtmlObj();
		$editorObj->assignContent(json_decode($contentobj));
		$content = utf8_decode($editorObj->replacespecialchars($editorObj->render()));
		$this->body = $content;
//		$replace = '<body>
//						<center>
//							<table border="0" cellpadding="0" cellspacing="0" width="600px" style="border-collapse:collapse;background-color:#444444;border-top:0;border-bottom:0">
//								<tbody>
//									<tr>
//										<td align="center" valign="top" style="border-collapse:collapse">
//											<span style="padding-bottom:9px;color:#eeeeee;font-family:Helvetica;font-size:12px;line-height:150%">"' . $this->message . '" — ' . $this->mail->fromName . '</span>
//										</td>
//									</tr>
//								</tbody>
//							</table>
//						</center>';
//		
//		$this->body = str_replace('<body>', $replace, $content);
	}
}
