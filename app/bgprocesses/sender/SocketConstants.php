<?php

class SocketConstants
{
	const PUB2CHILDREN_ENDPOINT = 'ipc:///tmp/pub2children.sock';
//	const MAILREQUESTS_ENDPOINT = 'tcp://*:9999';
//	const MAILREQUESTS_ENDPOINT_PEER = 'tcp://localhost:9999';
	const MAILREQUESTS_ENDPOINT = 'ipc:///tmp/requests.sock';
	const MAILREQUESTS_ENDPOINT_PEER = 'ipc:///tmp/requests.sock';
	const PULLFROMCHILD_ENDPOINT = 'ipc:///tmp/pullfromchildren.sock';
		
	public static function getPub2ChildrenEndPoint()
	{
		return 'ipc:///tmp/' . Phalcon\DI::getDefault()->get('sockets')->tochildren . '.sock';
	}
	
	public static function getMailRequestsEndPoint()
	{
		return 'ipc:///tmp/' . Phalcon\DI::getDefault()->get('sockets')->request . '.sock';
	}
	
	public static function getMailRequestsEndPointPeer()
	{
		return 'ipc:///tmp/' . Phalcon\DI::getDefault()->get('sockets')->request . '.sock';
	}
	
	public static function getPullFromChildEndPoint()
	{
		return 'ipc:///tmp/' . Phalcon\DI::getDefault()->get('sockets')->fromchild . '.sock';
	}
}
