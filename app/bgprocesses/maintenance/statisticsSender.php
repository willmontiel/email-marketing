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
        
        $mails = Mail::find(array(
            'conditions' => 'status = ?1 AND statisticsStatus = ?2 AND finishedon <= ?3',
            'bind' => array(1 => 'Sent',
                            2 => 0,
                            3 => $towdays,)
        ));        
        
        $accounts = array();
        
        foreach ($mails as $mail) {
            $accounts[] = $mail->idAccount;
        }
        
        $account = array_unique($accounts);
        
        print_r($accounts);
        print_r($account);
    }    
}