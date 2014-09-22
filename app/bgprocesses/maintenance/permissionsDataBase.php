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
			'ROLE_MAIL_SERVICES' => 6,
			'ROLE_TRAINING' => 7,
			'ROLE_TEMPLATE' => 8,
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
			'contactlist::full delete' => 14,
			
			'dbase::create' => 15,
			'dbase::read' => 16,
			'dbase::update' => 17,
			'dbase::delete' => 18,
			
			'account::create' => 19,
			'account::read' => 20,
			'account::update' => 21,
			'account::delete' => 22,
			'account::login how any user' => 23,
			'account::billing' => 24,
			
			'customfield::create' => 25,
			'customfield::read' => 26,
			'customfield::update' => 27,
			'customfield::delete' => 28,
			
			'user::create' => 29,
			'user::read' => 30,
			'user::update' => 31,
			'user::delete' => 32,
			'user::login how any user' => 33,
			
			'segment::create' => 34,
			'segment::read' => 35,
			'segment::update' => 36,
			'segment::delete' => 37,
			
			'blockemail::read' => 38,
			'blockemail::block email' => 39,
			'blockemail::unblock email' => 40,
			
			'process::read' => 41,
			'process::download' => 42,
			
			'dashboard::read' => 43,
			
			'mail::create' => 44,
			'mail::read' => 45,
			'mail::update' => 46,
			'mail::delete' => 47,
			'mail::send' => 48,
			'mail::clone' => 49,
			'mail::manage' => 50,
			'mail::on any mail' => 51,
			
			'template::create' => 52,
			'template::read' => 53,
			'template::update' => 54,
			'template::delete' => 55,
			'template::on any template' => 56,
			
			'statistic::read' => 57,
			'statistic::download' => 58,
			'statistic::share' => 59,
			
			'flashmessage::create' => 60,
			'flashmessage::read' => 61,
			'flashmessage::update' => 62,
			'flashmessage::delete' => 63,
			
			'form::create' => 64,
			'form::read' => 65,
			'form::update' => 66,
			'form::delete' => 67,
			
			'socialmedia::create' => 68,
			'socialmedia::read' => 69,
			'socialmedia::delete' => 70,
			
			'system::read' => 71,
			'system::update' => 72,
			
			'tools::read' => 73,
			
			'footer::create' => 74,
			'footer::read' => 75,
			'footer::update' => 76,
			'footer::delete' => 77,
			'footer::view' => 78,
			
			'apikey::create' => 79,
			'apikey::read' => 80,
			'apikey::update' => 81,
			'apikey::delete' => 82,
			
			'api::billing' => 83,
			'api::account' => 84,
			
			'campaign::create' => 85,
			'campaign::read' => 86,
			'campaign::update' => 87,
			'campaign::delete' => 88,
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
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contactlist::full delete'),
			
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
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'dbase::update'),
			
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
			
			
//			----------//----------**ROLE_ADMIN_DB**----------//----------
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::import'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contactlist::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contactlist::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contactlist::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'dbase::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'dbase::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'dbase::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'customfield::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'customfield::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'customfield::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'customfield::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'user::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'user::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'user::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'user::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'process::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'process::download'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'mail::clone'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'statistic::download'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'statistic::share'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'form::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'tools::read'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'footer::view'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'apikey::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'apikey::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'apikey::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'apikey::delete'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'api::account'),
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'campaign::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'campaign::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'campaign::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'campaign::delete'),			
			
			
//			----------//----------**ROLE_TRAINING**----------//----------
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::import'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contactlist::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contactlist::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contactlist::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'dbase::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'dbase::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'dbase::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'customfield::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'customfield::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'customfield::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'customfield::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'user::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'user::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'user::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'user::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'process::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'process::download'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'mail::clone'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'statistic::download'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'statistic::share'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'form::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'socialmedia::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'socialmedia::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'socialmedia::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'tools::read'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'footer::view'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'apikey::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'apikey::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'apikey::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'apikey::delete'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'api::account'),
			
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'campaign::create'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'campaign::read'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'campaign::update'),
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'campaign::delete'),
			
			
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
			
			
			
			//----------//----------**ROLE_MAIL_SERVICES**----------//----------
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'segment::create'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'segment::read'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'segment::update'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'segment::delete'),
			
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'blockemail::read'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'blockemail::block email'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'blockemail::unblock email'),
			
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::create'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::update'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::delete'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::send'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::clone'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::manage'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'mail::on any mail'),
			
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'statistic::read'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'statistic::download'),
			array( 'Role' => 'ROLE_MAIL_SERVICES', 'Action' => 'statistic::share'),
			
			
			//----------//----------**ROLE_TEMPLATE**----------//----------
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'dashboard::read'),
			
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'mail::read'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'template::create'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'template::read'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'template::update'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'template::delete'),
			
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'contactlist::read'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'dbase::read'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'form::create'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'form::read'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'form::update'),
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'form::delete'),
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
