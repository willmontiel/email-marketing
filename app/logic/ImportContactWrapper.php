<?php

class ImportContactWrapper extends BaseWrapper
{
	protected $idContactlist;
	protected $newcontact;


	public function setIdContactlist($idContactlist) {
		$this->idContactlist = $idContactlist;
	}
	
	public function startImport($fields, $destiny, $delimiter) {
		
		$open = fopen($destiny, "r");
		
		$line = trim(fgets($open));
		$linew = explode($delimiter, $line);
		
		$posCol = $this->SettingPositions($fields, $linew);
		
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		$customfields = Customfield::findByIdDbase($list->idDbase);
				
		$cantTrans = 0;
		$wrapper = new ContactWrapper();
		while(! feof($open)) {
			if($cantTrans == 0){
				$wrapper->startTransaction();
			}
			$wrapper->setAccount($this->account);
			$wrapper->setIdDbase($list->idDbase);
			$wrapper->setIdContactlist($this->idContactlist);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		

			$this->newcontact = new stdClass();
			
			$this->MappingToJSON($linew, $posCol, $customfields);

			try {
				$contact = $wrapper->addExistingContactToListFromDbase($this->newcontact->email);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($this->newcontact);
				}
			}
			catch (\InvalidArgumentException $e) {
//				$wrapper->rollbackTransaction();
			}
			catch (\Exception $e) {
				echo $e;
//				$wrapper->rollbackTransaction();
			}			 
			
			$line = trim(fgets($open));
			$linew = explode($delimiter, $line);
			
			$cantTrans++;
			
			if($cantTrans == 100){
				$wrapper->endTransaction();
				$cantTrans = 0;
			}
		}

		$wrapper->endTransaction(false);
	}
	
	protected function SettingPositions($fields, $linew) {
		
		for($i=0; $i< count($fields); $i++) {
			for($j=0; $j< count($linew); $j++) {
				if($fields[$i] == $linew[$j]) {
					$posCol[$i] = $j;
				}
			}
		}
		return $posCol;
	}

	protected function MappingToJSON($linew, $posCol, $customfields){
		
		$this->newcontact->email = $linew[$posCol[0]];
		$this->newcontact->name = $linew[$posCol[1]];
		$this->newcontact->last_name = $linew[$posCol[2]];
		$this->newcontact->status = "";
		$this->newcontact->activated_on = "";
		$this->newcontact->bounced_on = "";
		$this->newcontact->subscribed_on = "";
		$this->newcontact->unsubscribed_on = "";
		$this->newcontact->spam_on = "";
		$this->newcontact->ip_active = "";
		$this->newcontact->ip_subscribed = "";
		$this->newcontact->updated_on = "";
		$this->newcontact->created_on = "";
		$this->newcontact->is_bounced = "";
		$this->newcontact->is_subscribed = 1;
		$this->newcontact->is_spam = "";
		$this->newcontact->is_active = 1;
		
		$numfield = 3;
		foreach ($customfields as $field) {
			$namefield= strtolower($field->name);
			$this->newcontact->$namefield = $linew[$posCol[$numfield]];
			$numfield++;
		}
	}
}
