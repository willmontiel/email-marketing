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
        $marks = array("%%NAME%%", "%%LASTNAME%%");
        
        foreach ($this->findMails() as $mail) {
            $links = $this->getStatisticsLinks($mail);
            print_r($links);
            
            foreach ($this->findUsers($mail->idAccount) as $user) {
                $replace = array($user->firstName, $user->lastName);
                //$message = $this->replaceContentStatictsMail($msg, $marks, $replace);
                
                //echo $message;
                
                //$sender = new AdministrativeMessages();
                //$sender->sendMessage()
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