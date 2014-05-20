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
				$this->fieldnames[] = $idfield;
				$this->transformations[] = 'Date';
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