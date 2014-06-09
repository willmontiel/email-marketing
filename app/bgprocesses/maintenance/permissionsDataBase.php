<?php
require_once '../bootstrap/phbootstrap.php';

$permissions = new PermissionsDataBase();
$permissions->executeSqls();

class PermissionsDataBase
{
	public function __construct() 
	{
		$this->loadRoles();
		$this->loadResources();
		$this->loadActions();
		$this->loadAllowed();
	}
	
	
	public function loadRoles()
	{
		$this->role = array(
			'ROLE_SUDO' => 1,
			'ROLE_ADMIN' => 2,
			'ROLE_USER' => 3,
			'ROLE_STATISTICS' => 4
		);
	}
	
	public function loadResources()
	{
		$this->resource = array(
			'contact' => 1,
			'contactlist' => 2,
			'dbase' => 3,
			'account' => 4,
			'customfield' => 5,
			'user' => 6,
			'segment' => 7,
			'blockemail' => 8,
			'process' => 9,
			'dashboard' => 10,
			'mail' => 11,
			'template' => 12,
			'statistic' => 13,
			'flashmessage' => 14,
			'form' => 15,
			'socialmedia' => 16,
			'system' => 17,
			'tools' => 18,
		);
	}
	
	public function loadActions()
	{
		$this->action = array(
			'contact::create' => 1,
			'contact::read' => 2,
			'contact::update' => 3,
			'contact::delete' => 4,
			'contact::(un)subscribe' => 5,
			'contact::importbatch' => 6,
			'contact::import' => 7,
			
			'contactlist::create' => 8,
			'contactlist::read' => 9,
			'contactlist::update' => 10,
			'contactlist::delete' => 11,
			
			'dbase::create' => 12,
			'dbase::read' => 13,
			'dbase::update' => 14,
			'dbase::delete' => 15,
			
			'account::create' => 16,
			'account::read' => 17,
			'account::update' => 18,
			'account::delete' => 19,
			'account::login how any user' => 20, 
			'account::billing' => 67, 
			
			'customfield::create' => 21,
			'customfield::read' => 22,
			'customfield::update' => 23,
			'customfield::delete' => 24,
			
			'user::create' => 25,
			'user::read' => 26,
			'user::update' => 27,
			'user::delete' => 28,
			'user::login how any user' => 29,
			
			'segment::create' => 30,
			'segment::read' => 31,
			'segment::update' => 32,
			'segment::delete' => 33,
			
			'blockemail::read' => 34,
			'blockemail::block email' => 35,
			'blockemail::unblock email' => 36,
			
			'process::read' => 37,
			'process::download' => 38,
			
			'dashboard::read' => 39,
			
			'mail::create' => 40,
			'mail::read' => 41,
			'mail::update' => 42,
			'mail::delete' => 43,
			'mail::send' => 44,
			'mail::clone' => 45,
			'mail::manage' => 46,
			
			'template::create' => 47,
			'template::read' => 48,
			'template::update' => 49,
			'template::delete' => 50,
			
			'statistic::read' => 51,
			'statistic::download' => 52,
			'statistic::share' => 67,
			
			'flashmessage::create' => 53,
			'flashmessage::read' => 54,
			'flashmessage::update' => 55,
			'flashmessage::delete' => 56,
			
			'form::create' => 57,
			'form::read' => 58,
			'form::update' => 59,
			'form::delete' => 60,
			
			'socialmedia::create' => 61,
			'socialmedia::read' => 62,
			'socialmedia::delete' => 63,
			
			'system::read' => 64,
			'system::update' => 65,
			
			'tools::read' => 66,
		);
	}
	
	public function loadAllowed()
	{
		$this->allowed = array(
//			----------//----------**ROLE_SUDO**----------//----------
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::import'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contactlist::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contactlist::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contactlist::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'dbase::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'dbase::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'dbase::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'account::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'account::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'account::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'account::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'account::billing'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'account::login how any user'),

			array( 'Role' => 'ROLE_SUDO', 'Action' => 'customfield::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'customfield::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'customfield::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'customfield::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'user::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'user::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'user::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'user::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'user::login how any user'),

			array( 'Role' => 'ROLE_SUDO', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'process::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'process::download'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::clone'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::manage'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'statistic::download'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'statistic::share'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'flashmessage::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'flashmessage::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'flashmessage::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'flashmessage::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'form::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'system::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'system::update'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'tools::read'),
			
			
//			----------//----------**ROLE_ADMIN**----------//----------
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::import'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contactlist::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contactlist::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contactlist::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dbase::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dbase::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dbase::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'customfield::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'customfield::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'customfield::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'customfield::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'user::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'user::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'user::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'user::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'process::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'process::download'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'mail::clone'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'statistic::download'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'statistic::share'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'form::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'tools::read'),
			
			
//			----------//----------**ROLE_USER**----------//----------
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::import'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'contactlist::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contactlist::update'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'contactlist::delete'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'mail::clone'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'statistic::download'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'form::delete'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_USER', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'tools::read'),
			
//			----------//----------**ROLE_STATISTICS**----------//----------
			
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'dashboard::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'mail::read'),
		);
		
	}
	
	public function executeSqls()
	{
		$sqlRoles = "INSERT IGNORE INTO role VALUES ";
		$first = true;
		foreach($this->role as $name => $id) {
			if(!$first) {
				$sqlRoles.=', ';
			}
			$sqlRoles.= "('{$id}', '{$name}')";
			$first = false;
		}

		$sqlResource = "INSERT IGNORE INTO resource VALUES ";
		$first = true;
		foreach($this->resource as $name => $id) {
			if(!$first) {
				$sqlResource.=', ';
			}
			$sqlResource.= "('{$id}', '{$name}')";
			$first = false;
		}
		
		$sqlAction = "INSERT IGNORE INTO action VALUES ";
		$first = true;
		foreach($this->action as $name => $id) {
			if(!$first) {
				$sqlAction.=', ';
			}
			$data = explode('::', $name); 
			$sqlAction.= "('{$id}', '{$this->resource[$data[0]]}', '{$data[1]}')";
			$first = false;
		}

		$sqlAllowed = "INSERT IGNORE INTO allowed VALUES ";
		$first = true;
		foreach($this->allowed as $key => $value) {
			if(!$first) {
				$sqlAllowed.=', ';
			}
			$id = $key + 1 ;
			$sqlAllowed.= "('{$id}', '{$this->role[$value['Role']]}', '{$this->action[$value['Action']]}')";
			$first = false;
		}
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$db->begin();
		
		$db->execute('SET foreign_key_checks = 0');
		
		$db->execute('TRUNCATE TABLE role');
		$db->execute('TRUNCATE TABLE resource');
		$db->execute('TRUNCATE TABLE action');
		$db->execute('TRUNCATE TABLE allowed');
		
		$execRole = $db->execute($sqlRoles);
		$execResource = $db->execute($sqlResource);
		$execAction = $db->execute($sqlAction);
		$execAllowed = $db->execute($sqlAllowed);
		
		$db->execute('SET foreign_key_checks = 1');
		
		if (!$execRole || !$execResource || !$execAction || !$execAllowed) {
			$db->rollback();
		}
		
		$db->commit();	
	}
}