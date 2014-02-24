<?php
require_once '../bootstrap/phbootstrap.php';

define("REQUEST_TIMEOUT", 2500);
$context = new ZMQContext(1);

//  Socket to talk to clients
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind(SocketConstants::getImportServerProcessEndPoint());

$timer = Phalcon\DI::getDefault()->get('timerObject');
$log   = Phalcon\DI::getDefault()->get('logger');

while (true) {
	$timer->startTimer('waiting', 'Waiting for request!');
	$request = $responder->recv();
	$timer->endTimer('waiting');
	$timer->startTimer('replying', 'Replying to request!');
	$responder->send('');
	$timer->endTimer('replying');
	
	$timer->startTimer('processing', 'Processing request');
	printf ("I'm On It \n");
		
	$arrayDecode = json_decode($request);
	
	$idContactlist = $arrayDecode->idContactlist;
	$idImportproccess = $arrayDecode->idImportproccess;
	$fields = $arrayDecode->fields;
	$destiny = $arrayDecode->destiny;
	$delimiter = $arrayDecode->delimiter;
	$header = $arrayDecode->header;
	$idAccount = $arrayDecode->idAccount;
	$ipaddress = $arrayDecode->ipaddress;
	
	$importwrapper = new ImportContactWrapper();
	$account = Account::findFirstByIdAccount($idAccount);
	
	$importwrapper->setIdProccess($idImportproccess);
	$importwrapper->setIdContactlist($idContactlist);
	$importwrapper->setAccount($account);
	$importwrapper->setIpaddress($ipaddress);

	try {
		$importwrapper->startImport($fields, $destiny, $delimiter, $header);
	}
	catch (\InvalidArgumentException $e) {
		$log->log($e);
	}
	catch (\Exception $e) {
		$log->log($e);
	}
	printf ("I'm Done \n");
	$timer->endTimer('processing');

	print_timer($timer, $log);
	print_dbase_profile();
}


function print_timer(TimerObject $timer, $log)
{
	$log->log($timer);
}
