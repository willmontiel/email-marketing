<?php

namespace EmailMarketing\General\Misc;


class SelectedFieldsMapper
{
	protected $rawMap;
	protected $dbase;
	
	protected $fieldnames;
	protected $dbfields;
	protected $cfieldsmetadata;
	protected $cfieldsforinsert;
	protected $cfieldstransform;
	protected $transformations;
	protected $dateformat;
	protected $dateformated;

	public function __construct() 
	{
		$this->rawMap = null;
		$this->dbase = null;
	}
	
	/**
	 * Asigna la entrada de mapeo
	 * Un arreglo de "key" => "pos"
	 * Donde "key" es el nombre de un campo estandar (ej: email)
	 *	o es el ID de un campo personalizado (ej: 12)
	 * y "pos" es la columna del archivo (desde 0)
	 * @param array $map
	 * @throws InvalidArgumentException
	 */
	public function assignMapping($map)
	{
		if (!isset($map['email']) || $map['email'] == -1) {
			throw new \InvalidArgumentException('The "email" field is not present on the mapping, and it should!');
		}
		$this->rawMap = $map;
	}
	
	public function setDbase(\Dbase $dbase)
	{
		$this->dbase = $dbase;
	}
	
	public function setDateFormat($dateformat)
	{
		$this->dateformat = $dateformat;
	}
	
	public function processMapping()
	{
		if ($this->dbase == null || $this->rawMap == null) {
			throw new \InvalidArgumentException('Dbase and/or rawMap have not been set!');
		}

		// Cargar tipos para campos personalizados
		$this->determineDbaseFields();
		
		// Nombres de campos
		$this->fieldnames = array('email', 'domain');

		// Cambios de posicion de elementos
		$newmap = array(0 => $this->rawMap['email'], 1 => $this->rawMap['email']);
		$m = $this->rawMap;
		unset($m['email']);
		
		// Transformaciones
		$this->transformations  = array('email', 'domain');

		// Posicion donde debe moverse el nuevo campo
		$stposition = 2;
		
		// Recorrer la lista
		foreach ($m as $idfield => $position) {
			if ($position == null || $position == -1) {
				continue;
			}
			if (is_numeric($idfield)) {
				$name = $this->getCustomFieldName($idfield);
				if ($name) {
					$this->fieldnames[] = $name;
					$this->transformations[] = $this->cfieldstransform[$name];
					$newmap[$stposition] = $position;
					$stposition++;
				}
			}
			else if ($idfield == 'birthdate') {
				\Phalcon\DI::getDefault()->get('logger')->log("Es birthdate {$idfield}");
				$this->fieldnames[] = $idfield;
				$this->transformations[] = 'birthdate';
				$newmap[$stposition] = $position;
				$stposition++;
			}
			else {
				$this->fieldnames[] = $idfield;
				$this->transformations[] = 'Text';
				$newmap[$stposition] = $position;
				$stposition++;
			}
		}	
		
		$this->mapping = $newmap;
	}
	
	/**
	 * Retorna lista de campos adicionales, con nombre y tipo (SQL)
	 * para creacion en la base de datos
	 * @return array
	 */
	public function getAdditionalFields()
	{
		return $this->cfieldsmetadata;
	}

	public function getAdditionalFieldsForInsert()
	{
		return $this->cfieldsforinsert;
	}
	
	/**
	 * Retorna nombre de los campos
	 * @return array
	 */
	public function getFieldnames()
	{
		return $this->fieldnames;
	}

	public function mapValues($values)
	{
		$result = array();
		
		// Validar correo
		$email = $values[$this->mapping[0]];
		if (! \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			throw new \InvalidArgumentException("Invalid Email Address: [{$email}]");
		}
		
		foreach ($this->mapping as $to => $from) {
			if (isset($values[$from])) {
				$result[$to] = $this->transformValue($to, $values[$from]);
			}
		}
		return $result;
	}

	/* **************************************************************** */
	
	/*
	 * Transforma el valor de un campo de acuerdo a su regla
	 */
	protected function transformValue($i, $value)
	{
		if (isset($this->transformations[$i])) {
			switch ($this->transformations[$i]) {
				case 'Numerical':
					$result =  intval($value);
					break;
				
				case 'Date':
					try {
						$d = $this->getTimeStamp($value);
					
						if (!$d || $d->getTimestamp() < 0) {
							$d = new \DateTime('now');
							$d->setTime(0,0,0);
						}
						$result = ($d)?$d->getTimestamp():0;
					} 
					catch (Exception $ex) {
						$result = 0;
					}
					break;
					
				case 'birthdate':
					if (!$this->validateDate($value)) {
						$result = null;
					}
					else {
						if ($this->dateformat == 'Y-m-d') {
							$result = $value;
						}
						else {
							$result = $this->transformDateFormat($value, $this->dateformat);
						}
					}
					break;
					
				case 'email':
					$result = strtolower($value);
					break;
				
				case 'domain':
					$value = strtolower($value);
					list($u, $d) = explode('@', $value);
					$result = $d;
					break;
				
				default:
					$result = $value;
					break;
			}
	
			return $result;
		}
	
		return $value;
	}
	
	protected function getTimeStamp($date)
	{
		$formats = array(
			'Y-m-d H:i:s', 
			'Y-m-d', 
			'Y/m/d H:i:s', 
			'Y/m/d',
			'd-m-Y H:i:s',
			'd-m-Y',
			'd/m/Y H:i:s',
			'd/m/Y',
			'm-d-Y H:i:s',
			'm-d-Y',
			'm/d/Y H:i:s',
			'm/d/Y'
		);
		
		foreach ($formats as $format) {
			$d = \DateTime::createFromFormat($format, $date);
			if ($d) {
				return $d;
			}
		}
	}
	
	
	protected function transformDateFormat($date, $format) 
	{
		$separator = substr($format, 1, 1);
	
		$f = explode($separator, $format);
		$d = explode($separator, $date);
		
		if (count($d) != 0) {
			$year = $this->getPart($f, $d, 'Y');
			$month = $this->getPart($f, $d, 'm');
			$day = $this->getPart($f, $d, 'd');

			$newDate = "{$year}-{$month}-{$day}";
			return $newDate;
		}
		return null;
	}

	protected function getPart($format, $value, $criteria) 
	{
		if ($format[0] == $criteria) {
			$v = $value[0];
		}
		else if ($format[1] == $criteria) {
			$v = $value[1];
		}
		else if ($format[2] == $criteria) {
			$v = $value[2];
		}
		return $v;
	}
	
	
	protected function validateDate($date) 
	{
		$date = $this->transformDateFormat($date, $this->dateformat);
		
		$d = explode('-', $date);
		
		if (count($d) != 0) {
			if (checkdate($d[1],$d[2],$d[0])) {
				return true;
			}
			return false;
		}
		return false;
	}
	
	protected function getCustomFieldName($fieldid)
	{
		if (isset($this->dbfields[$fieldid])) {
			return 'cf_' . $fieldid;
		}
		return null;
	}
	
	protected function determineDbaseFields()
	{
		$this->dbfields = array();
		$this->cfieldsmetadata = array();
		$this->cfieldstransform = array();
		$this->cfieldsforinsert = array();
		
		$cfieldsdef = $this->dbase->customFields;
		
		foreach ($cfieldsdef as $f) {
			$cfid = $f->idCustomField;
			$this->dbfields[$cfid] = $f->type;
			switch ($f->type) {
				case 'Date':
					$t = ' INT(10) DEFAULT 0';
					$it = 'numberValue';
					break;
				case 'Numerical':
				default:
					$t = ' VARCHAR(100) DEFAULT NULL';
					$it = 'textValue';
					break;
			}

			if (isset($this->rawMap[$cfid]) && $this->rawMap[$cfid] !== null) {
				$cfname = $this->getCustomFieldName($cfid);
				$this->cfieldsmetadata[$cfname] = $t;
				$this->cfieldstransform[$cfname] = $f->type;
				$this->cfieldsforinsert[$cfname] = array($cfid, $it);
			}
			else {
				//var_dump($cfid);
				//echo "{$cfid} in dbase is not in the array: " . print_r($this->rawMap, true) . "!\n";
			}
		}
	}
}