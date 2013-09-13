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
		
		$success = array();
		$errors = array();
		$total = 0;
		$exist = 0;
		$invalid = 0;
		$bloqued = 0;
		$limit = 0;
		$cantTrans = 0;
		$posCol = $fields;
		
		$open = fopen($destiny, "r");
		
//		$linew = trim(fgetcsv($open, 0, $delimiter));
//		$line = trim(fgets($open));
//		$linew = explode($delimiter, $line);

//		$posCol = $this->SettingPositions($fields, $linew);
		
		if(empty($posCol)) {
			throw new \InvalidArgumentException('No hay Mapeo de los Campos y las Columnas del Archivo');
		}
		
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		$customfields = Customfield::findByIdDbase($list->idDbase);
		
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->account);
		$wrapper->setIdDbase($list->idDbase);
		$wrapper->setIdContactlist($this->idContactlist);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		

		$wrapper->startTransaction();
		
		while(! feof($open)) {
			
			$line = trim(fgets($open));
			$linew = explode($delimiter, $line);
//			$linew = trim(fgetcsv($open, 0, $delimiter));
			
			$this->newcontact = new stdClass();
			
			$this->MappingToJSON($linew, $posCol);
			$this->MappingCustomFieldsToJSON($linew, $posCol, $customfields);
			
			array_push($success, $linew);
			
			try {
				$contact = $wrapper->addExistingContactToListFromDbase($this->newcontact->email, false);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($this->newcontact, false);
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
				$wrapper->rollbackTransaction();
			}			 
			
			$total++;
			
//			$line = trim(fgets($open));
//			$linew = explode($delimiter, $line);
			
			$cantTrans++;
			
			if($cantTrans == 100){
				$wrapper->endTransaction();
				
				$cantTrans = 0;
			}
		}
		$wrapper->endTransaction(false);
		
		$this->createReports($errors, $success);
		
		$count = array(
			"total" => $total,
			"import" => $total-($exist+$invalid+$bloqued+$limit),
			"Nimport" => $exist+$invalid+$bloqued+$limit,
			"exist" => $exist,
			"invalid" => $invalid,
			"bloqued" => $bloqued,
			"limit" => $limit,
			"idProcces" => $this->idProccess
		);
		
		return $count;
		
	}
	
	protected function SettingPositions($fields, $linew) {
		
		$posCol = array();
		for($i=0; $i< count($fields); $i++) {
			for($j=0; $j< count($linew); $j++) {
				if($fields[$i] == $linew[$j]) {
					$posCol[$i] = $j;
				}
			}
		}
		return $posCol;
	}

	protected function MappingToJSON($linew, $posCol){
		
		$this->newcontact->email = (isset($posCol[0]))?$linew[$posCol[0]]:"";
		$this->newcontact->name = (isset($posCol[1]))?$linew[$posCol[1]]:"";
		$this->newcontact->lastName = (isset($posCol[2]))?$linew[$posCol[2]]:"";;
		$this->newcontact->status = "";
		$this->newcontact->activatedOn = "";
		$this->newcontact->bouncedOn = "";
		$this->newcontact->subscribedOn = "";
		$this->newcontact->unsubscribedOn = "";
		$this->newcontact->spamOn = "";
		$this->newcontact->ipActive = "";
		$this->newcontact->ipSubscribed = "";
		$this->newcontact->updatedOn = "";
		$this->newcontact->createdOn = "";
		$this->newcontact->isBounced = "";
		$this->newcontact->isSubscribed = 1;
		$this->newcontact->isSpam = "";
		$this->newcontact->isActive = 1;
	}
	
	protected function MappingCustomFieldsToJSON($linew, $posCol, $customfields)
	{
		$numfield = 3;
		foreach ($customfields as $field) {
			$namefield= "campo".$field->idCustomField;
			$this->newcontact->$namefield = (isset($posCol[$numfield]))?$linew[$posCol[$numfield]]:$field->defaultValue;
			if($field->required == "Si" && !$field->defaultValue){
				switch($field->type) {
					case "Text":
						$this->newcontact->$namefield = (isset($posCol[$numfield]))?$linew[$posCol[$numfield]]:" ";
						break;
					case "Numerical":
						if(isset($posCol[$numfield]))
							$this->newcontact->$namefield = (is_numeric($linew[$posCol[$numfield]]))?strtotime($linew[$posCol[$numfield]]):0;
						break;
					case "Date":
						$this->newcontact->$namefield = (isset($posCol[$numfield]))?$linew[$posCol[$numfield]]:date('Y-m-d',time());
						break;
				}
			} 
			elseif ($field->required == "No" && $field->type == "Numerical") {
				if(isset($posCol[$numfield]))
					$this->newcontact->$namefield = (is_numeric($linew[$posCol[$numfield]]))?$linew[$posCol[$numfield]]:$field->defaultValue;
			}
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
