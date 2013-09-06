<?php

class ImportContactWrapper extends BaseWrapper
{
	protected $idContactlist;
	protected $newcontact;
	protected $idProccess;
	
	public function setIdProccess($idProccess) {
		$this->idProccess = $idProccess;
	}

	public function setIdContactlist($idContactlist) {
		$this->idContactlist = $idContactlist;
	}
	
	public function startImport($fields, $destiny, $delimiter) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$success = array();
		$errors = array();
		$total = 0;
		$exist = 0;
		$invalid = 0;
		$bloqued = 0;
		$limit = 0;
		$cantTrans = 0;
		
		$open = fopen($destiny, "r");
		
		$line = trim(fgets($open));
		$linew = explode($delimiter, $line);
		
		$posCol = $this->SettingPositions($fields, $linew);
		
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		$customfields = Customfield::findByIdDbase($list->idDbase);
		
		$wrapper = new ContactWrapper();
		
		$db->begin();
		
		$wrapper->startCounter();
		
		while(! feof($open)) {
			$wrapper->setAccount($this->account);
			$wrapper->setIdDbase($list->idDbase);
			$wrapper->setIdContactlist($this->idContactlist);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		

			$this->newcontact = new stdClass();
			
			$this->MappingToJSON($linew, $posCol, $customfields);
			
			array_push($success, $linew);
			
			try {
				$contact = $wrapper->addExistingContactToListFromDbase($this->newcontact->email);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($this->newcontact);
				}
			}
			catch (\InvalidArgumentException $e) {
				
				array_pop($success);
				
				switch ($e->getCode()) {
					case 0:
						array_push($linew, "Existente");
						$exist++;
						break;						
					case 1:
						array_push($linew, "Correo Invalido");
						$invalid++;
						break;
					case 2:
						array_push($linew, "Correo Bloqueado");
						$bloqued++;
						break;
					case 3:
						array_push($linew, "Limite de Contactos Excedido");
						$limit++;
						break;
				}
				
				array_push($errors, $linew);
				
			}
			catch (\Exception $e) {
				$db->rollback();
			}			 
			
			$total++;
			
			$line = trim(fgets($open));
			$linew = explode($delimiter, $line);
			
			$cantTrans++;
			
			if($cantTrans == 100){
				$db->commit();				
				$wrapper->endCounters();
				
				$db->begin();
				$wrapper->startCounter();
				
				$cantTrans = 0;
			}
		}

		$db->commit();				
		$wrapper->endCounters();
		
		$this->createReports($errors, $success);
		
		$count = array(
			"total" => $total,
			"import" => $total-($exist+$invalid+$bloqued+$limit),
			"Nimport" => $exist+$invalid+$bloqued+$limit,
			"exist" => $exist,
			"invalid" => $invalid,
			"bloqued" => $bloqued,
			"limit" => $limit				
		);
		
		return $count;
		
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
	
	protected function createReports($errors, $success)
	{
		$uniquecode = uniqid();
		
		$nameNimported = $this->account->idAccount."_".date("ymdHi",time())."_".$uniquecode."noneimported.csv";
		
		$saveFileError = new Importfile();
		$saveFileError->idAccount = $this->account->idAccount;
		$saveFileError->internalName = $nameNimported;
		$saveFileError->originalName = $nameNimported;
		$saveFileError->createdon = time();

		if (!$saveFileError->save()) {
				foreach ($saveFileError->getMessages() as $msg) {
						$this->flashSession->error($msg);
						$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
				}
		} else {
			$fp = fopen('../tmp/ifiles/' . $nameNimported, 'w');

			foreach ($errors as $error) {
				fputcsv($fp, $error);
			}

			fclose($fp);
		}
		
		$nameImported = $this->account->idAccount."_".date("ymdHi",time())."_".$uniquecode."imported.csv";
		
		$saveFileSuccess = new Importfile();
		$saveFileSuccess->idAccount = $this->account->idAccount;
		$saveFileSuccess->internalName = $nameImported;
		$saveFileSuccess->originalName = $nameImported;
		$saveFileSuccess->createdon = time();
		
		if (!$saveFileSuccess->save()) {
				foreach ($saveFileSuccess->getMessages() as $msg) {
						$this->flashSession->error($msg);
						$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
				}
		} else {
			$fp = fopen('../tmp/ifiles/' . $nameImported, 'w');		

			foreach ($success as $succ) {
				fputcsv($fp, $succ);
			}

			fclose($fp);
		}
		
		$proccess = Importproccess::findFirstByIdImportproccess($this->idProccess);
		
		$proccess->errorFile = $saveFileError->idImportfile;
		$proccess->successFile = $saveFileSuccess->idImportfile;
		
		if(!$proccess->save()) {
			throw new InvalidArgumentException("Error al crear el registro del proceso de importacion");
		}
		
	}
}
