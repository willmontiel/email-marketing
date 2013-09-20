<?php
/*
*  Hello World server
*  Binds REP socket to tcp://*:5555
*  Expects "Hello" from client, replies with "World"
* @author Ian Barber <ian(dot)barber(at)gmail(dot)com>
*/

require_once '../bootstrap/phbootstrap.php';

define("REQUEST_TIMEOUT", 2500);
$context = new ZMQContext(1);

//  Socket to talk to clients
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind("tcp://*:5556");

while (true) {
	$request = $responder->recv();
	
	printf ("I'm on it \n");
		
	$arrayDecode = json_decode($request);
	
	$idContactlist = $arrayDecode->idContactlist;
	$idImportfile = $arrayDecode->idImportfile;
	$fields = $arrayDecode->fields;
	$destiny = $arrayDecode->destiny;
	$delimiter = $arrayDecode->delimiter;
	$header = $arrayDecode->header;
	$idAccount = $arrayDecode->idAccount;
	$ipaddress = $arrayDecode->ipaddress;
	
	$importwrapper = new ImportContactWrapper();
	$account = Account::findFirstByIdAccount($idAccount);
	
	$importwrapper->setIdFile($idImportfile);
	$importwrapper->setIdContactlist($idContactlist);
	$importwrapper->setAccount($account);
	$importwrapper->setIpaddress($ipaddress);

	try {
		$count = $importwrapper->startImport($fields, $destiny, $delimiter, $header);
	}
	catch (\InvalidArgumentException $e) {
		Phalcon\DI::getDefault()->get('logger')->log($e);
	}
	catch (\Exception $e) {
		Phalcon\DI::getDefault()->get('logger')->log($e);
	}
	printf ("I'm done \n");
	$responder->send('');
}