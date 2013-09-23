<?php
//		Phalcon\DI::getDefault()->get('logger')->log("");
class ImportContactWrapper extends BaseWrapper
{
	protected $idContactlist;
	protected $newcontact;
	protected $idFile;
	protected $ipaddress;
//	protected $tablename;


	public function setIdFile($idFile) {
		$this->idFile = $idFile;
	}

	public function setIdContactlist($idContactlist) {
		$this->idContactlist = $idContactlist;
	}
	
	public function setIpaddress($ipaddress) {
		$this->ipaddress = $ipaddress;
	}
	
//	public function setTablename($tablename) {
//		$this->tablename = $tablename;
//	}
	
	public function startImport($fields, $destiny, $delimiter, $header) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		$mode = $this->account->accountingMode;
		if ($mode == "Contacto") {
			$contactLimit = $this->account->contactLimit;
			$activeContacts = $this->account->countActiveContactsInAccount();
		} else {
			$contactLimit = 1;
			$activeContacts = 0;
		}
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		$dbase = Dbase::findFirstByIdDbase($list->idDbase);
		$posCol = (array) $fields;
		
		if(empty($posCol)) {
			throw new \InvalidArgumentException('No hay Mapeo de los Campos y las Columnas del Archivo');
		}

		$newproccess = new Importproccess();
						
		$newproccess->idAccount = $this->account->idAccount;
		$newproccess->inputFile = $this->idFile;

		if(!$newproccess->save()) {
			throw new \InvalidArgumentException('No se creo ningun proceso de importaction');
		}
		
//		$this->tablename = "tmp". $newproccess->idImportproccess;
		$deletetable = "TRUNCATE TABLE tmpimport;";
		$db->execute($deletetable);
		
		if($activeContacts < $contactLimit) {
			
			$customfields = $this->createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $dbase->idDbase);

			$this->createFieldInstances($customfields, $dbase->idDbase);
		}
		
		$dbase->updateCountersInDbase();
		$list->updateCountersInContactlist();
		
		$count = $this->runReports($destiny, $header, $delimiter, $activeContacts, $contactLimit, $mode, $newproccess->idImportproccess);
		
//		$db->execute($deletetable);
		
		return $count;
		
	}
	
	protected function createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $idDbase)
	{	
		$db = Phalcon\DI::getDefault()->get('db');
		$idAccount = $this->account->idAccount;
		$customfields = array();
		$emails = array();
		$line = 0;
		$idArray = 0;
		$oldActiveContacts = 0;
		$thisActiveContacts = $activeContacts;
		$values = "";
		
		$open = fopen($destiny, "r");
		
		if($header) {
			$linew = fgetcsv($open, 0, $delimiter);
		}
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			$email = (isset($posCol['email']))?$linew[$posCol['email']]:"";
			$name = (isset($posCol['name']))?$linew[$posCol['name']]:"";
			$lastname = (isset($posCol['lastname']))?$linew[$posCol['lastname']]:"";
			
			if ( !empty($linew) ) {
				if ( ($thisActiveContacts < $contactLimit) ) {
					$email = strtolower($email);
					if ( \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
						if (empty($emails) || !in_array($email, $emails)) {
							array_push($emails, $email);	
							if ( $line != 0 ) {
								$values.=", ";
							}
							list($user, $edomain) = preg_split("/@/", $email, 2);
							$fieldEmail = $db->escapeString($email);
							$fieldDomain = $db->escapeString($edomain);
							$fieldName = $db->escapeString($name);
							$fieldLastname = $db->escapeString($lastname);
							$values.= "($idArray, $fieldEmail, find_or_create_email($fieldEmail, $fieldDomain, $idAccount), $fieldName, $fieldLastname)";
							foreach ($posCol as $key => $value) {
								if(is_numeric($key) && !empty($linew[$value])){
									$valuescf = array ($idArray, $key, $linew[$value]);
									array_push($customfields, $valuescf);
								}
							} 
							$line++;
							$idArray++;
							if ($mode == "Contacto") {
								$thisActiveContacts++;
							}
						}
					}
				}
			}
			
			if (($line == 50 || feof($open) || ($thisActiveContacts == $contactLimit && $mode == "Contacto")) && (!empty($values))) {
				$contactsInserted = $this->runSQLs($values, $idAccount, $idDbase);
				if ($mode == "Contacto") {
					$thisActiveContacts = $activeContacts + $contactsInserted + $oldActiveContacts;
					$oldActiveContacts += $contactsInserted;
				}
				$line = 0;
				$values = "";
			}
		}
		
		fclose($open);
		
		return $customfields;
	}
	
	protected function runSQLs($values, $idAccount, $idDbase)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$hora = time();
		
//		$newtable = "CREATE TABLE $this->tablename LIKE tmpimport";
		$tabletmp = "INSERT INTO tmpimport(idArray, email, idEmail, name, lastName) VALUES $values;";
		$findidemailblocked = "UPDATE tmpimport t JOIN email e ON (t.idEmail = e.idEmail) SET t.blocked = 1 WHERE t.idEmail IS NOT NULL AND e.blocked > 0 AND e.idAccount = $idAccount;";
		$findidcontactinDB = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail) SET t.idContact = c.idContact, t.dbase = 1 WHERE t.idEmail IS NOT NULL AND c.idDbase = $idDbase;";
		$findidcontact = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail) SET t.idContact = c.idContact WHERE t.idEmail IS NOT NULL AND c.idDbase = $idDbase;";
		$findcoxcl = "UPDATE tmpimport t JOIN coxcl x ON (t.idContact = x.idContact) SET t.coxcl = 1 WHERE t.idContact IS NOT NULL AND x.idContactlist = $this->idContactlist;";
		$countemailsavailables = "SELECT COUNT(*) AS cnt FROM tmpimport WHERE idContact IS NULL AND blocked IS NULL";
		$createcontacts = "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) SELECT $idDbase, t.idEmail, t.name, t.lastName, $hora, 0, 0, 0, $this->ipaddress, $this->ipaddress, $hora, $hora, $hora FROM tmpimport t WHERE t.idContact IS NULL AND t.blocked IS NULL;";
		$createcoxcl = "INSERT INTO coxcl (idContactlist, idContact, createdon) SELECT $this->idContactlist, t.idContact, $hora FROM tmpimport t WHERE t.coxcl IS NULL AND t.blocked IS NULL";
		$status = "UPDATE tmpimport SET status = 1 WHERE coxcl IS NULL AND blocked IS NULL;";
		
		$db->begin();
		
//		$db->execute($newtable);
		
		$db->execute($tabletmp);
		
		$db->execute($findidemailblocked);
		
		$db->execute($findidcontactinDB);
		$db->execute($createcontacts);
		
		$contactsToCreate = $db->fetchAll($countemailsavailables);
		
		$db->execute($findidcontact);
		
		$db->execute($findcoxcl);
		$db->execute($createcoxcl);
		$db->execute($status);
		
		$db->commit();
		
		return $contactsToCreate[0]['cnt'];
	}
	
	protected function createFieldInstances ($customfields, $idDbase) 
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$values = "";
		$line = 0;
		$DateCF = "SELECT idCustomField FROM customfield WHERE type = 'DATE' AND idDbase = $idDbase;";
		$idcontactsfromtmp = "SELECT t.idArray, t.idContact FROM tmpimport t WHERE t.idContact IS NOT NULL AND t.status = 1 AND t.dbase IS NULL;";
		
		$idscontactwithcf = $db->fetchAll($idcontactsfromtmp);
		$idsDateCF = $db->fetchAll($DateCF);
		
		foreach ($idscontactwithcf as $ids){
			$done = FALSE;
			$idForArray = $ids['idArray'];
			$idtoContact = $ids['idContact'];
			foreach ($customfields as $cf) {
				if ($cf[0] == $idForArray) {
					if($line != 0) {
						$values.=", ";
					}
					foreach ($idsDateCF as $dates) {
						if ($cf[1] == $dates['idCustomField']) {
							$date = strtotime($cf[2]);
							$value = (is_numeric($date))?$date:"NULL";
							$values.= "($cf[1], $idtoContact, NULL, $value)";
							$done = TRUE;
						}
					}
					if (!$done) {
						$values.= "($cf[1], $idtoContact, '$cf[2]', NULL)";
					}
					$line++;
				}
				if ((!empty($values)) && $line == 100) {
				$createFieldinstances = "INSERT INTO fieldinstance (idCustomField, idContact, textValue, numberValue) VALUES ".$values.";";
				$db->execute($createFieldinstances);
				$line = 0;
				$values = "";
			}
			}
		}
		if(!empty($values) && $line != 0){
			$createFieldinstances = "INSERT INTO fieldinstance (idCustomField, idContact, textValue, numberValue) VALUES ".$values.";";
			$db->execute($createFieldinstances);
		}
	}

	protected function runReports($destiny, $header, $delimiter, $activeContacts, $contactLimit, $mode, $idImportproccess) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		$total = 0;
		$invalid = 0;
		$limit = 0;
		$exist = 0;
		$bloqued = 0;
		$repeated = 0;
		$emails = array();
		$success = array();
		$errors = array();
		
		$querytxt1 = "SELECT t.email FROM tmpimport t WHERE t.blocked = 1;";
		$querytxt2 = "SELECT t.email FROM tmpimport t WHERE t.coxcl = 1 AND t.blocked IS NULL AND t.status IS NULL;";
		$querytxt3 = "SELECT t.email FROM tmpimport t WHERE t.status = 1;";

		$emailsBlocked = $db->fetchAll($querytxt1);
		$emailsRepeated = $db->fetchAll($querytxt2);
		$emailsImported = $db->fetchAll($querytxt3);
		
		$open = fopen($destiny, "r");
		
		if($header) {
			$linew = fgetcsv($open, 0, $delimiter);
		}
		
		while(! feof($open)) {
			$done = FALSE;
			$linew = fgetcsv($open, 0, $delimiter);
			if ( !empty($linew) ) {
				$email = strtolower($linew[0]);
				if ( !\filter_var($email, FILTER_VALIDATE_EMAIL) ) {
					array_push($linew, "Correo Invalido");
					array_push($errors, $linew);
					$invalid++;
				} elseif (!empty($emails) && in_array($email, $emails)) {
					array_push($linew, "Correo Repetido en Archivo");
					array_push($errors, $linew);
					$repeated++;
				}
				else {
					array_push($emails, $email);	
					foreach ($emailsImported as $emailimported) {
						if($emailimported['email'] == $linew[0]) {
							array_push($success, $linew);
							$done = TRUE;
						}
					}
					foreach ($emailsBlocked as $emailblocked) {
						if($emailblocked['email'] == $linew[0]) {
							array_push($linew, "Correo Bloqueado");
							array_push($errors, $linew);
							$bloqued++;
							$done = TRUE;
						}
					}
					foreach ($emailsRepeated as $emailrepeated) {
						if($emailrepeated['email'] == $linew[0]){
							array_push($linew, "Existente");
							array_push($errors, $linew);
							$exist++;
							$done = TRUE;
						} 
					}
					if(!$done) {
						array_push($linew, "Limite de Contactos Excedido");
						array_push($errors, $linew);
						$limit++;
					}

				}
				$total++;
			}
		}
		fclose($open);
		$this->createReports($errors, $success, $idImportproccess);
		
		$count = array(
			"total" => $total,
			"import" => $total-($exist+$invalid+$bloqued+$limit+$repeated),
			"Nimport" => $exist+$invalid+$bloqued+$limit+$repeated,
			"exist" => $exist,
			"invalid" => $invalid,
			"bloqued" => $bloqued,
			"limit" => $limit,
			"repeated" => $repeated,
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
			$fp = fopen('../../../tmp/ifiles/' . $nameNimported, 'w');

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
			$fp = fopen('../../../tmp/ifiles/' . $nameImported, 'w');		

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
