<?php

class ImportContactWrapper extends BaseWrapper
{
	protected $idContactlist;
	protected $newcontact;
	protected $idFile;
	
	public function setIdFile($idFile) {
		$this->idFile = $idFile;
	}

	public function setIdContactlist($idContactlist) {
		$this->idContactlist = $idContactlist;
	}
	
	public function startImport($fields, $destiny, $delimiter, $header) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$hora = time();
		$ipaddress = ip2long($_SERVER["REMOTE_ADDR"]);
		$posCol = $fields;
		$firstline = TRUE;
		$values = "";
		$contactLimit = $this->account->contactLimit;
		$activeContacts = $this->account->countActiveContactsInAccount();
		$idAccount = $this->account->idAccount;

		$open = fopen($destiny, "r");
		
		if($header) {
			$linew = fgetcsv($open, 0, $delimiter);
		}
	
		if(empty($posCol)) {
			throw new \InvalidArgumentException('No hay Mapeo de los Campos y las Columnas del Archivo');
		}
		
		$newproccess = new Importproccess();
						
		$newproccess->idAccount = $this->account->idAccount;
		$newproccess->inputFile = $this->idFile;

		if(!$newproccess->save()) {
			throw new \InvalidArgumentException('No se creo ningun proceso de importaction');
		}
		
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
//		$customfields = Customfield::findByIdDbase($list->idDbase);
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			$name = (isset($posCol[1]))?$linew[$posCol[1]]:"";
			$lastname = (isset($posCol[2]))?$linew[$posCol[2]]:"";
			
			if ( !empty($linew) ) {
				if ( ($activeContacts <= $contactLimit) ) {
					$email = strtolower($linew[0]);
					if ( \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
						if ( !$firstline ) {
							$values.=", ";
						}
						list($user, $edomain) = preg_split("/@/", $linew[0], 2);	
						$values.= "('$linew[0]', find_or_create_email('$linew[0]', '$edomain', $idAccount), '$name', '$lastname', '$edomain', find_domain('$edomain'))";
						$firstline = FALSE;
					}
				}
			}
		}
		fclose($open);
		
		$tabletmp = "INSERT INTO tmpimport(email, idEmail, name, lastName, domain, idDomain) VALUES ".$values.";";
		
		$deletetable = "TRUNCATE TABLE tmpimport;";
		
		$findidemailblocked = "UPDATE tmpimport t JOIN email e ON (t.idEmail = e.idEmail AND e.idAccount = $idAccount) SET t.blocked = 1 WHERE t.idEmail IS NOT NULL AND e.blocked > 0;";
		
		$findidcontact = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail AND c.idDbase = $list->idDbase) SET t.idContact = c.idContact WHERE t.idEmail IS NOT NULL;";
		
		$findcoxcl = "UPDATE tmpimport t JOIN coxcl x ON (t.idContact = x.idContact AND x.idContactlist = $this->idContactlist) SET t.coxcl = 1 WHERE t.idContact IS NOT NULL;";
		
		$createcontacts = "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) SELECT $list->idDbase, t.idEmail, t.name, t.lastName, $hora, 0, 0, 0, $ipaddress, $ipaddress, $hora, $hora, $hora FROM tmpimport t WHERE t.idContact IS NULL AND t.blocked IS NULL;";
		
		$createcoxcl = "INSERT INTO coxcl (idContactlist, idContact, createdon) SELECT $this->idContactlist, t.idContact, $hora FROM tmpimport t WHERE t.coxcl IS NULL AND t.blocked IS NULL";
		
		$db->begin();
		
		$firstquery = $db->execute($tabletmp);
		
		$emailsblocked = $db->execute($findidemailblocked);
		
		$idcontacts = $db->execute($findidcontact);
		$contactscreated = $db->execute($createcontacts);
		$idcontactscreated = $db->execute($findidcontact);
		
		$coxcls = $db->execute($findcoxcl);
		$createdcoxcl = $db->execute($createcoxcl);
		
		$db->commit();
		
		$count = $this->runReports($destiny, $delimiter, $activeContacts, $contactLimit, $newproccess->idImportproccess);
		
		$db->begin();
		
		$truncatetable = $db->execute($deletetable);
		
		$db->commit();
				
		return $count;
		
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

	protected function runReports($destiny, $delimiter, $activeContacts, $contactLimit, $idImportproccess) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$total = 0;
		$invalid = 0;
		$limit = 0;
		$exist = 0;
		$bloqued = 0;
		$success = array();
		$errors = array();
		
		$querytxt1 = "SELECT t.email FROM tmpimport t WHERE t.blocked = 1;";
		$querytxt2 = "SELECT t.email FROM tmpimport t WHERE t.coxcl = 1 AND t.blocked IS NULL;";

		$emailsBlocked = $db->fetchAll($querytxt1);
		$emailsRepeated = $db->fetchAll($querytxt2);
		
		$open = fopen($destiny, "r");
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			
			if ( !empty($linew) ) {
				array_push($success, $linew);
				if ( !($activeContacts <= $contactLimit) ) {
					array_pop($success);
					array_push($linew, "Limite de Contactos Excedido");
					array_push($errors, $linew);
					$limit++;
				}
				else {
					$email = strtolower($linew[0]);
					if ( !\filter_var($email, FILTER_VALIDATE_EMAIL) ) {
						array_pop($success);
						array_push($linew, "Correo Invalido");
						array_push($errors, $linew);
						$invalid++;
					} 
					else {
						foreach ($emailsBlocked as $emailblocked) {
							if($emailblocked['email'] == $linew[0]) {
								array_pop($success);
								array_push($linew, "Correo Bloqueado");
								array_push($errors, $linew);
								$bloqued++;
							}
						}
						foreach ($emailsRepeated as $emailrepeated) {
							if($emailrepeated['email'] == $linew[0]){
								array_pop($success);
								array_push($linew, "Existente");
								array_push($errors, $linew);
								$exist++;
							} 
						}
					}
				}
				$total++;
			}
		}
		fclose($open);
		$this->createReports($errors, $success, $idImportproccess);
		
		$count = array(
			"total" => $total,
			"import" => $total-($exist+$invalid+$bloqued+$limit),
			"Nimport" => $exist+$invalid+$bloqued+$limit,
			"exist" => $exist,
			"invalid" => $invalid,
			"bloqued" => $bloqued,
			"limit" => $limit,
			"idProcces" => $idImportproccess
		);
		
		return $count;
	}
	

	protected function createReports($errors, $success, $idImportproccess)
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
		
		$proccess = Importproccess::findFirstByIdImportproccess($idImportproccess);
		
		$proccess->errorFile = $saveFileError->idImportfile;
		$proccess->successFile = $saveFileSuccess->idImportfile;
		
		if(!$proccess->save()) {
			throw new InvalidArgumentException("Error al crear el registro del proceso de importacion");
		}
		
	}
}
