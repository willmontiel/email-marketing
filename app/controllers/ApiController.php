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
				$lista[] = $this->fromPObjectToJObject($field);
			}
		}

		return $this->setJsonResponse(array('fields' => $lista) );
		
	}

	/**
	 * Graba la informacion de un StdObject con la informacion de 
	 * un campo persnalizado, dentro de un objeto del modelo
	 * @param \Object $jsonObject
	 * @param Customfield $phObject
	 */
	protected function populatePObject($jsonObject, $phObject)
	{
		$phObject->name = $jsonObject->name;
		$phObject->type = $jsonObject->type;
		$phObject->required = ($jsonObject->required)?'Si':'No';
		$phObject->values = $jsonObject->values;
		$phObject->defaultValue = $jsonObject->default_value;
		
		$phObject->minValue = $jsonObject->min_value;
		$phObject->maxValue = $jsonObject->max_value;
		$phObject->maxLength = $jsonObject->max_length;
	}

	/**
	 * Crea un arreglo con la informacion del objeto Customfield
	 * para que pueda ser convertido a JSON
	 * @param Customfield $phObject
	 * @return array
	 */
	protected function fromPObjectToJObject($phObject)
	{
		$jsonObject = array();
		$jsonObject['id'] = $phObject->idCustomField;
		$jsonObject['name'] = $phObject->name;
		$jsonObject['type'] = $phObject->type;
		$jsonObject['required'] = ($phObject->required=='Si');
		$jsonObject['values'] = $phObject->values;
		$jsonObject['default_value'] = $phObject->defaultValue;
		
		$jsonObject['min_value'] = $phObject->minValue;
		$jsonObject['max_value'] = $phObject->maxValue;
		$jsonObject['max_length'] = $phObject->maxLength;

		return $jsonObject;
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
		
		$fielddata = $this->fromPObjectToJObject($customfield);
		
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
		if (!isset($contents->default_value)) {
			$contents->default_value = '';
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
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Validar campos
		$contents = $contentsT->field;
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
		$this->populatePObject($contents, $customfield);
		
		if (!$customfield->save()) {
			foreach ($customfield->getMessages() as $message) {
				$log->log('Error grabando Customfield: [' . $message . ']');
			}
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Error grabando informacion');
		}


		$fielddata = $this->fromPObjectToJObject($customfield);

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
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Validar campos
		$contents = $contentsT->field;
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
		$this->populatePObject($contents, $customfield);
		
		if (!$customfield->save()) {
			foreach ($customfield->getMessages() as $message) {
				$log->log('Error grabando Customfield: [' . $message . ']');
			}
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Error grabando informacion');
		}

		$fielddata = $this->fromPObjectToJObject($customfield);

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
		$fieldinstances = Fieldinstance::findByIdCustomField($idCustomfield);
		
		foreach ($fieldinstances as $fieldinstance) {
			$fieldinstance->delete();
		}
		
		$customfield->delete();
		
		return $this->setJsonResponse(null);	
	
	}
		
	/**
	 * 
	 * @Get("/dbase/{idDbase:[0-9]+}/contacts")
	 */
	public function listcontactsAction($idDbase)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setPager($pager);		
		
		$valueSearch = $this->request->getQuery('email', null, null);
		
		if($valueSearch == null) {
			if (!$db || $db->account != $this->user->account) {
				return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
			}
			$result = $wrapper->findContacts($db);

			return $this->setJsonResponse($result);		
		}
		
		else {
			
			$contacts = $wrapper->findContactsByValueSearch($valueSearch);
			
			return $this->setJsonResponse($contacts);
		}
		
	}
	
	/**
	 * 
	 * @Get("/dbase/{idDbase:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */	
	public function getcontactAction($idDbase, $idContact)
	{
		
		$contact = Contact::findFirstByIdContact($idContact);
		
		if (!$contact || $contact->dbase->idDbase != $idDbase || $contact->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el contacto');
		}
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		
		
		$fielddata = $wrapper->convertContactToJson($contact);
		
		return $this->setJsonResponse(array('contact' => $fielddata) );	
	
	}
		
	/**
	 * 
	 * @Post("/dbase/{idDbase:[0-9]+}/contacts")
	 */
	public function createcontactAction($idDbase)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$log = $this->logger;

		/*
		 * Tomar el "payload" en formato JSON y convertirlo a un objeto de PHP (usando json_decode)
		 * Por convencion del RESTAdapter de EMBERJS, el objeto esta embebido dentro de un atributo
		 * "root" que tiene el mismo nombre que el tipo de objeto (modelo de Ember)
		 * En este caso:
		 * 
		 * { contact:
		 *		{ id: ___, email: _____, name: _____, last_name: _____ , ... }
		 * }
		 * 
		 * Los nombres de atributos se convierten a minusculas, y se adiciona un prefijo
		 * con "_" (underscore) a las letras mayusculas
		 * 
		 * Por ejemplo, si se define un modelo asi:
		 * App.Contact = DS.Model.extend({
		 *					email: DS.attr('string'),
		 *					lastName: DS.attr('string')
		 * });
		 * 
		 * 
		 * RESTAdapter lo va a transferir como un objeto JSON asi:
		 * 
		 * { "contact": { "email": "email@aqui.com", "last_name": "apellido aqui" } } 
		 * 
		 * NOTESE el cambio de lastName a last_name
		 * 
		 */
		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIdContactlist($contents->list_id);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Crear el nuevo contacto:
		try {
			$contact = $wrapper->createNewContactFromJsonData($contents);
			$contactdata = $wrapper->convertContactToJson($contact);
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('errors' => array('msg' => $e->getMessage())), 422, 'Invalid data');	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating new contact!');	
		}
		return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		
	}
		
	/**
	 * 
	 * @Put("/dbase/{idDbase:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */
	public function updatecontactAction($idDbase, $idContact)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$log = $this->logger;

		/*
		 * Tomar el "payload" en formato JSON y convertirlo a un objeto de PHP (usando json_decode)
		 * Por convencion del RESTAdapter de EMBERJS, el objeto esta embebido dentro de un atributo
		 * "root" que tiene el mismo nombre que el tipo de objeto (modelo de Ember)
		 * En este caso:
		 * 
		 * { contact:
		 *		{ id: ___, email: _____, name: _____, last_name: _____ , ... }
		 * }
		 * 
		 * Los nombres de atributos se convierten a minusculas, y se adiciona un prefijo
		 * con "_" (underscore) a las letras mayusculas
		 * 
		 * Por ejemplo, si se define un modelo asi:
		 * App.Contact = DS.Model.extend({
		 *					email: DS.attr('string'),
		 *					lastName: DS.attr('string')
		 * });
		 * 
		 * 
		 * RESTAdapter lo va a transferir como un objeto JSON asi:
		 * 
		 * { "contact": { "email": "email@aqui.com", "last_name": "apellido aqui" } } 
		 * 
		 * NOTESE el cambio de lastName a last_name
		 * 
		 */
		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Editar el contacto existente
		try {
			$contact = $wrapper->updateContactFromJsonData($idContact, $contents);
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while updating contact!');	
		}
		
		$contactdata = $wrapper->convertContactToJson($contact);

		return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		
	}
	
	 /**
	 * 
	 * @Route("/dbase/{idDbase:[0-9]+}/contacts/{idContact:[0-9]+}", methods="DELETE")
	 */
	public function deletecontactAction($idDbase, $idContact)
	{
		
		$contact = Contact::findFirstByIdContact($idContact);
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$contact || $contact->dbase->idDbase != $db->idDbase || $contact->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el campo');
		}
		
		// Eliminar el Contacto de la Base de Datos
		$wrapper = new ContactWrapper();
		
		$wrapper->deleteContactFromDB($contact, $db);
		
		return $this->setJsonResponse(null);	
	
	}
	
	/* Inicio de listas de contactos*/
	/**
	 * 
	 * @Get("/lists")
	 */
	public function getlistsAction()
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$name = $this->request->getQuery('name', null);
		
		$contactWrapper = new ContactListWrapper();
		$contactWrapper->setPager($pager);
		
		$lists = $contactWrapper->findContactListByAccount($this->user->account, $name);
		
		return $this->setJsonResponse($lists);
		
	}
	
	/**
	 * 
	 * @Post("/lists")
	 */
	public function contactlistsnewAction()
	{
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		
		$contents = $contentsT->list;
		
		$wrapper = new ContactListWrapper();
		$wrapper->setAccount($this->user->account);
		
		try {
			$lists = $wrapper->validateContactListData($contents);
		}
		catch (InvalidArgumentException $e) {
			return $this->setJsonResponse(array('errors' => array('name' => 'Nombre Incorrecto')), 422, 'Error: ' . $e->getMessage());
		}
		catch (Exception $e) {
			return $this->setJsonResponse(array('errors' => array('generalerror' => 'Problemas al crear lista de contactos')), 422, 'Error: ' . $e->getMessage());
		}
		
		return $this->setJsonResponse($lists);
	}
	
	/**
	 * 
	 * @Put("/lists/{idContactlist:[0-9]+}")
	 */
	public function listseditAction($idContactlist)
	{
		$log = $this->logger;
		
		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->list;
		
		$wrapper = new ContactListWrapper();
		$mensaje = $wrapper->updateContactList($contents, $idContactlist);
		
		return $this->setJsonResponse($mensaje);
	}
	
	
	/**
	 * 
	 * @Get("/contactlist/{idContactlist:[0-9]+}/contacts")
	 */	
	public function listcontactsbylistAction($idContactlist)
	{
		
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
			
		if ($list->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el contacto');
		}
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($list->idDbase);
		$wrapper->setPager($pager);
		$wrapper->setIdContactlist($idContactlist);
		
		$contacts = $wrapper->findContactsByList($list);
	                
		return $this->setJsonResponse($contacts);	
	
	}
	
	/**
	 * 
	 * @Route("/lists/{idContactlist:[0-9]+}", methods="DELETE")
	 */
    public function deletecontactlistAction($idContactlist)
	{
		$wrapper = new ContactListWrapper();
		$account = $this->user->account;
		$listExsit = $wrapper->validateListBelongsToAccount($account, $idContactlist);
		
		if($listExsit == false) {
			$status = "No se encontro la lista";
		}
		else {
			$status = $wrapper->deleteContactList($idContactlist);	
		}
		return $this->setJsonResponse($status);
	}
	/*Fin listas de contactos*/
	
	/**
	 * 
	 * @Get("/contactlist/{idContactlist:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */	
	public function getcontactbylistAction($idContactlist, $idContact)
	{
		
		$contact = Contact::findFirstByIdContact($idContact);
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
			
		if (!$contact || $contact->dbase->idDbase != $list->idDbase || $contact->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el contacto');
		}
		
		$wrapper = new ContactWrapper();
        $wrapper->setIdDbase($list->idDbase);
		
		$fielddata = $wrapper->convertContactToJson($contact);
		
		return $this->setJsonResponse(array('contact' => $fielddata) );	
	
	}
        
        
    /**
	 * 
	 * @Post("/contactlist/{idContactlist:[0-9]+}/contacts")
	 */
	public function createcontactbylistAction($idContactlist)
	{
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($list->idDbase);
		$wrapper->setIdContactlist($idContactlist);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Crear el nuevo contacto:

		try {
				$contact = $wrapper->searchContactinDbase($contents->email);
				if($contact == false) {
					$contact = $wrapper->createNewContactFromJsonData($contents);
				}
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('errors' => array('msg' => $e->getMessage())), 422, 'Invalid data');	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating new contact!');	
		}
		
		$contactdata = $wrapper->convertContactToJson($contact);

		return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		
	}
        
        
        
    /**
	 * 
	 * @Put("/contactlist/{idContactlist:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */
	public function updatecontactbylistAction($idContactlist, $idContact)
	{
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		
        if (!$list || $list->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la lista');
		}
                
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($list->idDbase);
		$wrapper->setIdContactlist($idContactlist);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Editar el contacto existente
		try {
			$contact = $wrapper->updateContactFromJsonData($idContact, $contents);
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while updating contact!');	
		}

		$contactdata = $wrapper->convertContactToJson($contact);

		return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		
	}
        
        
    /**
	 * 
	 * @Route("/contactlist/{idContactlist:[0-9]+}/contacts/{idContact:[0-9]+}", methods="DELETE")
	 */
	public function deletecontactbylistAction($idContactlist, $idContact)
	{
		
		$contact = Contact::findFirstByIdContact($idContact);
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		
		if (!$contact || $contact->dbase->idDbase != $list->idDbase || $contact->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el campo');
		}
		
		// Eliminar el Contacto de la Lista
		$wrapper = new ContactWrapper();
		
		$mensaje = $wrapper->deleteContactFromList($contact, $list);
		
		return $this->setJsonResponse($mensaje);	
	
	}

	/**
	 * 
	 * @Get("/dbases")
	 */
	public function dbaselistAction()
	{
		$dbs = array();
		foreach ($this->user->account->dbases as $db) {
			$dbs[] = array('id'=> $db->idDbase, 'name' => $db->name);
		}
		return $this->setJsonResponse(array('dbases' => $dbs));
	}
	
	//Inicio de listas globales de bloqueo de emails

	/**
	 * 
	 * @Get("/blockedemails")
	 */

	public function listblockedemailsAction()
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$blockedWrapper = new BlockedEmailWrapper();
		$blockedWrapper->setPager($pager);
		
		$blockeds = $blockedWrapper->findBlockedEmailList($this->user->account);
		
		return $this->setJsonResponse($blockeds);
	}
	
	/**
	 * 
	 * @Post("/blockedemails")
	 */
	public function addemailtoblockedlistAction()
	{
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		$contents = $contentsT->blockedemail;
		
		$blockedWrapper = new BlockedEmailWrapper();
		
		try {
			$blockedEmail = $blockedWrapper->addBlockedEmail($contents, $this->user->account);
		}
		
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 422, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while blocking email!');	
		}
		
		return $this->setJsonResponse(array('blockedemail' => $blockedEmail), 201, 'Success');
	}
	
	/**
	 * 
	 * @Route("/blockedemails/{idBlockedemail:[0-9]+}", methods="DELETE")
	 */
	public function removeemailfromblockedlistAction($idBlockedemail)
	{
		$log = $this->logger;
		
		$wrapper = new BlockedEmailWrapper();
		
		try {
			$wrapper->removeEmailFromBlockedList($this->user->account, $idBlockedemail);
		}
		
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 422, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while updating contact!');	
		}
		
	}
	
	/*Finaliza todo lo que tiene que ver con listas de bloqueo globales*/
}

	
