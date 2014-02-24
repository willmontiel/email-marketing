<?php

//$contact = new Contact;

$context = new ZMQContext();
if ($argc >= 1) {
	$mensaje = $argv[1];
}
//  Socket to talk to server
echo "Connecting to hello world server\n";
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect(SocketConstants::getImportClientProcessEndPoint());

for ($request_nbr = 0; $request_nbr != 10; $request_nbr++) {
    printf ("Sending request %d...\n", $request_nbr);
    $requester->send($mensaje);

    $reply = $requester->recv();
    printf ("Received reply %d: [%s]\n", $request_nbr, $reply);
}