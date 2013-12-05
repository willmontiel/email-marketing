<?php

	$context = new ZMQContext();
		
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	
	$requester->send(sprintf("%s 116", 'New-Task'));
	$request = $requester->recv();
	
//	sleep(5);
//	
//	$requester->send(sprintf("%s 73", 'Cancel-Process'));
//	$request = $requester->recv();
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
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 70", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 72", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 73", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 74", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 76", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 77", 'New-Task'));
	$request = $requester->recv();
