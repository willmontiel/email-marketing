<?php

/**
 * @RoutePrefix("/api")
 */
class ApiController extends ControllerBase
{
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/fields")
	 */
	public function listcustomfieldsAction($idDbase)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$lista = array();
		
		$fields = $db->customFields;
		
		if ($fields) {
			foreach ($fields as $field) {
				$elem = array();
				$elem['id'] = $field->idCustomField;
				$elem['name'] = $field->name;
				$elem['type'] = $field->type;
				$elem['required'] = ($field->required=='Si');
				$elem['values'] = $field->values;

				$lista[] = $elem;
			}
		}

		return $this->setJsonResponse(array('fields' => $lista) );
		
	}

	/**
	 * 
	 * @Get("/dbase/{idDbase:[0-9]+}/fields/{idCustomfield:[0-9]+}")
	 */
	public function getcustomfieldAction($idDbase, $idCustomfield)
	{
		$customfield = Customfield::findFirstByIdCustomField($idCustomfield);
		
		if (!$customfield || $customfield->dbase->idDbase != $idDbase || $customfield->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el campo');
		}
		
		$fielddata = array();
		
		$fielddata['id'] = $customfield->idCustomField;
		$fielddata['name'] = $customfield->name;
		$fielddata['type'] = $customfield->type;
		$fielddata['required'] = ($customfield->required=='Si');
		$fielddata['values'] = $customfield->values;

		return $this->setJsonResponse(array('field' => $fielddata) );	
	
	}
	
	
	protected function validFieldObject($contents)
	{
		$this->errortxt = array();
		$failed = false;
		if (!isset($contents->name)) {
			$this->errortxt[] = '"name" requerido';
			$failed = true;
		}
		if (!isset($contents->type)) {
			$this->errortxt[] = '"type" requerido';
			$failed = true;
		}
		if (!isset($contents->required)) {
			$this->errortxt[] = '"required" requerido';
			$failed = true;
		}
		if (!isset($contents->values)) {
			$contents->values = '';
		}
		return !$failed;
	}


	/**
	 * 
	 * @Post("/dbase/{idDbase:[0-9]+}/fields")
	 */
	public function createcustomfieldAction($idDbase)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$log = $this->logger;
		
		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contents = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contents, true) . ']');
		
		// Validar campos
		$contents = $contents->field;
		if (!$this->validFieldObject($contents)) {
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Campos invalidos: ' . implode(';', $this->errortxt));
		}

		// Validar que no exista otro campo en la base de datos con el mismo nombre
		$otro = Customfield::count( 'idDbase = ' . $db->idDbase . ' AND name = "' . $contents->name . '"' );
		
		if ($otro > 0) {
			$log->log('Encontrado:' . $otro);
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'El campo ya existe');
		}
		
		// Insertar el objeto en la base de datos
		$customfield = new Customfield();
		$customfield->dbase = $db;
		$customfield->name = $contents->name;
		$customfield->type = $contents->type;
		$customfield->required = ($contents->required)?'Si':'No';
		$customfield->values = $contents->values;
		
		if (!$customfield->save()) {
			foreach ($customfield->getMessages() as $message) {
				$log->log('Error grabando Customfield: [' . $message . ']');
			}
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Error grabando informacion');
		}

		$fielddata = array();
		
		$fielddata['id'] = $customfield->idCustomField;
		$fielddata['name'] = $customfield->name;
		$fielddata['type'] = $customfield->type;
		$fielddata['required'] = ($customfield->required=='Si');
		$fielddata['values'] = $customfield->values;

		return $this->setJsonResponse(array('field' => $fielddata), 201, 'Success');	
	
	}
	
	/**
	 * 
	 * @Put("/dbase/{idDbase:[0-9]+}/fields/{idCustomfield:[0-9]+}")
	 */
	public function updatecustomfieldAction($idDbase, $idCustomfield)
	{
		
		$customfield = Customfield::findFirstByIdCustomField($idCustomfield);

		if (!$customfield || $customfield->dbase->idDbase != $idDbase || $customfield->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el campo');
		}
		$db = $customfield->dbase;

		$log = $this->logger;
		
		$contentsraw = $this->request->getRawBody();

		$log->log('Got this: [' . $contentsraw . ']');
		$contents = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contents, true) . ']');
		
		// Validar campos
		$contents = $contents->field;
		if (!$this->validFieldObject($contents)) {
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Campos invalidos: ' . implode(';', $this->errortxt));
		}

		// Validar que no exista otro campo en la base de datos con el mismo nombre
		$otro = Customfield::count( 'idDbase = ' . $db->idDbase . ' AND name = "' . $contents->name . '" AND idCustomField != ' . $idCustomfield );
		
		if ($otro > 0) {
			$log->log('Encontrado:' . $otro);
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'El nombre de campo ya existe en la base de datos');
		}
		
		// Actualizar el objeto en la base de datos
		$customfield->name = $contents->name;
		$customfield->type = $contents->type;
		$customfield->required = ($contents->required)?'Si':'No';
		$customfield->values = $contents->values;
		
		if (!$customfield->save()) {
			foreach ($customfield->getMessages() as $message) {
				$log->log('Error grabando Customfield: [' . $message . ']');
			}
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Error grabando informacion');
		}

		$fielddata = array();
		
		$fielddata['id'] = $customfield->idCustomField;
		$fielddata['name'] = $customfield->name;
		$fielddata['type'] = $customfield->type;
		$fielddata['required'] = ($customfield->required=='Si');
		$fielddata['values'] = $customfield->values;

		return $this->setJsonResponse(array('field' => $fielddata), 201, 'Success');	
	
	}

	/**
	 * 
	 * @Route("/dbase/{idDbase:[0-9]+}/fields/{idCustomfield:[0-9]+}", methods="DELETE")
	 */
	public function delcustomfieldAction($idDbase, $idCustomfield)
	{
		
		$customfield = Customfield::findFirstByIdCustomField($idCustomfield);

		if (!$customfield || $customfield->dbase->idDbase != $idDbase || $customfield->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el campo');
		}
		
		// Eliminar el campo
		$customfield->delete();
		
		return $this->setJsonResponse(array('status' => 'success'), 201, 'Success');	
	
	}

}
