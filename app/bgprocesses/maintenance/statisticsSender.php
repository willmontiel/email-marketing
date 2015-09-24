<?php
require_once '../bootstrap/phbootstrap.php';

$sSender = new StatisticsSender();
$sSender->init();

class StatisticsSender
{
    public function __construct() 
    {
        $di =  \Phalcon\DI\FactoryDefault::getDefault();
        $this->urlManager = $di['urlManager'];
        $this->logger = $di['logger'];
    }
        
    public function init()
    {        
        $msg = $this->getStatisticsContentMail();
        $marks = array("%%NAME%%", "%%LASTNAME%%", "%%MAILNAME%%", "%%MAILDATE%%", "%%URL_COMPLETE", "%%URL_BASIC%%", "%TOTALEMAILS%");
        
        foreach ($this->findMails() as $mail) {
            $links = $this->getStatisticsLinks($mail);
            
            foreach ($this->findUsers($mail->idAccount) as $user) {
                $replace = array($user->firstName, $user->lastName, $mail->subject, date('Y-m-d H:i' , $mail->finishedon), $links[1], $links[0], $mail->messagesSent);
                $message = $this->replaceContentStatictsMail($msg->msg, $marks, $replace);
                
                $subject = "Envío automático de estadísticas";
                $from = array('contenidos@sigmamovil.com' => 'Equipo de Contenidos');
                $userEmail = array($user->email => $user->firstName . " " . $user->lastName);
                
                $sender = new AdministrativeMessages();
                $sender->sendBasicMessage($subject, $from, $message, $userEmail, $msg->text);
                
                $mail->statisticsStatus = 1;
                
                if($mail->save()){
                    $this->traceSuccess("Edit mail: {$mail->idMail}");
                }
                else{
                    $this->traceSuccess("Fail Edit mail: {$mail->idMail}");
                }
                
                echo "Correo envíado y tabla actualizada -";
            }
        } 
    }    
    
    public function findMails() {
        $d = strtotime(date("d-m-Y h:m:s"));
        $towdays = strtotime("-2 days", $d);
        
        $mails = Mail::find(array(
            'conditions' => 'status = ?1 AND statisticsStatus = ?2 AND finishedon <= ?3',
            'bind' => array(1 => 'Sent',
                            2 => 0,
                            3 => $towdays,)
        ));       
        
        return $mails;
    }
    
    public function findUsers($idAccount) {
        $users = User::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $idAccount)
        ));    
        
        return $users;
    }
    
    public function getStatisticsContentMail() 
    {
        $msg = Adminmsg::findFirst(array(
                'conditions' => 'type = ?1',
                'bind' => array(1 => 'StatisticsInfo')
        ));

        return $msg;
    }

    public function replaceContentStatictsMail($subject, $search, $replace) 
    {
        return str_replace($search, $replace, $subject);
    }
    
    public function getStatisticsLinks($mail)
    {
        $linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
        $linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));

        $parameters = array(1, $mail->idMail, 'summary');
        $urlSummary = $linkdecoder->encodeLink('share/results', $parameters);

        $parameters2 = array(1, $mail->idMail, 'complete');
        $urlComplete = $linkdecoder->encodeLink('share/results', $parameters2);

        $url = array($urlSummary, $urlComplete);
        
        return $url;
    }
}