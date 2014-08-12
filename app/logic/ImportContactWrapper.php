<?php
/**
 * 
 */

use EmailMarketing\General\Misc\SelectedFieldsMapper;

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
	
	protected $dateformat;
	/**
	 *
	 * @var SelectedFieldsMapper
	 */
	protected $fieldmapper;
	
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
//		$this->temporaryMode = true;
//		$this->debugMode = false;
		
		// Prueba temporalmente (borrar 2 lineas para produccion)
		$this->temporaryMode = false;
		$this->debugMode = true;

		$this->timer = Phalcon\DI::getDefault()->get('timerObject');
		$this->log = Phalcon\DI::getDefault()->get('logger');

	}

	protected function resetProcess()
	{
		$this->repeated = 0;
		$this->invalid = 0;
		$this->errors = array();
		$this->emailbuffers = array();
		$this->timer->reset();
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
	 * @param string $destiny
	 * @param string $dateformat
	 * @param string $delimiter
	 * @param boolean $header
         * @param string $importmode
	 * @throws \InvalidArgumentException
	 */
	public function startImport($fields, $destiny, $dateformat, $delimiter, $header, $importmode = 'normal') {
		try {
			ini_set('auto_detect_line_endings', '1');
			$mode = $this->account->accountingMode;

			$this->dateformat = $dateformat;

			$this->resetProcess();
			$this->timer->startTimer('all-import', 'Import process');

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
			$posCol = json_decode(json_encode($fields), true);

			// Cargar el proceso de importacion
			$this->loadProcess();

			// Crear el mapper
			$this->fieldmapper = new SelectedFieldsMapper;
			$this->log->log('Position fields for mapping object: [' . print_r($posCol, true) . ']');

			try {
				$this->fieldmapper->setDbase($dbase);
				$this->fieldmapper->assignMapping($posCol);
				$this->fieldmapper->setDateFormat($this->dateformat);
				$this->fieldmapper->processMapping();
			} catch (Exception $ex) {
				$this->updateProcessStatus('Importacion no realizada');
				$this->saveProcess();

				throw new \InvalidArgumentException("Error al crear objeto de mapeo de columnas: [{$ex->getMessage()}]");
			}

			$this->updateProcessStatus('En Ejecucion');
			$this->saveProcess();

			$this->timer->endTimer('phase1');

			$this->timer->startTimer('phase2', 'Create values to import!');

			$this->createTemporaryTable();

			// Creacion de contactos utilizando LOAD DATA INFILE
			// Preprocesando el archivo CSV
			$this->importDataFromCSV($destiny, $header, $delimiter, $activeContacts, $contactLimit, $mode, $dbase, $importmode);

			$this->timer->endTimer('phase2');

			$this->timer->startTimer('phase3', 'Update counters!');
                        /**
                         * =====================================================
                         * NOTA:
                         * Modifiqué el STORED PROCEDURE de actualizacion de 
                         * contadores de base de datos.
                         * Ahora actualiza los contadores de todas las listas
                         * de esa base de datos. Por lo tanto no es necesario
                         * realizar la actualización de la lista que antes
                         * se hacia
                         * =====================================================
                         */
			$dbase->updateCountersInDbase();
			$this->timer->endTimer('phase3');

			$this->destroyTemporaryTable();

			$swrapper = new SegmentWrapper;

			// Recrear los segmentos de esta base de datos para tener en cuenta los nuevos contactos importados
			$this->timer->startTimer('phase5', 'Recreate segments!');
			$swrapper->recreateSegmentsInDbase($dbase->idDbase);		
			$this->timer->endTimer('phase5');

			$this->timer->endTimer('all-import');
		}
		catch (\InvalidArgumentException $e) {
			$this->destroyTemporaryTable();
			$this->updateProcessStatus('Cancelado');
			$this->saveProcess();
		}
		catch (\Exception $e) {
			$this->destroyTemporaryTable();
			$this->updateProcessStatus('Cancelado');
			$this->saveProcess();
		}
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
	 * Cuenta el numero de lineas que tiene el archivo
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
	 * Si ya esta en el archivo, incrementa contador y graba error
	 * 
	 * Si alguno de los dos se cumple retorna null, de lo contrario
	 * retorna el email en minusculas
	 * 
	 * @param string $email
	 * @param int $line
	 * @return boolean
	 */
	protected function verifyEmailAddress($email, $line)
	{
		$email = strtolower($email);
		if (empty($this->emailbuffers) || !isset($this->emailbuffers[$email]) ) {
			$this->emailbuffers[$email] = true;
			return true;
		}
		else {
			// Email repetido en el archivo
			$this->errors[] = \sprintf('Correo [%s] repetido en linea %d', $email, $line);
			$this->repeated++;
			return false;
		}
	}

	/**
	 * Altera la base de datos
	 */
	protected function alterTemporaryTable()
	{
		// Campos adicionales:
		// [ fieldname => metadata, ... ]
		// Ej: [ 'cf_1' => ' VARCHAR(100) DEFAULT NULL' ]
		$fields = $this->fieldmapper->getAdditionalFields();
		$this->log->log('Additional fields: [' . print_r($fields, true) . ']');
		
		if (count($fields) > 0) {
			$af = implode(',', array_map(function ($k, $v) { return $k . $v; }, array_keys($fields), $fields));
			$alter = "ALTER TABLE {$this->tablename} ADD COLUMN ({$af})";
			$this->log->log('Alter: [' . $alter . ']');
			$this->db->execute($alter);
		}
	}
	
	/**
	 * 
	 * @param string $sourcefile
	 * @param boolean $hasHeader
	 * @param string $delimiter
	 * @param int $currentActiveContacts
	 * @param int $contactLimit
	 * @param string $mode
	 * @param Dbase $dbase
	 * @param string $importmode
	 * @throws \InvalidArgumentException
	 */
	protected function importDataFromCSV($sourcefile, $hasHeader, $delimiter, $activeContacts, $contactLimit, $mode, Dbase $dbase, $importmode)
	{
		// Cuantas lineas tiene el archivo?
		$linecount = $this->countFileRecords($sourcefile);

		$maxrows = ($hasHeader)?$linecount:$linecount+1;
		$notimported = 0;
		if ($mode == 'Contacto') {
			// Modo contactos, verificar que es menor, el numero de registros
			// del archivo, o el numero de contactos que se pueden insertar en
			// la cuenta
			$dif = $contactLimit - $activeContacts;
			$maxrows = ($dif < $maxrows)?$dif:$maxrows;
			$notimported = ($linecount>$maxrows)?($linecount - $maxrows):0;
		}
		$this->log->log("File rows: {$linecount}, maxrows: {$maxrows}");
		
		/*
		 * 
		 * Metodo mas simple:
		 * =================
		 * Leer linea por linea el archivo fuente
		 * Grabar en un archivo destino con los campos a importar (solamente)
		 * y con el orden correcto de importacion (ej: email,name,lastname,etc)
		 * Validar el email
		 * Extraer el dominio
		 * Esto hace mas sencillo armar la sentencia de carga... probar tiempo de ejecucion
		 * El orden de los campos sera:
		 * email,domain,name,lastname
		 * 
		 * Archivo temporal donde se guardaran los registros a importar
		 * con los campos que se importan y eliminando registros de
		 * email invalido
		 */

		$this->alterTemporaryTable();
		
		$tmpFilename = $sourcefile . '.pr';
		$this->timer->startTimer('copy-rows', 'Copy csv file to temporary file!');
		$this->updateProcessStatus('Preprocesando registros');
		$this->saveProcess();
		// Ejecutar la copia de registros
		$this->copyCSVRecordsToPR($sourcefile, $tmpFilename, $delimiter, $maxrows, $hasHeader);
		$this->timer->endTimer('copy-rows');

		
		// Numero de lineas que tiene ahora el archivo final
		// Esto elimina duplicados, invalidos, etc
		$flines = $this->countFileRecords($tmpFilename);
		$this->log->log("Temporary file rows: {$flines}");
		
		$this->timer->startTimer('load-rows', 'Load rows from temporary file into database!');
		// Crear sentencia SQL que hace la importacion de los registros desde el
		// archivo temporal
		$this->updateProcessStatus('Cargando registros en base de datos');
		$this->saveProcess();
		$rpath = realpath($tmpFilename);
		$fields = implode(',', $this->fieldmapper->getFieldnames() );
		$sql_db_mode = "SET session sql_mode=''";
		$importfile = "LOAD DATA INFILE '{$rpath}' INTO TABLE {$this->tablename} CHARACTER SET UTF8 FIELDS TERMINATED BY '{$delimiter}' OPTIONALLY ENCLOSED BY '\"'"
					. "({$fields})";
		$sql_db_mode_strict = "SET session sql_mode='strict_all_tables'";
		
		$this->log->log("SQL: {$importfile}");
		// Ejecutar sentencia SQL
		
		$this->db->execute($sql_db_mode);
		
		$this->db->execute($importfile);
		$this->timer->endTimer('load-rows');
		
		$this->db->execute($sql_db_mode_strict);
		
		$this->updateProcessStatus('Mapeando contactos');
		$this->saveProcess();

		$this->timer->startTimer('update-rows', 'Find or create emails in temporary table!');
		// Procesar todos los registros, asignando un idEmail
		$this->db->execute("UPDATE {$this->tablename} SET idEmail = find_or_create_email(email, domain, {$this->account->idAccount})");
		$this->timer->endTimer('update-rows');
		
		$this->timer->startTimer('clean-rows', 'Clean rows and insert contacts!');
		// Limpiar los registros e insertarlos
		$this->cleanInsertedRecords($this->account->idAccount, $dbase->idDbase);
		$this->timer->endTimer('clean-rows');
	
		// Actualizar/insertar campos personalizados
		$this->updateProcessStatus('Actualizando campos personalizados');
		$this->saveProcess();

                /*
                 * =============================================================
                 * Marcar contactos o emails dependiendo del modo de importacion
                 * seleccionado por el usuario:
                 * 'normal'         ==> no se hace nada adicional
                 * 'unsubscribed'   ==> se marcan como des-suscritos
                 * 'bounced'        ==> se marcan emails como rebotados
                 * 'inactive'       ==> se marcan como inactivos
                 * =============================================================
                 */
                
                switch ($importmode) {
                    case 'unsubscribed':
                        $this->timer->startTimer('unsubscribing', 'Unsubscribing contacts!');
                        $this->unsubscribeContacts();
        		$this->timer->endTimer('unsubscribing');
                        break;
                    case 'bounced':
                        $this->timer->startTimer('bouncing', 'Bouncing contacts\' emails!');
                        $this->bounceContacts();
        		$this->timer->endTimer('bouncing');
                        break;
                    case 'inactive':
                        $this->timer->startTimer('deactivating', 'Deactivating contacts!');
                        $this->deactivateContacts();
        		$this->timer->endTimer('deactivating');
                        break;
                    case 'normal':
                        // Nothing to be done
                        break;
                    default:
                        $this->log->log("ERROR: the importmode value is not valid: [{$importmode}]");
                        break;
                }
                
		$this->timer->startTimer('custom-fields', 'Insert Custom Fields!');
		$this->updateCustomFields();
		$this->timer->endTimer('custom-fields');
		
		// Reporte
		$this->timer->startTimer('report', 'Run reports!');
		$this->runReports($notimported);
		$this->timer->endTimer('report');
	}

	protected function updateCustomFields()
	{
		// Por cada campo personalizado
		foreach ($this->fieldmapper->getAdditionalFieldsForInsert() as $fn => $data) {
			$sql = "INSERT INTO fieldinstance (idCustomField, idContact, {$data[1]}) "
					. "SELECT {$data[0]}, idContact, {$fn} "
					. "FROM {$this->tablename} "
					. "WHERE idContact IS NOT NULL "
					. "ON DUPLICATE KEY UPDATE {$data[1]} = {$fn}";
			$this->log->log("Excuting SQL: [{$sql}]");
			$this->db->execute($sql);
		}
	}

	/**
	 * Metodo que ejecuta la copia linea a linea de los registros del archivo
	 * CSV a un CSV temporal, cambiando el orden de los campos, validando
	 * que las direcciones de email esten correctas, y eliminando duplicados
	 * y solo copiando los campos importantes.
	 * @param string $sourcefile
	 * @param string $tmpFilename
	 * @param string $delimiter
	 * @param int $maxrows
	 * @param boolean $hasHeader
	 */
	protected function copyCSVRecordsToPR($sourcefile, $tmpFilename, $delimiter, $maxrows, $hasHeader)
	{		
		$fp = fopen($sourcefile, 'r');
		$nfp = fopen($tmpFilename, 'w');
		
		$rows = 0;

		if ($hasHeader) {
			$line = fgetcsv($fp, 0, $delimiter);
		}
		// cada cierta cantidad de registros se debe informar avance
		$every = (int)($maxrows/10);
		$this->incrementProgress(0);
		while (!feof($fp) && $rows <= $maxrows) {
			$line = fgetcsv($fp, 0, $delimiter);
			$rows++;
			try {
				$lineOut = $this->fieldmapper->mapValues($line);
			}
			catch (\InvalidArgumentException $e) {
				$this->errors[] = \sprintf('%s en linea %d', $e->getMessage(), $rows);
				$this->invalid++;
				continue;
			}
			// Validar que el EMAIL no este repetido
			if ( $this->verifyEmailAddress($lineOut[0], $rows) ) {
//				fputcsv($nfp, $lineOut, $delimiter);
				try {
					$this->fputcsv2($nfp, $lineOut, $delimiter, '"', true);
				}
				catch (\Exception $e) {
					$this->errors[] = \sprintf('%s en linea %d', $e->getMessage(), $rows);
					$this->invalid++;
				}
			}
			if (! $rows % $every) {
				$this->incrementProgress($rows);
			}
		}
		fclose($fp);
		fclose($nfp);

		$this->incrementProgress($rows);
		$this->log->log("Copying data from [{$sourcefile}] to [{$tmpFilename}]. {$rows} rows processed!");
	}
	
	
	protected function fputcsv2 ($fh, array $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) { 
		$delimiter_esc = preg_quote($delimiter, '/'); 
		$enclosure_esc = preg_quote($enclosure, '/'); 

		$output = array(); 
		foreach ($fields as $field) { 
			if ($field === null && $mysql_null) { 
				$output[] = 'NULL'; 
				continue; 
			} 

			$output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? ( 
				$enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure 
			) : $field; 
		} 
		$line = join($delimiter, $output);
		
		$this->log->log("Line before: {$line}");
		
		$line = utf8_encode($line);
		
		$this->log->log("Line after: {$line}");
		
//		if (!mb_check_encoding($line, 'UTF-8')) {
//			if (mb_check_encoding($line, 'ISO-8859-1')) {
//				$line = mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1');
////				$line = utf8_encode($line);
//			}
//			else {
//				throw new \Exception('Codificacion invalida en texto');
//			}
//		}
		
		fwrite($fh, pack("CCC",0xef,0xbb,0xbf)); 
		fwrite($fh, $line . "\n"); 
	}
	
	protected function incrementProgress($adv)
	{
		try {
			$this->db->execute("UPDATE importproccess SET processLines = {$adv} WHERE idImportproccess = {$this->idProccess}");
		}
		catch (\Exception $e) {
			$this->log->log('Error incrementando avance de proceso: [' . $e . ']');
		}
	}

	/**
	 * Limpia los registros insertados y luego inserta los contactos
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
		$createcontacts    =  "INSERT INTO contact (idDbase, idEmail, name, lastName, birthDate, status, unsubscribed, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) "
							. "    SELECT {$idDbase}, t.idEmail, t.name, t.lastName, t.birthDate, {$hora}, 0, {$this->ipaddress}, {$this->ipaddress}, {$hora}, {$hora}, {$hora} "
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

        /**
         * Este metodo des-suscribe los contactos que han sido importados
         * y aquellos que ya existían en la base de datos y que fueron incluidos
         * dentro de la importación
         */
        protected function unsubscribeContacts()
        {
            $hora = time();
            
            $sql = "UPDATE contact c "
                    . "JOIN {$this->tablename} t ON (t.idContact = c.idContact) "
                . " SET c.unsubscribed = {$hora} "
                . " WHERE c.unsubscribed = 0";
            $this->db->execute($sql);
        }
        
        /**
         * Este metodo desactiva los contactos que han sido importados
         * y aquellos que ya existían en la base de datos y que fueron incluidos
         * dentro de la importación
         */
        protected function deactivateContacts()
        {
            $sql = "UPDATE contact c "
                    . "JOIN {$this->tablename} t ON (t.idContact = c.idContact) "
                . " SET c.status = 0 "
                . " WHERE c.status != 0";
            $this->db->execute($sql);
        }

        /**
         * Este metodo rebota las direcciones de correo de los contactos que han
         * sido importados y de aquellos que ya existían en la base de datos y 
         * que fueron incluidos dentro de la importación
         */
        protected function bounceContacts()
        {
            $hora = time();
            
            $sql = "UPDATE email e "
                    . "JOIN {$this->tablename} t ON (t.idEmail = e.idEmail) "
                . " SET e.bounced = {$hora} "
                . " WHERE e.bounced = 0";
            $this->db->execute($sql);
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
			// OK
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

			$this->db->execute($queryForErrors);							

			if(!empty($this->errors)) {
				$fp = fopen($filesPath . $nameNimported, 'a');

				foreach ($this->errors as $error) {
					$tmp = explode(",", $error);
//					fputcsv($fp, $tmp);
					$this->fputcsv2($fp, $tmp);
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
								INTO OUTFILE  '{$filesPath}{$nameImported}'
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\n'";

			$this->db->execute($queryForSuccess);
		}
		$queryBloqued = "SELECT COUNT(*) AS bloqueados FROM {$this->tablename} WHERE blocked = 1 AND status IS NULL";
		$queryExist = "SELECT COUNT(*)	AS existentes FROM {$this->tablename} WHERE status IS NULL AND coxcl = 1 AND blocked IS NULL";
		
		$bloquedCount = $this->db->fetchAll($queryBloqued);
		$existCount = $this->db->fetchAll($queryExist);
		
		$bloqued = $bloquedCount[0]['bloqueados'];
		$exist = $existCount[0]['existentes'];
		
		$queryInfo = "UPDATE importproccess SET exist = {$exist}, invalid = {$this->invalid}, bloqued = {$bloqued}, limitcontact = {$limit}, repeated = {$this->repeated} WHERE idImportproccess = {$this->idProccess}";
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
