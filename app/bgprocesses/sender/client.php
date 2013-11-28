<?php

	$context = new ZMQContext();
		
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	
	$requester->send(sprintf("%s 'FirstWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'SecondWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'ThirdWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'FourthWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(rand(0, 5));
	
	$requester->send(sprintf("%s 'FifthWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(1);
	
	$requester->send(sprintf("%s 'SixthWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(1);
	
	$requester->send(sprintf("%s 'SeventhWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(1);
	
	$requester->send(sprintf("%s 'EighthWork'", 'NewTask'));
	$request = $requester->recv();
	
	sleep(1);
	
	$requester->send(sprintf("%s 'NinthWork'", 'NewTask'));
	$request = $requester->recv();



