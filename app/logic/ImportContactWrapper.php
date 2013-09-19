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
		$contactLimit = $this->account->contactLimit;
		$activeContacts = $this->account->countActiveContactsInAccount();
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		$dbase = Dbase::findFirstByIdDbase($list->idDbase);
		$posCol = $fields;
		
		if(empty($posCol)) {
			throw new \InvalidArgumentException('No hay Mapeo de los Campos y las Columnas del Archivo');
		}

		$newproccess = new Importproccess();
						
		$newproccess->idAccount = $this->account->idAccount;
		$newproccess->inputFile = $this->idFile;

		if(!$newproccess->save()) {
			throw new \InvalidArgumentException('No se creo ningun proceso de importaction');
		}
		$deletetable = "TRUNCATE TABLE tmpimport;";
		
		$db->begin();
		
		$db->execute($deletetable);
		
		$db->commit();
		
		if($activeContacts < $contactLimit) {
			
			$customfields = $this->createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $dbase->idDbase);

			$this->createFieldInstances($customfields, $dbase->idDbase);
		}
		
		$count = $this->runReports($destiny, $header, $delimiter, $activeContacts, $contactLimit, $newproccess->idImportproccess);
		
		$db->begin();
		
		$db->execute($deletetable);
		
		$db->commit();
		
		$dbase->updateCountersInDbase();
		$list->updateCountersInContactlist();
		
		return $count;
		
	}
	
	protected function createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $idDbase)
	{	
		$db = Phalcon\DI::getDefault()->get('db');
		$idAccount = $this->account->idAccount;
		$customfields = array();
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
				if ( ($thisActiveContacts <= $contactLimit) ) {
					$email = strtolower($email);
					if ( \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
						if ( $line != 0 ) {
							$values.=", ";
						}
						list($user, $edomain) = preg_split("/@/", $email, 2);
						$fieldEmail = $db-> escapeString($email);
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
						$thisActiveContacts++;
					}
				}
			}
			
			if (($line == 50 || feof($open) || $thisActiveContacts == $contactLimit) && (!empty($values))) {
				$contactsInserted = $this->runSQLs($values, $idAccount, $idDbase);
				$thisActiveContacts = $activeContacts + $contactsInserted + $oldActiveContacts;
				$oldActiveContacts += $contactsInserted;
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
		$ipaddress = ip2long($_SERVER["REMOTE_ADDR"]);
		
		$tabletmp = "INSERT INTO tmpimport(idArray, email, idEmail, name, lastName) VALUES $values;";
		$findidemailblocked = "UPDATE tmpimport t JOIN email e ON (t.idEmail = e.idEmail) SET t.blocked = 1 WHERE t.idEmail IS NOT NULL AND e.blocked > 0 AND e.idAccount = $idAccount;";
		$findidcontactinDB = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail) SET t.idContact = c.idContact, t.dbase = 1 WHERE t.idEmail IS NOT NULL AND c.idDbase = $idDbase;";
		$findidcontact = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail) SET t.idContact = c.idContact WHERE t.idEmail IS NOT NULL AND c.idDbase = $idDbase;";
		$findcoxcl = "UPDATE tmpimport t JOIN coxcl x ON (t.idContact = x.idContact) SET t.coxcl = 1 WHERE t.idContact IS NOT NULL AND x.idContactlist = $this->idContactlist;";
		$countemailsavailables = "SELECT COUNT(*) AS cnt FROM tmpimport WHERE idContact IS NULL AND blocked IS NULL";
		$createcontacts = "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) SELECT $idDbase, t.idEmail, t.name, t.lastName, $hora, 0, 0, 0, $ipaddress, $ipaddress, $hora, $hora, $hora FROM tmpimport t WHERE t.idContact IS NULL AND t.blocked IS NULL;";
		$createcoxcl = "INSERT INTO coxcl (idContactlist, idContact, createdon) SELECT $this->idContactlist, t.idContact, $hora FROM tmpimport t WHERE t.coxcl IS NULL AND t.blocked IS NULL";
		$status = "UPDATE tmpimport SET status = 1 WHERE coxcl IS NULL AND blocked IS NULL;";
		Phalcon\DI::getDefault()->get('logger')->log($tabletmp);
		$db->begin();
		
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
		$idcontactsfromtmp = "SELECT t.idArray, t.idContact FROM tmpimport t WHERE t.idContact IS NOT NULL AND t.status = 1;";
		
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
				$db->begin();
				$db->execute($createFieldinstances);
				$db->commit();
				$line = 0;
				$values = "";
			}
			}
		}
		if(!empty($values) && $line != 0){
			$createFieldinstances = "INSERT INTO fieldinstance (idCustomField, idContact, textValue, numberValue) VALUES ".$values.";";
			$db->begin();
			$db->execute($createFieldinstances);
			$db->commit();
		}
	}

	protected function runReports($destiny, $header, $delimiter, $activeContacts, $contactLimit, $idImportproccess) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		
		$total = 0;
		$invalid = 0;
		$limit = 0;
		$exist = 0;
		$bloqued = 0;
		$success = array();
		$errors = array();
		
		$querytxt1 = "SELECT t.email FROM tmpimport t WHERE t.blocked = 1;";
		$querytxt2 = "SELECT t.email FROM tmpimport t WHERE t.coxcl = 1 AND t.blocked IS NULL AND t.status IS NULL;";
		$querytxt3 = "SELECT t.email FROM tmpimport t WHERE t.status IS NULL AND t.coxcl IS NULL AND t.blocked IS NULL;";

		$emailsBlocked = $db->fetchAll($querytxt1);
		$emailsRepeated = $db->fetchAll($querytxt2);
		$emailsLimit = $db->fetchAll($querytxt3);
		
		$open = fopen($destiny, "r");
		
		if($header) {
			$linew = fgetcsv($open, 0, $delimiter);
		}
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			
			if ( !empty($linew) && $activeContacts < $contactLimit) {
				array_push($success, $linew);
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
						foreach ($emailsLimit as $emaillimit) {
							if($emaillimit['email'] == $linew[0]) {
								array_pop($success);
								array_push($linew, "Limite de Contactos Excedido");
								array_push($errors, $linew);
								$limit++;
							}
						}
					}
				} elseif($activeContacts >= $contactLimit) {
					array_push($linew, "Limite de Contactos Excedido");
					array_push($errors, $linew);
					$limit++;
				}
				$total++;
				$activeContacts++;
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
