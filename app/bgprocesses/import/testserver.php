<?php
/*
*  Hello World server
*  Binds REP socket to tcp://*:5555
*  Expects "Hello" from client, replies with "World"
* @author Ian Barber <ian(dot)barber(at)gmail(dot)com>
*/
define("REQUEST_TIMEOUT", 2500);
$context = new ZMQContext(1);

//  Socket to talk to clients
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind("tcp://*:5556");

while (true) {
    //  Wait for next request from client
    $request = $responder->recv();
	sleep(5);
	printf ("El pedido " . $request . " se ha procesado" );
    $responder->send('');
}