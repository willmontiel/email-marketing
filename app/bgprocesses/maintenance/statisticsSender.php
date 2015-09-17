<?php
require_once '../bootstrap/phbootstrap.php';

$sSender = new StatisticsSender();
$sSender->init();

class StatisticsSender
{
    public function init()
    {        
        $d = strtotime(date("d-m-Y h:m:s"));        
        $towdays = strtotime("-2 days", $d);
        
        echo $d;
        echo $towdays;
        
        $mails = Mail::find(array(
            'conditions' => 'status = ?1 AND statisticsStatus = ?2 AND finishedon < ?3 AND finishedon > ?4',
            'bind' => array(1 => 'Sent',
                            2 => 0,
                            3 => $d,
                            4 => $towdays)
        ));        
        
        $dates = array();
        
        foreach ($mails as $mail) {
            $dates[] = $mail->finishedon;
            echo $dates;
        }
        
        
    }    
}