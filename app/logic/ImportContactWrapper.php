<?php
/**
 * 
 */
class ImportContactWrapper
{

	// Numero de registros que hacen parte del lote
	// para insercion
	const BATCH_SIZE = 100;

	protected $idContactlist;
	protected $newcontact;
	protected $idProccess;
	protected $ipaddress;
	protected $tablename;

	protected $repeated;
	protected $errors;
	protected $invalid;
	protected $emailbuffers;
	
	/**
	 *
	 * @var Account
	 */
	protected $account;

	/**
	 *
	 * @var TimerObject
	 */
	protected $timer;

	protected $temporaryMode;
	protected $debugMode;
	/**
	 *
	 * @var \Phalcon\Db\Adapter
	 */
	protected $db;
	/**
	 *
	 * @var Importprocess
	 */
	protected $process;
	
	protected $log;

	public function __construct()
	{
		// Por defecto las tablas temporales son "TEMPORALES"
		// Por defecto el modo debug esta apagado
		$this->temporaryMode = true;
		$this->debugMode = false;
		
		$this->timer = Phalcon\DI::getDefault()->get('timerObject');
		$this->log = Phalcon\DI::getDefault()->get('logger');

	}

	protected function resetProcess()
	{
		$this->repeated = 0;
		$this->invalid = 0;
		$this->errors = array();
		$this->emailbuffers = array();
	}
	
	public function setIdProccess($idProccess) {
		$this->idProccess = $idProccess;
	}

	public function setIdContactlist($idContactlist) {
		$this->idContactlist = $idContactlist;
	}
	
	public function setIpaddress($ipaddress) {
		$this->ipaddress = $ipaddress;
	}

	/**
	 * Cargar la instancia de la base de datos
	 */
	protected function getDB()
	{
		if (!$this->db) {
			$this->db = Phalcon\DI::getDefault()->get('db');
		}
	}
	
	public function setAccount(Account $account) {
		$this->account = $account;
	}
	

	/**
	 * Activar/desactivar modo de tabla temporal (para que no sea una tabla global)
	 * Si esta activo, se utiliza el DML "CREATE TEMPORARY TABLE"
	 * y "DELETE TEMPORARY TABLE"
	 * 
	 * Si no esta activo, entonces se utiliza el DML estandar "CREATE TABLE"
	 * 
	 * @param boolean $active
	 */
	public function setTemporaryTableMode($active) 
	{
		$this->temporaryMode = $active;
	}

	/**
	 * Activar/desactivar el mode de depuracion
	 * 
	 * Si esta activo el modo de depuracion, la tabla temporal no se elimina
	 * 
	 * @param type $active
	 */
	public function setDebugImportMode($active)
	{
		$this->debugMode = $active;
		if ($active) {
			// Modo temporal no puede estar activo
			$this->temporaryMode = false;
		}
			
	}
			
	/**
	 * Metodo que realiza la importacion de los registros
	 * 
	 * @param array $fields
	 * @param type $destiny
	 * @param type $delimiter
	 * @param type $header
	 * @throws \InvalidArgumentException
	 */
	public function startImport($fields, $destiny, $delimiter, $header) {
		$mode = $this->account->accountingMode;
		
		$this->resetProcess();
	
		// Cual es el proposito de esto?
		// Controlar la importacion de contactos para que no exceda el limite
		// ?
		$this->timer->startTimer('phase1', 'Prepare to import!');
		if ($mode == "Contacto") {
			$contactLimit = $this->account->contactLimit;
			$activeContacts = $this->account->countActiveContactsInAccount();
		} else {
			$contactLimit = 1;
			$activeContacts = 0;
		}
		
		// Buscar la lista de contactos
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		// Buscar la base de datos
		$dbase = Dbase::findFirstByIdDbase($list->idDbase);

		// En que formato se presentan los campos (de que forma esta codificado
		//esto?
		$posCol = (array) $fields;
		
		// Cargar el proceso de importacion
		$this->loadProcess();

		// Validar que se haya mapeado el campo de correo electronico (requerido)
		if(empty($posCol['email']) && $posCol['email'] != '0') {
			$this->updateProcessStatus('Importacion no realizada');
			$this->saveProcess();
			
			throw new \InvalidArgumentException('No hay Mapeo de los Campos y las Columnas del Archivo');
		}

		$this->updateProcessStatus('En Ejecucion');
		$this->saveProcess();
		
		$this->timer->endTimer('phase1');

		$this->timer->startTimer('phase2', 'Create values to import!');
		
		$this->createTemporaryTable();
		
		// Creacion de contactos insertando registros desde PHP
		// utilizando INSERT INTO, con bloques de correos
		// Muy lento
		//$this->createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $dbase->idDbase);

		// Creacion de contactos utilizando LOAD DATA INFILE
		// Preprocesando el archivo CSV
		$this->importDataFromCSV($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $dbase);
				
		$this->timer->endTimer('phase2');

		$this->timer->startTimer('phase3', 'Update Dbase counters!');
		$dbase->updateCountersInDbase();
		$this->timer->endTimer('phase3');
		$this->timer->startTimer('phase4', 'Update contact lists counters!');
		$list->updateCountersInContactlist();
		$this->timer->endTimer('phase4');
		
		$this->destroyTemporaryTable();
		
		$swrapper = new SegmentWrapper;
		
		// Recrear los segmentos de esta base de datos para tener en cuenta los nuevos contactos importados
		$this->timer->startTimer('phase5', 'Recreate segments!');
		$swrapper->recreateSegmentsInDbase($dbase->idDbase);		
		$this->timer->endTimer('phase5');
	}
	
	
	/**
	 * Crea el nombre de la tabla temporal
	 */
	protected function createTemporaryTableName()
	{
		if (!$this->tablename) {
			$this->tablename = "import_tmp_{$this->process->idImportproccess}";
		}
	}
	/**
	 * Crea la tabla temporal
	 */
	protected function createTemporaryTable()
	{
		$this->createTemporaryTableName();
		$this->getDB();
		$tmp = ($this->temporaryMode)?' TEMPORARY ':'';
		
		$this->db->execute("CREATE {$tmp} TABLE {$this->tablename} LIKE tmpimport");
	}
	
	protected function destroyTemporaryTable()
	{
		$this->getDB();
		if (!$this->debugMode) {
			$this->createTemporaryTableName();
			$tmp = ($this->temporaryMode)?' TEMPORARY ':'';

			$this->db->execute("DROP {$tmp} TABLE {$this->tablename}");
		}
	}
	
	protected function loadProcess()
	{
		$this->process = Importproccess::findFirstByIdImportproccess($this->idProccess);
		
		if (!$this->process) {
			throw new \InvalidArgumentException("El id de proceso {$this->idProccess} es invalido.");
		}
	}
	
	/**
	 * Cambiar el estatus del proceso
	 * @param string $status
	 */
	protected function updateProcessStatus($status)
	{
		$this->process->status = $status;
	}
	
	/**
	 * Grabar el proceso (intentar)
	 * si falla genera una excepcion
	 * @throws \Exception
	 */
	protected function saveProcess()
	{
		if(!$this->process->save()) {
			$str = implode(PHP_EOL, $this->process->getMessages());
			throw new \Exception('Error al actualizar el estado del proceso:' . $str);
		}

	}

	/**
	 * 
	 * @param string $fn
	 * @return int
	 */
	protected function countFileRecords($fn)
	{
		$output = shell_exec("wc -l '{$fn}'");
		
		return intval($output);
	}


	/**
	 * Verifica correo electronico
	 * Si es invalido incrementa contador y graba error
	 * Si ya esta en el archivo, incrementa contador y graba error
	 * 
	 * Si alguno de los dos se cumple retorna null, de lo contrario
	 * retorna el email en minusculas
	 * 
	 * @param string $email
	 * @param int $line
	 * @return string
	 */
	protected function verifyEmailAddress($email, $line)
	{
		$email = strtolower($email);
		if ( \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			if (empty($this->emailbuffers) || !in_array($email, $this->emailbuffers)) {
				array_push($this->emailbuffers, $email);	
			}
			else {
				// Email repetido en el archivo
				$this->errors[] = \sprintf('Correo [%s] repetido en linea %d', $email, $line);
				$this->repeated++;
				$email = null;
			}
		}
		else {
			$this->errors[] = \sprintf('Correo [%s] invalido en linea %d', $email, $line);
			$this->invalid++;
			$email = null;
		}

		return $email;
	}

	/**
	 * 
	 * @param string $sourcefile
	 * @param boolean $hasHeader
	 * @param string $delimiter
	 * @param array $fieldMapping
	 * @param int $currentActiveContacts
	 * @param int $contactLimit
	 * @param string $mode
	 * @param Dbase $dbase
	 * @throws \InvalidArgumentException
	 */
	protected function importDataFromCSV($sourcefile, $hasHeader, $delimiter, $fieldMapping, $activeContacts, $contactLimit, $mode, $dbase)
	{
		// Validar que al menos el campo de email este mapeados
		if (!isset($fieldMapping['email']) ) {
			throw new \InvalidArgumentException('Campo email no esta mapeado en la informacion a importar y es requerido!');
		}
		// Cuantas lineas tiene el archivo?
		$linecount = $this->countFileRecords($sourcefile);

		$maxrows = ($hasHeader)?$linecount:$linecount+1;
		
		if ($mode == 'Contacto') {
			// Modo contactos, verificar que es menor, el numero de registros
			// del archivo, o el numero de contactos que se pueden insertar en
			// la cuenta
			$dif = $contactLimit - $activeContacts;
			$maxrows = ($dif < $maxrows)?$dif:$maxrows;
		}
		$this->log("File rows: {$linecount}, maxrows: {$maxrows}");
		
		// Metodo mas simple:
		// =================
		// Leer linea por linea el archivo fuente
		// Grabar en un archivo destino con los campos a importar (solamente)
		// y con el orden correcto de importacion (ej: email,name,lastname,etc)
		// Validar el email
		// Extraer el dominio
		// Esto hace mas sencillo armar la sentencia de carga... probar tiempo de ejecucion
		// El orden de los campos sera:
		// email,domain,name,lastname
		
		// Archivo temporal donde se guardaran los registros a importar
		// con los campos que se importan y eliminando registros de
		// email invalido
		$tmpFilename = $sourcefile . '.pr';
		$this->timer->startTimer('copy-rows', 'Copy csv file to temporary file!');
		// Ejecutar la copia de registros
		$this->copyCSVRecordsToPR($sourcefile, $tmpFilename, $delimiter, $maxrows, $fieldMapping, $hasHeader);
		$this->timer->endTimer('copy-rows');

		
		// Numero de lineas que tiene ahora el archivo final
		// Esto elimina duplicados, invalidos, etc
		$flines = $this->countFileRecords($tmpFilename);
		$this->log("Temporary file rows: {$flines}");
		
		$this->timer->startTimer('load-rows', 'Load rows from temporary file into database!');
		// Crear sentencia SQL que hace la importacion de los registros desde el
		// archivo temporal
		$rpath = realpath($tmpFilename);
		$importfile = "LOAD DATA INFILE '{$rpath}' INTO TABLE {$this->tablename} FIELDS TERMINATED BY '{$delimiter}' OPTIONALLY ENCLOSED BY '\"'"
					. "(email, domain, name, lastname)";
		
		// Ejecutar sentencia SQL
		$this->db->execute($importfile);
		$this->timer->endTimer('load-rows');
		
		$this->timer->startTimer('update-rows', 'Find or create emails in temporary table!');
		// Procesar todos los registros, asignando un idEmail
		$this->db->execute("UPDATE {$this->tablename} SET idEmail = find_or_create_email(email, domain, {$this->account->idAccount})");
		$this->timer->endTimer('update-rows');
		
		$this->timer->startTimer('clean-rows', 'Clean rows and insert contacts!');
		// Limpiar los registros e insertarlos
		$this->cleanInsertedRecords($this->account->idAccount, $dbase->idDbase);
		$this->timer->endTimer('clean-rows');
	
		// Reporte
		$this->timer->startTimer('report', 'Run reports!');
		$this->runReports($flines);
		$this->timer->endTimer('report');
	}
	
	protected function copyCSVRecordsToPR($sourcefile, $tmpFilename, $delimiter, $maxrows, $fieldMapping, $hasHeader)
	{
		// Indices de posicion de los campos primarios
		$emailPos = $fieldMapping['email'];
		$namePos  = (isset($fieldMapping['name']))?$fieldMapping['name']:-1;
		$lastPos  = (isset($fieldMapping['lastname']))?$fieldMapping['lastname']:-1;
		
		$fp = fopen($sourcefile, 'r');
		$nfp = fopen($tmpFilename, 'w');
		
		$skipped = 0;
		$rows = 0;
		
		$line = fgetcsv($fp, 0, $delimiter);
		if ($hasHeader) {
			$line = fgetcsv($fp, 0, $delimiter);
		}
		while (!feof($fp) && ($rows - $skipped) <= $maxrows) {
			$rows++;
			// Validar EMAIL (correcto y que no este repetido)
			$email = $this->verifyEmailAddress($line[$emailPos], $rows);
			if ($email) {
				$lineOut = array();
				list($user, $domain) = explode('@', $email);
				$lineOut[] = $email;
				$lineOut[] = $domain;
				$lineOut[] = $line[$namePos];
				$lineOut[] = $line[$lastPos];
				
				fputcsv($nfp, $lineOut, $delimiter);
			}
			else {
				$skipped++;
			}
			$line = fgetcsv($fp, 0, $delimiter);
		}
		fclose($fp);
		fclose($nfp);

		$this->log->log("Copying data from [{$sourcefile}] to [{$tmpFilename}]. {$rows} rows processed, {$skipped} rows skipped!");
	}
	
	
	/**
	 * Este metodo lee las lineas del archivo CSV y las convierte en contactos
	 * @param string $destiny
	 * @param type $header
	 * @param string $delimiter
	 * @param type $posCol
	 * @param int $activeContacts
	 * @param int $contactLimit
	 * @param string $mode
	 * @param int $idDbase
	 * @throws InvalidArgumentException
	 */
	protected function createValuesToInsertInTmp($destiny, $header, $delimiter, $posCol, $activeContacts, $contactLimit, $mode, $idDbase)
	{	
		$idAccount = $this->account->idAccount;
		$customfields = array();
		$line = 0;
		$recordsinserted = 0;
		
		// Cuantas lineas tiene el archivo?
		$linecount = $this->countFileRecords($destiny);
		
		$open = @fopen($destiny, "r");
		
		if (!$open) {
			throw new \ErrorException("Error al abrir el archivo de origen de importacion");
		}
		
		// Validar que al menos el campo de email este mapeados
		if (!isset($posCol['email']) ) {
			throw new \InvalidArgumentException('Campo email no esta mapeado en la informacion a importar y es requerido!');
		}
		
		$emailPos = $posCol['email'];
		$namePos  = (isset($posCol['name']))?$posCol['name']:-1;
		$lastPos  = (isset($posCol['lastname']))?$posCol['lastname']:-1;

		$lineInFile = 0;
		// Si hay header, leer la primera linea y no tenerla en cuenta
		if ($header) {
			$lineInFile++;
			$linew = fgetcsv($open, 0, $delimiter);
			$contacts2insert = $linecount-1;
		}
		else {
			$contacts2insert = $linecount;
		}
		
		/*
		 * La carga se hace en 3 pasos:
		 * 1) Se leen e insertan registros de N en N hasta llegar al tope maximo
		 * 2) Se procesan los registros en la tabla temporal
		 * 3) Se insertan los registros a la tabla de contactos
		 * 
		 * Cual es el tope maximo? Depende del modo de la cuenta, 
		 *	si el modo es "Contacto" entonces es el numero de contactos disponibles
		 *  actualmente en la cuenta
		 *  de lo contrario es el numero de registros que tiene el archivo
		 * 
		 */
		if ($mode == 'Contacto') {
			// Modo contactos, verificar que es menor, el numero de registros
			// del archivo, o el numero de contactos que se pueden insertar en
			// la cuenta
			$dif = $contactLimit - $activeContacts;
			$contacts2insert = ($dif < $contacts2insert)?$dif:$contacts2insert;
		}
		
		$recordbuffer = array();
		
		// Recorrer todo el archivo
		// o hasta que se acabe el saldo que tiene el usuario
		$this->timer->startTimer('insert-rows', 'Insert rows into temporary table!');

		while(!feof($open) && ($recordsinserted < $contacts2insert)) {
			
			// Leer linea
			$linew = fgetcsv($open, 0, $delimiter);
			// Incrementar contador de linea
			$lineInFile++;
			
			if ( empty($linew) ) {
				// Linea erronea
				$this->log->log('Linea vacia!');
				// Registro invalido
				$this->invalid++;
				continue;
			}
			
			// Leer campos primarios
			$email = $this->verifyEmailAddress($linew[$emailPos], $lineInFile);
			if (!$email) {
				// Email invalido o repetido
				// continuar con el siguiente registro
				// el metodo adiciona el error e incrementa invalid o repeated
				continue;
			}
			$name = ($namePos>=0)?$linew[$namePos]:'';
			$lastname = ($lastPos>=0)?$linew[$lastPos]:'';

			// Revisar el saldo
			if ( $recordsinserted < $contacts2insert ) {
				// Se puede insertar (todavia hay saldo)
				list($user, $edomain) = preg_split("/@/", $email, 2);
				// Escapar los campos para la insercion y evitar asi problemas
				// que pueden romper la ejecucion
				$fieldEmail = $this->db->escapeString($email);
				$fieldDomain = $this->db->escapeString($edomain);
				$fieldName = $this->db->escapeString($name);
				$fieldLastname = $this->db->escapeString($lastname);
				
				$recordbuffer[] = "({$lineInFile}, {$fieldEmail}, find_or_create_email({$fieldEmail}, {$fieldDomain}, {$idAccount}), {$fieldName}, {$fieldLastname})";

				/*
				 * Campos personalizados!!!
				 * Quedan temporalmente des-habilitados
				foreach ($posCol as $key => $value) {

					if(is_numeric($key) && !empty($linew[$value])){
						$valuescf = array ($lineInFile, $key, $linew[$value]);
						array_push($customfields, $valuescf);
					}

				} 
				 * 
				 */
			}
			// Incrementar contador de registros insertados
			$recordsinserted++;
			
			// Cuando se llegue al numero de lineas por lote
			// Grabar en la tabla, limpiar el buffer y continuar
			if (count($recordbuffer) > self::BATCH_SIZE) {
				$this->saveRecordBuffer($recordbuffer);
				$recordbuffer = array();

				// Informar avance
				$this->incrementProcessAdvance($recordsinserted);
			}
		}
		// Cerrar archivo
		fclose($open);

		// Verificar que no hayan registros en el buffer
		if (count($recordbuffer) > 0) {
			$this->saveRecordBuffer($recordbuffer);
			$recordbuffer = array();

			// Informar avance
			$this->incrementProcessAdvance($recordsinserted);
		}
		$this->timer->endTimer('insert-rows');
		
		$this->timer->startTimer('process-rows', 'Process rows from temporary table!');
		
		// Segundo y tercer paso:
		// Procesar los registros insertados
		// Esto marca los emails bloqueados
		// tambien marca los que ya existen, etc.
		// Insertar los contactos a partir de los registros insertados
		// que son validos
		$this->cleanInsertedRecords($idAccount, $idDbase);
		$this->timer->endTimer('process-rows');
		
		// Correr los reportes...
		// El parametro que se pasa es el numero de registros del archivo
		// que no se procesaron
		$this->timer->startTimer('report', 'Report results!');
		$this->runReports($linecount - $lineInFile + 1);
		$this->timer->endTimer('report');
	}

	protected function incrementProcessAdvance($adv)
	{
		try {
			$this->db->execute("UPDATE importproccess SET processLines = {$adv} WHERE idImportproccess = {$this->idProccess}");
		}
		catch (\Exception $e) {
			$this->log->log('Error incrementando avance de proceso: [' . $e . ']');
		}
	}
	
	/**
	 * Graba un buffer de registros
	 * @param array $buffer
	 */
	protected function saveRecordBuffer($buffer)
	{
		$values = implode(',', $buffer);
		$tabletmp = "INSERT INTO $this->tablename (idArray, email, idEmail, name, lastName) VALUES {$values}";
		$this->db->execute($tabletmp);
	}
	
	/**
	 * Limpia los registros insertados y los marca
	 * 
	 * @param int $idAccount
	 * @param int $idDbase
	 */
	protected function cleanInsertedRecords($idAccount, $idDbase)
	{
		$hora = time();
		// Marcar emails bloqueados en la tabla temporal (para no importar contactos nuevos)
		$findidemailblocked = "UPDATE {$this->tablename} t "
							. "   JOIN email e ON (t.idEmail = e.idEmail) "
							. "SET t.blocked = 1 "
							. "WHERE t.idEmail IS NOT NULL "
							. "   AND e.blocked > 0 "
							. "   AND e.idAccount = {$idAccount}";

		// Marcar ID de contacto en la tabla temporal para contactos que ya 
		// estan en la base de datos (primera vuelta)
		$findidcontactinDB =  "UPDATE {$this->tablename} t "
							. "   JOIN contact c ON (t.idEmail = c.idEmail) "
							. "SET t.idContact = c.idContact, "
							. "   t.dbase = 1 "
							. "WHERE t.idEmail IS NOT NULL "
							. "   AND c.idDbase = {$idDbase}";
		// Insertar contactos nuevos (ID nulo y no bloqueados)
		$createcontacts    =  "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) "
							. "    SELECT {$idDbase}, t.idEmail, t.name, t.lastName, {$hora}, 0, 0, 0, {$this->ipaddress}, {$this->ipaddress}, {$hora}, {$hora}, {$hora} "
							. "    FROM {$this->tablename} t "
							. "    WHERE t.idContact IS NULL "
							. "        AND t.blocked IS NULL;";
		// Marcar los registros que se insertaron como nuevos contactos
		// idcontact es nulo y no estan bloqueados
		$newcontact		   =  "UPDATE $this->tablename "
							. "SET new = 1 "
							. "WHERE idContact IS NULL "
							. "    AND blocked IS NULL";
		// Marcar ID de contacto en la tabla temporal para contactos que ya
		// estan en la base de datos (segunda vuelta)
		$findidcontact     =  "UPDATE {$this->tablename} t "
							. "   JOIN contact c ON (t.idEmail = c.idEmail) "
							. "SET t.idContact = c.idContact "
							. "WHERE t.idEmail IS NOT NULL "
							. "    AND c.idDbase = {$idDbase}";
		// Marcar en la tabla temporal los que ya estan en la lista de contactos
		$findcoxcl        =   "UPDATE {$this->tablename} t "
							. "    JOIN coxcl x ON (t.idContact = x.idContact) "
							. "SET t.coxcl = 1 "
							. "WHERE t.idContact IS NOT NULL "
							. "    AND x.idContactlist = {$this->idContactlist}";
		// Insertar los contactos de la tabla temporal que aun no estan en la
		// lista
		$createcoxcl	   =  "INSERT INTO coxcl (idContactlist, idContact, createdon) "
							. "    SELECT {$this->idContactlist}, t.idContact, {$hora} "
							. "    FROM {$this->tablename} t "
							. "    WHERE t.coxcl IS NULL "
							. "        AND t.blocked IS NULL";
		// Marcar los registros que se adicionaran a la lista
		// coxcl es nulo (no estan en la lista) y no estan bloqueados
		$status			   =  "UPDATE $this->tablename "
							. "SET status = 1 "
							. "WHERE coxcl IS NULL "
							. "    AND blocked IS NULL;";
		
//		$this->db->begin();
		
		$this->db->execute($findidemailblocked);
		$this->db->execute($findidcontactinDB);
		$this->db->execute($createcontacts);
		$this->db->execute($newcontact);
		$this->db->execute($findidcontact);
		$this->db->execute($findcoxcl);
		$this->db->execute($createcoxcl);
		$this->db->execute($status);
		
//		$this->db->commit();
		
	}

	
	protected function createFieldInstances ($customfields, $idDbase) 
	{
		$values = "";
		$line = 0;
		
		$DateCF = "SELECT idCustomField FROM customfield WHERE type = 'DATE' AND idDbase = $idDbase;";
		$idcontactsfromtmp = "SELECT t.idArray, t.idContact FROM $this->tablename t WHERE t.idContact IS NOT NULL AND t.new IS NOT NULL;";
		
		$idscontactwithcf = $this->db->fetchAll($idcontactsfromtmp);
		$idsDateCF = $this->db->fetchAll($DateCF);
		
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
						$value = $this->db->escapeString($cf[2]);
						$values.= "($cf[1], $idtoContact, $value, NULL)";
					}
					
					$line++;
				}
				
				if ((!empty($values)) && $line == 100) {
					$createFieldinstances = "INSERT INTO fieldinstance (idCustomField, idContact, textValue, numberValue) VALUES ".$values.";";
					$this->db->execute($createFieldinstances);
					$line = 0;
					$values = "";
				}
			}
		}
		
		if(!empty($values) && $line != 0){
			$createFieldinstances = "INSERT INTO fieldinstance (idCustomField, idContact, textValue, numberValue) VALUES ".$values.";";
			$this->db->execute($createFieldinstances);
		}
	}

	protected function runReports($limit) {
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
								INTO OUTFILE  '/tmp/{$nameNimported}'
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\n'";

			$this->db->execute($queryForErrors);							

			if(!empty($this->errors)) {
				$fp = fopen($filesPath . $nameNimported, 'a');

				foreach ($this->errors as $error) {
					$tmp = explode(",", $error);
					fputcsv($fp, $tmp);
				}

				fclose($fp);
			}
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
								INTO OUTFILE  '/tmp/{$nameImported}'
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\n'";

			$this->db->execute($queryForSuccess);
		}
		$queryBloqued = "SELECT COUNT(*) AS 'bloqueados' FROM {$this->tablename} WHERE blocked = 1 AND status IS NULL";
		$queryExist = "SELECT COUNT(*)	AS 'existentes' FROM {$this->tablename} WHERE status IS NULL AND coxcl = 1 AND blocked IS NULL";
		
		$bloquedCount = $this->db->fetchAll($queryBloqued);
		$existCount = $this->db->fetchAll($queryExist);
		
		$bloqued = $bloquedCount[0]['bloqueados'];
		$exist = $existCount[0]['existentes'];
		
		$queryInfo = "UPDATE importproccess SET exist = $exist, invalid = {$this->invalid}, bloqued = $bloqued, limitcontact = $limit, repeated = $this->repeated WHERE idImportproccess = $this->idProccess";
		$this->db->execute($queryInfo);
		
		$proccess = Importproccess::findFirstByIdImportproccess($this->idProccess);
		
		$proccess->errorFile = $saveFileError->idImportfile;
		$proccess->successFile = $saveFileSuccess->idImportfile;
		$proccess->status = "Finalizado";
		
		if(!$proccess->save()) {
			throw new \InvalidArgumentException('No se pudo actualizar el estado del proceso');
		}
	}
}
