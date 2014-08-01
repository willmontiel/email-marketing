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
			'ROLE_STATISTICS' => 4,
			'ROLE_WEB_SERVICES' => 5,
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
			'footer' => 19,
			'apikey' => 20,
			'api' => 21,
			'campaign' => 22,
		);
	}
	
	public function loadActions()
	{
		$this->action = array(
			'contact::create' => 1,
			'contact::read' => 2,
			'contact::update' => 3,
			'contact::delete' => 4,
			'contact::full delete' => 5,
			'contact::(un)subscribe' => 6,
			'contact::importbatch' => 7,
			'contact::import' => 8,
			'contact::on any import' => 9,
			
			'contactlist::create' => 10,
			'contactlist::read' => 11,
			'contactlist::update' => 12,
			'contactlist::delete' => 13,
			
			'dbase::create' => 14,
			'dbase::read' => 15,
			'dbase::update' => 16,
			'dbase::delete' => 17,
			
			'account::create' => 18,
			'account::read' => 19,
			'account::update' => 20,
			'account::delete' => 21,
			'account::login how any user' => 22,
			'account::billing' => 23,
			
			'customfield::create' => 24,
			'customfield::read' => 25,
			'customfield::update' => 26,
			'customfield::delete' => 27,
			
			'user::create' => 28,
			'user::read' => 29,
			'user::update' => 30,
			'user::delete' => 31,
			'user::login how any user' => 32,
			
			'segment::create' => 33,
			'segment::read' => 34,
			'segment::update' => 35,
			'segment::delete' => 36,
			
			'blockemail::read' => 37,
			'blockemail::block email' => 38,
			'blockemail::unblock email' => 39,
			
			'process::read' => 40,
			'process::download' => 41,
			
			'dashboard::read' => 42,
			
			'mail::create' => 43,
			'mail::read' => 44,
			'mail::update' => 45,
			'mail::delete' => 46,
			'mail::send' => 47,
			'mail::clone' => 48,
			'mail::manage' => 49,
			'mail::on any mail' => 50,
			
			'template::create' => 51,
			'template::read' => 52,
			'template::update' => 53,
			'template::delete' => 54,
			'template::on any template' => 55,
			
			'statistic::read' => 56,
			'statistic::download' => 57,
			'statistic::share' => 58,
			
			'flashmessage::create' => 59,
			'flashmessage::read' => 60,
			'flashmessage::update' => 61,
			'flashmessage::delete' => 62,
			
			'form::create' => 63,
			'form::read' => 64,
			'form::update' => 65,
			'form::delete' => 66,
			
			'socialmedia::create' => 67,
			'socialmedia::read' => 68,
			'socialmedia::delete' => 69,
			
			'system::read' => 70,
			'system::update' => 71,
			
			'tools::read' => 72,
			
			'footer::create' => 73,
			'footer::read' => 74,
			'footer::update' => 75,
			'footer::delete' => 76,
			'footer::view' => 77,
			
			'apikey::create' => 78,
			'apikey::read' => 79,
			'apikey::update' => 80,
			'apikey::delete' => 81,
			
			'api::billing' => 82,
			'api::account' => 83,
			
			'campaign::create' => 84,
			'campaign::read' => 85,
			'campaign::update' => 86,
			'campaign::delete' => 87,
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
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::full delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::import'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::on any import'),
			
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
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'mail::on any mail'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'template::on any template'),
			
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
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'footer::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'footer::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'footer::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'footer::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'footer::view'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'apikey::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'apikey::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'apikey::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'apikey::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'system::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'system::update'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'tools::read'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'api::billing'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'api::account'),
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'campaign::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'campaign::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'campaign::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'campaign::delete'),
			
			
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
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'footer::view'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'apikey::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'apikey::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'apikey::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'apikey::delete'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'api::account'),
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'campaign::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'campaign::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'campaign::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'campaign::delete'),
			
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
			
			array( 'Role' => 'ROLE_USER', 'Action' => 'footer::view'),
			
//			----------//----------**ROLE_STATISTICS**----------//----------
			
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'dashboard::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'mail::read'),
			
			
//			----------//----------**ROLE_WEB_SERVICES**----------//----------
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::full delete'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::import'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contactlist::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contactlist::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contactlist::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'dbase::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'dbase::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'dbase::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'customfield::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'customfield::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'customfield::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'customfield::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'user::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'user::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'user::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'user::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'process::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'process::download'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'mail::clone'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'statistic::download'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'statistic::share'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'form::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'tools::read'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'footer::view'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'apikey::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'apikey::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'apikey::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'apikey::delete'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'api::account'),
			
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'campaign::create'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'campaign::read'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'campaign::update'),
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'campaign::delete'),
			
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
