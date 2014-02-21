<?php
	require_once '../bootstrap/phbootstrap.php';
	
	$context = new ZMQContext();
		
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect(SocketConstants::getMailRequestsEndPoint());
	
//	$requester->send(sprintf("%s", 'Show-Status'));
//	$request = $requester->recv();
//	printf($request);

	$requester->send(sprintf("%s 40", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 41", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 42", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 43", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 44", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 47", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 55", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 56", 'Play-Task'));
		$request = $requester->recv();
	$requester->send(sprintf("%s 58", 'Play-Task'));
		$request = $requester->recv();
	
//	$requester->send(sprintf("%s 2", 'New-Task'));
//	$request = $requester->recv();
//	
//	$requester->send(sprintf("%s 3", 'New-Task'));
//	$request = $requester->recv();
//	
//	$requester->send(sprintf("%s 4", 'New-Task'));
//	$request = $requester->recv();
//	
//	$requester->send(sprintf("%s 5", 'New-Task'));
//	$request = $requester->recv();
//	sleep(1);
//	$requester->send(sprintf("%s 4820", 'Checking-Work'));
//	$request = $requester->recv();
//	sscanf($request, '%s %s', $header, $content);
//	printf('Se realizo un ' . $header . ' y trajo ' . $content .PHP_EOL);
//	sleep(1);
//	$requester->send(sprintf("%s 4820", 'Checking-Work'));
//	$request = $requester->recv();
//	sscanf($request, '%s %s', $header, $content);
//	printf('Se realizo un ' . $header . ' y trajo ' . $content .PHP_EOL);
//	sleep(1);
//	$requester->send(sprintf("%s 4820", 'Checking-Work'));
//	$request = $requester->recv();
//	sscanf($request, '%s %s', $header, $content);
//	printf('Se realizo un ' . $header . ' y trajo ' . $content .PHP_EOL);
//	sleep(1);
//	$requester->send(sprintf("%s 4820", 'Checking-Work'));
//	$request = $requester->recv();
//	sscanf($request, '%s %s', $header, $content);
//	printf('Se realizo un ' . $header . ' y trajo ' . $content .PHP_EOL);
//	sleep(1);
//	$requester->send(sprintf("%s 4820", 'Checking-Work'));
//	$request = $requester->recv();
//	sscanf($request, '%s %s', $header, $content);
//	printf('Se realizo un ' . $header . ' y trajo ' . $content .PHP_EOL);
	
//	
//	sleep(2);
//	
//	$requester->send(sprintf("%s 3", 'Cancel-Process'));
//	$request = $requester->recv();
	

//	sleep(2);
//	
//	$requester->send(sprintf("%s 3", 'Stop-Process'));
//	$request = $requester->recv();

//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s", 'Scheduled-Task'));
//	$request = $requester->recv();
	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 70", 'New-Task'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 72", 'New-Task'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 73", 'New-Task'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 74", 'New-Task'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 76", 'New-Task'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 77", 'New-Task'));
//	$request = $requester->recv();
