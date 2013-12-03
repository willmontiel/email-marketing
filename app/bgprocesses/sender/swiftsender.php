<?php
require_once "swift_required.php";

$subject = 'Hello from Mailing, PHP!';
$from = array('ivan.barona@sigmamovil.com' =>'Ivan Barona');
$to = array(
 'ivan.barona@sigmamovil.com'  => 'Ivan Barona',
 'william.montiel@sigmamovil.com' => 'Will I Am'
);

$text = "Mailing speaks plaintext";
$html = '<table style="background-color: #a6f0a0; width: 100%;"><tr><td style="padding: 20px;"><center><table style="width: 550px;" width="550px" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 100%; background-color: transparent;"><table style="width: 100%; border-collapse: collapse; table-layout: fixed;" cellpadding="0"><tbody></tbody></table></td></tr><tr><td style="width: 100%; background-color: transparent;"><table style="width: 100%; border-collapse: collapse; table-layout: fixed;" cellpadding="0"><tbody></tbody></table></td></tr><tr><td style="width: 100%; background-color: transparent;"><table style="width: 100%; border-collapse: collapse; table-layout: fixed;" cellpadding="0"><tbody><tr><td style="word-break: break-all;"><h2 style="text-align: center;">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</h2></td></tr></tbody></table></td></tr><tr><td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tbody><tr><td style="width: 50%; background-color: transparent;"><table style="width: 100%; border-collapse: collapse; table-layout: fixed;" cellpadding="0"><tbody><tr><td align="center" style="width: 169px;" width="169px"><img src="http://localhost/emarketing/assets/13/images/277.jpg" alt="02.jpg" style="height: 120px; width: 169px;" height="120" width="169"></td></tr></tbody></table></td><td style="width: 50%; background-color: transparent;"><table style="width: 100%; border-collapse: collapse; table-layout: fixed;" cellpadding="0"><tbody><tr><td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tr><td style="word-break: break-all;"><p style="text-align: center;"><strong></strong><span style="color: rgb(79, 129, 189);"><strong>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</strong></span></p></td><td align="left" style="width: 98px;" width="98px"><img src="http://localhost/emarketing/assets/13/images/280.png" alt="04.png" style="height: 41px; width: 98px;" height="41" width="98"></td></tr></table></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="width: 100%; background-color: transparent;"><table style="width: 100%; border-collapse: collapse; table-layout: fixed;" cellpadding="0"><tbody></tbody></table></td></tr></tbody></table></center></td></tr></table>';

$transport = Swift_SmtpTransport::newInstance('mailing.sigmamovil.com', 25);
//$transport->setUsername('');
//$transport->setPassword('');
$swift = Swift_Mailer::newInstance($transport);

$message = new Swift_Message($subject);
$message->setFrom($from);
$message->setBody($html, 'text/html');
$message->setTo($to);
$message->addPart($text, 'text/plain');

$recipients = $swift->send($message, $failures);

if ($recipients){
 echo 'Message successfully sent!';
} 
else {
 echo "There was an error:\n";
 print_r($failures);
}