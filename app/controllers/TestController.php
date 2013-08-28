<?php

class TestController extends ControllerBase
{
	public $result;
	
	public function indexAction()
	{
		
	}

	public function testcountersAction()
	{
		$email = "hola@holasrc.com";
		$idAccount = 3;
		$idDbase = 1156;
		$idList = 1;
		
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIdList($idList);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		$contact = $this->createContactObj($email, "", 1);
		
		$contactC = $wrapper->createNewContactFromJsonData($contact);
		
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		$this->result[0] = array(
			"Ctotal" => $db->Ctotal,
			"Cactive" => $db->Cactive,
			"Cunsubscribed" => $db->Cunsubscribed,
			"Cbounced" => $db->Cbounced,
			"Cspam" => $db->Cspam
		);
		
		$this->view->setVar("resutlados", $this->result[0]);	
	}
	
	protected function createContactObj($email, $isactive = "", $issubscribed = "", $isbounced = "", $isspam = "")
	{
		$obj = array();
		$obj['email'] = $email;
		$obj['name'] = "";
		$obj['last_name'] = "";
		$obj['status'] = "";
		$obj['activated_on'] = "";
		$obj['bounced_on'] = "";
		$obj['subscribed_on'] = "";
		$obj['unsubscribed_on'] = "";
		$obj['spam_on'] = "";
		$obj['ip_active'] = "";
		$obj['ip_subscribed'] = "";
		$obj['updated_on'] = "";
		$obj['created_on'] = "";
		$obj['is_bounced'] = $isbounced;
		$obj['is_subscribed'] = $issubscribed;
		$obj['is_spam'] = $isspam;
		$obj['is_active'] = $isactive;
	}
}