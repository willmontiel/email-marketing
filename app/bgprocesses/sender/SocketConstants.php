<?php

class SocketConstants
{
// ************** - Mail Sockets - **************
	
	public static function getMailPub2ChildrenEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->mailtochildren;
	}
	
	public static function getMailRequestsEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->mailrequest;
	}
	
	public static function getMailRequestsEndPointPeer()
	{
		return Phalcon\DI::getDefault()->get('sockets')->mailrequest;
	}
	
	public static function getMailPullFromChildEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->mailfromchild;
	}
	
// ************** - Import Sockets - **************
	
	public static function getImportPub2ChildrenEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->importtochildren;
	}
	
	public static function getImportRequestsEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->importrequest;
	}
	
	public static function getImportRequestsEndPointPeer()
	{
		return Phalcon\DI::getDefault()->get('sockets')->importrequest;
	}
	
	public static function getImportPullFromChildEndPoint()
	{
		return Phalcon\DI::getDefault()->get('sockets')->importfromchild;
	}
}
