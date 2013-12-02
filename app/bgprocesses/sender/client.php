<?php

	$context = new ZMQContext();
		
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	
	$requester->send(sprintf("%s 60", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 60", 'Cancel-Process'));
	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 60", 'Stop-Process'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s", 'Scheduled-Task'));
//	$request = $requester->recv();
	
//	$requester->send(sprintf("%s 61", 'New-Task'));
//	$request = $requester->recv();
//	
//	sleep(rand(0, 5));
//	
//	$requester->send(sprintf("%s 62", 'New-Task'));
//	$request = $requester->recv();