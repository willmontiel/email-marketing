<?php
//		Phalcon\DI::getDefault()->get('logger')->log("");
class ImportContactWrapper extends BaseWrapper
{
	protected $idContactlist;
	protected $newcontact;
	protected $idProccess;
	protected $ipaddress;
	protected $tablename;


	public function setIdProccess($idProccess) {
		$this->idProccess = $idProccess;
	}

	public function setIdContactlist($idContactlist) {
		$this->idContactlist = $idContactlist;
	}
	
	public function setIpaddress($ipaddress) {
		$this->ipaddress = $ipaddress;
	}
	
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

		$newproccess = Importproccess::findFirstByIdImportproccess($this->idProccess);
		$newproccess->status = "En Ejecucion";

		if(!$newproccess->save()) {
			throw new \InvalidArgumentException('No se pudo actualizar el estado del proceso');
		}
		
		$this->tablename = "tmp". $newproccess->idImportproccess;
		
		$newtable = "CREATE TABLE $this->tablename LIKE tmpimport";
		$deletetable = "DROP TABLE $this->tablename";

		$db->execute($newtable);
		
		if ($activeContacts < $contactLimit) {			
			$this->createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $dbase->idDbase);
		}

		$dbase->updateCountersInDbase();
		$list->updateCountersInContactlist();

		$db->execute($deletetable);
		
	}
	
	protected function createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $idDbase)
	{	
		$db = Phalcon\DI::getDefault()->get('db');
		$idAccount = $this->account->idAccount;
		$customfields = array();
		$emails = array();
		$errors = array();
		$repeated = 0;
		$invalid = 0;
		$limit = 0;
		$line = 0;
		$totalLine = 0;
		$oldActiveContacts = 0;
		$thisActiveContacts = $activeContacts;
		$values = "";
		
		$open = @fopen($destiny, "r");
		
		if (!$open) {
			throw new InvalidArgumentException("Error al abrir el archivo original");
		}
		
		if ($header) {
			$linew = fgetcsv($open, 0, $delimiter);
		}
		
		while(! feof($open)) {
			
			$linew = fgetcsv($open, 0, $delimiter);
			
			$email = (!empty($posCol['email']) || $posCol['email'] == '0')?$linew[$posCol['email']]:"";
			$name = (!empty($posCol['name']) || $posCol['name'] == '0')?$linew[$posCol['name']]:"";
			$lastname = (!empty($posCol['lastname']) || $posCol['lastname'] == '0')?$linew[$posCol['lastname']]:"";
			
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
							
							$values.= "($totalLine, $fieldEmail, find_or_create_email($fieldEmail, $fieldDomain, $idAccount), $fieldName, $fieldLastname)";
							
							foreach ($posCol as $key => $value) {
								
								if(is_numeric($key) && !empty($linew[$value])){
									$valuescf = array ($totalLine, $key, $linew[$value]);
									array_push($customfields, $valuescf);
								}
								
							} 
							
							$line++;
							
							if ($mode == "Contacto") {
								
								$thisActiveContacts++;
								
							}
						}
						else {
							$lineInFile = ($header)?$totalLine+2:$totalLine+1;
							array_push($errors, $lineInFile.','.$email.',Correo Repetido en Archivo');
							$repeated++;
						}
					}
					else {
						$lineInFile = ($header)?$totalLine+2:$totalLine+1;
						array_push($errors, $lineInFile.','.$email.',Correo Invalido');
						$invalid++;
					}
				}
				else {
					$lineInFile = ($header)?$totalLine+2:$totalLine+1;
					array_push($errors, $lineInFile.','.$email.',Limite de Contactos Excedido');
					$limit++;
				}
				$totalLine++;
			}
			
			if (($line == 50 || feof($open) || ($thisActiveContacts == $contactLimit && $mode == "Contacto")) && (!empty($values))) {
				
				$contactsInserted = $this->runSQLs($values, $idAccount, $idDbase);
				if ($mode == "Contacto") {
					
					$thisActiveContacts = $activeContacts + $contactsInserted + $oldActiveContacts;
					$oldActiveContacts += $contactsInserted;
				}
				
				$this->createFieldInstances($customfields, $idDbase);
				
				$queryToAdd = "UPDATE importproccess SET processLines = $totalLine WHERE idImportproccess = $this->idProccess";
				$db->execute($queryToAdd);
				
				$line = 0;
				$values = "";
				$customfields = array();
			}
		}
		
		fclose($open);
		$this->runReports($errors, $repeated, $invalid, $limit);
	}
	
	protected function runSQLs($values, $idAccount, $idDbase)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$hora = time();
		
		$tabletmp = "INSERT INTO $this->tablename (idArray, email, idEmail, name, lastName) VALUES $values;";
		$findidemailblocked = "UPDATE $this->tablename t JOIN email e ON (t.idEmail = e.idEmail) SET t.blocked = 1 WHERE t.idEmail IS NOT NULL AND e.blocked > 0 AND e.idAccount = $idAccount;";
		$findidcontactinDB = "UPDATE $this->tablename t JOIN contact c ON (t.idEmail = c.idEmail) SET t.idContact = c.idContact, t.dbase = 1 WHERE t.idEmail IS NOT NULL AND c.idDbase = $idDbase;";
		$findidcontact = "UPDATE $this->tablename t JOIN contact c ON (t.idEmail = c.idEmail) SET t.idContact = c.idContact WHERE t.idEmail IS NOT NULL AND c.idDbase = $idDbase;";
		$findcoxcl = "UPDATE $this->tablename t JOIN coxcl x ON (t.idContact = x.idContact) SET t.coxcl = 1 WHERE t.idContact IS NOT NULL AND x.idContactlist = $this->idContactlist;";
		$countemailsavailables = "SELECT COUNT(*) AS cnt FROM $this->tablename WHERE idContact IS NULL AND blocked IS NULL";
		$createcontacts = "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) SELECT $idDbase, t.idEmail, t.name, t.lastName, $hora, 0, 0, 0, $this->ipaddress, $this->ipaddress, $hora, $hora, $hora FROM $this->tablename t WHERE t.idContact IS NULL AND t.blocked IS NULL;";
		$createcoxcl = "INSERT INTO coxcl (idContactlist, idContact, createdon) SELECT $this->idContactlist, t.idContact, $hora FROM $this->tablename t WHERE t.coxcl IS NULL AND t.blocked IS NULL";
		$status = "UPDATE $this->tablename SET status = 1 WHERE coxcl IS NULL AND blocked IS NULL;";
		$newcontact = "UPDATE $this->tablename SET new = 1 WHERE idContact IS NULL AND blocked IS NULL";
		
		$db->begin();
		
		$db->execute($tabletmp);
		
		$db->execute($findidemailblocked);
		
		$db->execute($findidcontactinDB);
		$db->execute($createcontacts);
		$db->execute($newcontact);
		
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
		$idcontactsfromtmp = "SELECT t.idArray, t.idContact FROM $this->tablename t WHERE t.idContact IS NOT NULL AND t.new IS NOT NULL;";
		
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
						$value = $db->escapeString($cf[2]);
						$values.= "($cf[1], $idtoContact, $value, NULL)";
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

	protected function runReports($errors, $repeated, $invalid, $limit) {
		$db = Phalcon\DI::getDefault()->get('db');
		$exist = 0;
		$bloqued = 0;
		$uniquecode = uniqid();

		// Path de archivos
		$filePath = Phalcon\DI::getDefault()->get('appPath')->path . '/tmp/ifiles/';
		$filesPath = str_replace("\\", "/", $filePath);

		$nameImported =  $this->account->idAccount."_".date("ymdHi",time())."_".$uniquecode."imported.csv";
		$nameNimported = $this->account->idAccount."_".date("ymdHi",time())."_".$uniquecode."noneimported.csv";
		
		$saveFileError = new Importfile();
		$saveFileError->idAccount = $this->account->idAccount;
		$saveFileError->internalName = $nameNimported;
		$saveFileError->originalName = $nameNimported;
		$saveFileError->createdon = time();
		
		if (!$saveFileError->save()) {
			throw new \InvalidArgumentException('No se pudo crear el archivo de Errores');
		} 
		else {
			$queryForErrors =  "SELECT idArray, email, 
									CASE WHEN blocked = 1 THEN 'Correo Bloqueado'
										WHEN coxcl = 1 AND blocked IS NULL THEN 'Existente'
									END
								FROM {$this->tablename}
								WHERE status IS NULL
								INTO OUTFILE  '{$filesPath}{$nameNimported}'
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\n'";

			$db->execute($queryForErrors);							
			
			$fp = fopen($filesPath . $nameNimported, 'a');

			foreach ($errors as $error) {
				fputcsv($fp, $error);
			}

			fclose($fp);
		}
		
		$saveFileSuccess = new Importfile();
		$saveFileSuccess->idAccount = $this->account->idAccount;
		$saveFileSuccess->internalName = $nameImported;
		$saveFileSuccess->originalName = $nameImported;
		$saveFileSuccess->createdon = time();
		
		if (!$saveFileSuccess->save()) {
			throw new \InvalidArgumentException('No se pudo crear el archivo de Exito');
		} else {
			$queryForSuccess = "SELECT idArray, email
								FROM {$this->tablename}
								WHERE status = 1
								INTO OUTFILE  '{$filesPath}{$nameImported}'
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\n'";

			$db->execute($queryForSuccess);
		}
		$queryBloqued = "SELECT COUNT(*) AS 'bloqueados' FROM {$this->tablename} WHERE blocked = 1 AND status IS NULL";
		$queryExist = "SELECT COUNT(*)	AS 'existentes' FROM {$this->tablename} WHERE status IS NULL AND coxcl = 1 AND blocked IS NULL";
		
		$bloquedCount = $db->fetchAll($queryBloqued);
		$existCount = $db->fetchAll($queryExist);
		
		$bloqued = $bloquedCount[0]['bloqueados'];
		$exist = $existCount[0]['existentes'];
		
		$queryInfo = "UPDATE importproccess SET exist = $exist, invalid = $invalid, bloqued = $bloqued, limitcontact = $limit, repeated = $repeated WHERE idImportproccess = $this->idProccess";
		$db->execute($queryInfo);
		
		$proccess = Importproccess::findFirstByIdImportproccess($this->idProccess);
		
		$proccess->errorFile = $saveFileError->idImportfile;
		$proccess->successFile = $saveFileSuccess->idImportfile;
		$proccess->status = "Finalizado";
		
		if(!$proccess->save()) {
			throw new \InvalidArgumentException('No se pudo actualizar el estado del proceso');
		}
	}
}
