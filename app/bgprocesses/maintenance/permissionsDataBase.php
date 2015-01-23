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
			'ROLE_ADMIN_DB' => 9,
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
			'pdf' => 23,
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
			'contact::export' => 9,
			'contact::on any import' => 10,
			
			'contactlist::create' => 11,
			'contactlist::read' => 12,
			'contactlist::update' => 13,
			'contactlist::delete' => 14,
			'contactlist::full delete' => 15,
			
			'dbase::create' => 16,
			'dbase::read' => 17,
			'dbase::update' => 18,
			'dbase::delete' => 19,
			
			'account::create' => 20,
			'account::read' => 21,
			'account::update' => 22,
			'account::delete' => 23,
			'account::login how any user' => 24,
			'account::billing' => 25,
			
			'customfield::create' => 26,
			'customfield::read' => 27,
			'customfield::update' => 28,
			'customfield::delete' => 29,
			
			'user::create' => 30,
			'user::read' => 31,
			'user::update' => 32,
			'user::delete' => 33,
			'user::login how any user' => 34,
			
			'segment::create' => 35,
			'segment::read' => 36,
			'segment::update' => 37,
			'segment::delete' => 38,
			
			'blockemail::read' => 39,
			'blockemail::block email' => 40,
			'blockemail::unblock email' => 41,
			
			'process::read' => 42,
			'process::download' => 43,
			
			'dashboard::read' => 44,
			
			'mail::create' => 45,
			'mail::read' => 46,
			'mail::update' => 47,
			'mail::delete' => 48,
			'mail::send' => 49,
			'mail::clone' => 50,
			'mail::manage' => 51,
			'mail::on any mail' => 52,
			
			'template::create' => 53,
			'template::read' => 54,
			'template::update' => 55,
			'template::delete' => 56,
			'template::on any template' => 57,
			
			'statistic::read' => 58,
			'statistic::download' => 59,
			'statistic::share' => 60,
			
			'flashmessage::create' => 61,
			'flashmessage::read' => 62,
			'flashmessage::update' => 63,
			'flashmessage::delete' => 64,
			
			'form::create' => 65,
			'form::read' => 66,
			'form::update' => 67,
			'form::delete' => 68,
			
			'socialmedia::create' => 69,
			'socialmedia::read' => 70,
			'socialmedia::delete' => 71,
			
			'system::read' => 72,
			'system::update' => 73,
			
			'tools::read' => 74,
			
			'footer::create' => 75,
			'footer::read' => 76,
			'footer::update' => 77,
			'footer::delete' => 78,
			'footer::view' => 79,
			
			'apikey::create' => 80,
			'apikey::read' => 81,
			'apikey::update' => 82,
			'apikey::delete' => 83,
			
			'api::billing' => 84,
			'api::account' => 85,
			
			'campaign::create' => 86,
			'campaign::read' => 87,
			'campaign::update' => 88,
			'campaign::delete' => 89,
			
			'pdf::create' => 90,
			'pdf::read' => 91,
			'pdf::update' => 92,
			'pdf::delete' => 93,
			'pdf::send' => 94,
			'pdf::loadtemplate' => 95,
			'pdf::readtemplate' => 96,
			'pdf::deletetemplate' => 97,
			'pdf::edittemplate' => 98,
			'pdf::createbatch' => 99,
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
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'contact::export'),
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
			
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::create'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::read'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::update'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::delete'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::send'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::loadtemplate'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::readtemplate'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::deletetemplate'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::edittemplate'),
			array( 'Role' => 'ROLE_SUDO', 'Action' => 'pdf::createbatch'),
			
			
//			----------//----------**ROLE_ADMIN**----------//----------
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::import'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'contact::export'),
			
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
			
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'pdf::create'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'pdf::read'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'pdf::update'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'pdf::delete'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'pdf::send'),
			array( 'Role' => 'ROLE_ADMIN', 'Action' => 'pdf::createbatch'),
			
//			----------//----------**ROLE_ADMIN_DB**----------//----------
			
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::create'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::read'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::update'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::delete'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::(un)subscribe'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::importbatch'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::import'),
			array( 'Role' => 'ROLE_ADMIN_DB', 'Action' => 'contact::export'),
			
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
			array( 'Role' => 'ROLE_TRAINING', 'Action' => 'contact::export'),
			
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
			array( 'Role' => 'ROLE_USER', 'Action' => 'contact::export'),
			
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
			array( 'Role' => 'ROLE_STATISTICS', 'Action' => 'statistic::download'),
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
			array( 'Role' => 'ROLE_WEB_SERVICES', 'Action' => 'contact::export'),
			
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
			array( 'Role' => 'ROLE_TEMPLATE', 'Action' => 'mail::create'),
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
