<?php

	$context = new ZMQContext();
		
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	
//	$requester->send(sprintf("%s", 'Show-Status'));
//	$request = $requester->recv();
//	printf($request);
	
	$requester->send(sprintf("%s 3", 'New-Task'));
	$request = $requester->recv();
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
	
	
//	sleep(5);
//	
//	$requester->send(sprintf("%s 3", 'Cancel-Process'));
//	$request = $requester->recv();
	

//	sleep(3);
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
