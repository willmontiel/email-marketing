<?php

class SocketConstants
{
	const PUB2CHILDREN_ENDPOINT = 'ipc:///tmp/pub2children.sock';
//	const MAILREQUESTS_ENDPOINT = 'tcp://*:9999';
//	const MAILREQUESTS_ENDPOINT_PEER = 'tcp://localhost:9999';
	const MAILREQUESTS_ENDPOINT = 'ipc:///tmp/requests.sock';
	const MAILREQUESTS_ENDPOINT_PEER = 'ipc:///tmp/requests.sock';
	const PULLFROMCHILD_ENDPOINT = 'ipc:///tmp/pullfromchildren.sock';
}
