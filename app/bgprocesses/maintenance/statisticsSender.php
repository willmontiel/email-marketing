<?php
require_once '../bootstrap/phbootstrap.php';

$sSender = new StatisticsSender();
$sSender->init();

class StatisticsSender
{
    public function init()
    {
        $mails = Mail::find(array(
            'conditions' => 'status = ?1 AND statisticsStatus = ?2',
            'bind' => array(1 => 'Sent',
                            2 => 0)
        ));
        
        $d = strtotime(date("Y-m-d"));        
        $towdays = strtotime("+2 days", $d);
        
        foreach ($mails as $mail) {
            echo $mail->fineshdon;
        }
    }    
}