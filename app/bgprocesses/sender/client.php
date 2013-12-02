<?php

	$context = new ZMQContext();
		
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	
	$requester->send(sprintf("%s 'Now1'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now1'", 'Cancel-Process'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now1'", 'Stop-Process'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s", 'Scheduled-Task'));
	$request = $requester->recv();
	
	$requester->send(sprintf("%s 'Now5'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now65'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now72'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now45'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now85'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now36'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now12'", 'New-Task'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'Now89'", 'New-Task'));
	$request = $requester->recv();