<?php

class SocketConstants
{
//	const PUB2CHILDREN_ENDPOINT = 'ipc:///tmp/pub2children.sock';
//	const MAILREQUESTS_ENDPOINT = 'ipc:///tmp/requests.sock';
//	const MAILREQUESTS_ENDPOINT_PEER = 'ipc:///tmp/requests.sock';
//	const PULLFROMCHILD_ENDPOINT = 'ipc:///tmp/pullfromchildren.sock';
		
	public static function getPub2ChildrenEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->tochildren;
	}
	
	public static function getMailRequestsEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->request;
	}
	
	public static function getMailRequestsEndPointPeer()
	{
		return Phalcon\DI::getDefault()->get('sockets')->request;
	}
	
	public static function getPullFromChildEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->fromchild;
	}
	
	public static function getImportProcessEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->import;
	}
}
