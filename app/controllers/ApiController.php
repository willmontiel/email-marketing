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

		
		$list = $this->listcustomfields($idDbase);
		
		if ($list === false) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		return $this->setJsonResponse(array('fields' => $list));
	}
	
	/**
	 * 
	 * @Get("/fields")
	 */
	public function getcustomfieldsaliasAction()
	{
		$idDbase = $this->request->getQuery('dbase', null, 0);
		if ($idDbase != 0) {
			$list = $this->listcustomfields($idDbase);

			if ($list === false) {
				return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
			}

			return $this->setJsonResponse(array('fields' => $list));
		}
		
		return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos!!');
	}
	

	protected function listcustomfields($idDbase)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$db || $db->account != $this->user->account) {
			return false;
		}
		
		$lista = array();
		
		$fields = $db->customFields;
		
		if ($fields) {
			foreach ($fields as $field) {
				$lista[] = $this->fromPObjectToJObject($field);
			}
		}
		
		return $lista;
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
		$phObject->defaultValue = $jsonObject->defaultValue;
		
		$phObject->minValue = $jsonObject->minValue;
		$phObject->maxValue = $jsonObject->maxValue;
		$phObject->maxLength = $jsonObject->maxLength;
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
		$jsonObject['defaultValue'] = $phObject->defaultValue;
		$jsonObject['dbase'] = $phObject->idDbase;
		
		$jsonObject['minValue'] = $phObject->minValue;
		$jsonObject['maxValue'] = $phObject->maxValue;
		$jsonObject['maxLength'] = $phObject->maxLength;

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
		if (!isset($contents->name) || trim($contents->name) == '' || $contents->name == null) {
			$this->errortxt[] = '"Nombre" requerido';
			$failed = true;
		}
		if (!isset($contents->type)) {
			$this->errortxt[] = '"Tipo" requerido';
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
		$customfield->idDbase = $db->idDbase;
		$this->populatePObject($contents, $customfield);
		
		if (!$customfield->save()) {
			foreach ($customfield->getMessages() as $message) {
				$log->log('Error grabando Customfield: [' . $message . ']');
			}
			$this->traceFail("Error creating custom field USER: {$this->user->idUser}/{$this->user->username}");
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Error grabando informacion');
		}


		$fielddata = $this->fromPObjectToJObject($customfield);
		
		$this->traceSuccess("Create custom field, idCustomField: {$fielddata['id']}");
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
				$this->traceFail("Error editing customfield, idCustomfield: {$idDbase} / idDbase: {$idCustomfield}");
				$log->log('Error grabando Customfield: [' . $message . ']');
			}
			return $this->setJsonResponse(array('status' => 'failed'), 400, 'Error grabando informacion');
		}

		$fielddata = $this->fromPObjectToJObject($customfield);

		$this->traceSuccess("Edit custom field, idCustomfield: {$idDbase} / idDbase: {$idCustomfield}");
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
		
		try {
			foreach ($fieldinstances as $fieldinstance) {
				$fieldinstance->delete();
				$this->traceSuccess("Custom field instance deleted, idDbase: {$idDbase} / idCustomfield: {$idCustomfield} / idContact: {$fieldinstance->idContact}");
			}
			$response = $customfield->delete();

			$this->traceSuccess("Custom field deleted, idDbase: {$idDbase} / idCustomfield: {$idCustomfield}");
			return $this->setJsonResponse(array('field' => null), 202, 'field deleted success');
		}
		catch (Exception $e) {
			$this->traceFail("Error deleting customfield, idDbase: {$idDbase} / idCustomfield: {$idCustomfield}");
			$this->logger->log("Exception: error while deleting customfield: {$e}");
			return $this->setJsonResponse(array('status' => 'Ha ocurrido un error, contacte con el administrador'), 500);
		}
		
	}
	
//	/**
//	 * 
//	 * @Get("/dbase/{idDbase:[0-9]+}/contacts")
//	 */

//	public function listcontactsAction($idDbase)
//	{
//		$db = Dbase::findFirstByIdDbase($idDbase);
//		$limit = $this->request->getQuery('limit');
//		$page = $this->request->getQuery('page');
//
//		$pager = new PaginationDecorator();
//		if ($limit) {
//			$pager->setRowsPerPage($limit);
//		}
//		if ($page) {
//			$pager->setCurrentPage($page);
//		}
//
//		$wrapper = new ContactWrapper();
//		$wrapper->setAccount($this->user->account);
//		$wrapper->setIdDbase($idDbase);
//		$wrapper->setPager($pager);		
//
//		$valueSearch = $this->request->getQuery('email', null, null);
//
//		if($valueSearch == null) {
//			if (!$db || $db->account != $this->user->account) {
//				return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
//			}
//			$result = $wrapper->findContacts($db);
//			return $this->setJsonResponse($result);		
//		}
//		else {
//			$contacts = $wrapper->findContactsByValueSearch($valueSearch);
//			return $this->setJsonResponse($contacts);
//		}
//	}

	

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
			return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		}
		catch (\InvalidArgumentException $e) {
			return $this->setJsonResponse(array('errors' => $wrapper->getFieldErrors() ), 422, 'Error: ' . $e->getMessage());
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating new contact!');	
		}
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
//		$log->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$log->log('Contact: [' . print_r($contentsT, true) . ']');
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$mailhistory = new \EmailMarketing\General\ModelAccess\ContactMailHistory();
		$dateFormat = new \EmailMarketing\General\Misc\DateFormat();
		
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->user->account);
		$wrapper->setDateFormat($dateFormat);
		$wrapper->setContactMailHistory($mailhistory);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);


		// Editar el contacto existente

		if (!isset($contents->email) || trim($contents->email) == '') {
			return $this->setJsonResponse(array('errors' => array('email'=> array('El email es requerido'))), 422, 'Invalid data');	
		}
		try {
			$contact = $wrapper->updateContactFromJsonData($idContact, $contents);
			$contactdata = $wrapper->convertContactToJson($contact);
			return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while updating contact!');	
		}
	}

	

	 /**
	 * 
	 * @Route("/dbase/{idDbase:[0-9]+}/contacts/{idContact:[0-9]+}", methods="DELETE")
	 */

	public function deletecontactAction($idDbase, $idContact)
	{
		$contact = Contact::findFirst(array(
			"conditions" => "idContact = ?1",
			"bind" => array(1 => $idContact)
		));

		$db = Dbase::findFirst(array(
			"conditions" => "idDbase = ?1",
			"bind" => array(1 => $idDbase)
		));

		if (!$contact || $contact->dbase->idDbase != $db->idDbase || $contact->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el contacto');
		}

		// Eliminar el Contacto de la Base de Datos
		try {
			$override = ($this->user->userrole == 'ROLE_SUDO') ? TRUE : FALSE;
			$wrapper = new ContactWrapper();
			$result = $wrapper->deleteContactFromDB($contact, $db, $override);
		} catch(\Exception $e) {
			return $this->setJsonResponse(array('errors' => $e->getMessage()), 422, $e->getMessage());
		}
			
		return $this->setJsonResponse($result);
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
		$idDbase = $this->request->getQuery('dbase', null, null);
		
		try {
			$contactWrapper = new ContactListWrapper();
			$contactWrapper->setPager($pager);

			if($idDbase == 0) {
				$lists = $contactWrapper->findContactListByAccount($this->user->account, $name);
			}
			else {
				$lists = $contactWrapper->findContactListByIdDbase($this->user->account, $idDbase);
			}
			return $this->setJsonResponse($lists);
		}
		catch (Exception $e) {
			$this->logger->log("Exception {$e}");
			return $this->setJsonResponse(array('status' => 'failed'), 500, 'Internal error');
		}
		
	}
	/**
	 * 
	 * @Get("/lists/{idContactlist:[0-9]+}")
	 */
	public function getonelistAction($idContactlist)
	{
		
		$contactWrapper = new ContactListWrapper();
		
		$lists = $contactWrapper->findContactListInAccount($idContactlist, $this->user->account);
		
		return $this->setJsonResponse($lists);
		
	}
	
	/**
	 * 
	 * @Post("/lists")
	 */
	public function createcontactListAction()
	{
		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		
		$contents = $contentsT->list;
		
		$wrapper = new ContactListWrapper();
		$wrapper->setAccount($this->user->account);
		
		try {
			$lists = $wrapper->createContactlist($contents);
		}
		catch (InvalidArgumentException $e) {
			$this->traceFail("Error Create contact list, USER: {$this->user->idUser}/{$this->user->username}");
			return $this->setJsonResponse(array('errors' => $wrapper->getFieldErrors() ), 422, 'Error: ' . $e->getMessage());
		}
		catch (Exception $e) {
			$this->logger->log("Exception: {$e}");
			$this->traceFail("Error Create contact list, USER: {$this->user->idUser}/{$this->user->username}");
			return $this->setJsonResponse(array('errors' => array('generalerror' => 'Problemas al crear lista de contactos')), 422, 'Error: ' . $e->getMessage());
		}
		
		$list = $lists['list'];
		$this->traceSuccess("Create contact list, idContactlist: {$list['id']}, idDbase: {$list['dbase']}");
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
		try {
			$mensaje = $wrapper->updateContactList($contents, $idContactlist);
		}
		catch (InvalidArgumentException $e) {
			$this->traceFail("Error editing contactlist: {$idContactlist}, USER: {$this->user->idUser}/{$this->user->username}");
			return $this->setJsonResponse(array('errors' => $wrapper->getFieldErrors() ), 422, 'Error: ' . $e->getMessage());
		}
		catch (Exception $e) {
			$this->traceFail("Error editing contactlist: {$idContactlist}, USER: {$this->user->idUser}/{$this->user->username}");
			return $this->setJsonResponse(array('errors' => array('generalerror' => 'Problemas al actualizar lista de contactos')), 422, 'Error: ' . $e->getMessage());
		}
		
		$list = $mensaje['list'];
		$this->traceSuccess("Edit contact list, idContactlist:{$list['id']} / idDbase: {$list['dbase']}");
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

		$contacts = $wrapper->findContactsComplete($list);
//		$contacts = $wrapper->findContactsByList($list);
		// Sideload de la informacion de la lista
		$contacts['lists'] = array(ContactListWrapper::convertListToJson($list, $list->dbase->account));

		return $this->setJsonResponse($contacts);	
	
	}
	
	/**
	 * 
	 * @Get("/contactlist/{idContactlist:[0-9]+}/lists/{other}")
	 */	
	public function listbylistAction($idContactlist, $other)
	{
		
		$list = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
			
		if ($list->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la lista');
		}
		
		$contacts['list'] = array(ContactListWrapper::convertListToJson($list, $list->dbase->account));
	                
		return $this->setJsonResponse($contacts);	
	
	}

	
	/**
	 * 
	 * @Route("/lists/{idContactlist:[0-9]+}", methods="DELETE")
	 */
    public function deletecontactlistAction($idContactlist)
	{
		$log = $this->logger;
		
		$wrapper = new ContactListWrapper();
		
		try {
			if(!$wrapper->validateListBelongsToAccount($this->user->account, $idContactlist)) {
				return $this->setJsonResponse(null, 422, 'Lista invalida');
			}
			$wrapper->setUser($this->user);
			$deletedList = $wrapper->deleteContactList($idContactlist);	
		}
		catch (\InvalidArgumentException $e) {
			$this->traceFail("Error deleting contactlist: {$idContactlist}, USER: {$this->user->idUser}/{$this->user->username}");
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 422, "Error: {$e}");	
		}
		catch (\Exception $e) {
			$this->traceFail("Error deleting contactlist: {$idContactlist}, USER: {$this->user->idUser}/{$this->user->username}");
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('errors' => $e->getMessage()), 422, $e->getMessage());
		}
		$this->traceSuccess("contactlist deleted, idContactlist: {$idContactlist}");
		return $this->setJsonResponse(array('list' => null), 202, 'list deleted success');
	}
	/*Fin listas de contactos*/
	
	/**
	 * 
	 * @Get("/contactlist/{idContactlist:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */	
	public function getcontactbylistAction($idContactlist, $idContact)
	{
		
		$contact = Contact::findFirst(array(
			"conditions" => "idContact = ?1",
			"bind" => array(1 => $idContact)
		));
		
		$list = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
			
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
		$list = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
		
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$mailhistory = new \EmailMarketing\General\ModelAccess\ContactMailHistory();
		$dateFormat = new \EmailMarketing\General\Misc\DateFormat();
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->user->account);
		$wrapper->setContactMailHistory($mailhistory);
		$wrapper->setDateFormat($dateFormat);
		$wrapper->setIdDbase($list->idDbase);
		$wrapper->setIdContactlist($idContactlist);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Crear el nuevo contacto:
		if (!isset($contents->email) || trim($contents->email) == '') {
			return $this->setJsonResponse(array('errors' => array('email'=> array('El email es requerido'))), 422, 'Invalid data');	
		}
		try {
			// Si el email ya existe en la base de datos, pero no esta en la lista, entonces adicionarlo a la lista
			$contact = $wrapper->addExistingContactToListFromDbase($contents->email, $list);
			// Si no esta en la base de datos, adicionarlo a la BD
			if($contact == false) {
				$contact = $wrapper->createNewContactFromJsonData($contents, $list);
			}
		}
		catch (\InvalidArgumentException $e) {
			$this->traceFail("Error creating contact, USER: {$this->user->idUser}/{$this->user->username}");
			$log->log('Exception: [' . $e . ']');

			/*
			 * Inicialmente los errores se entregaban en un objeto así:
			 * { errors: { fieldA: ['Mensaje 1', 'Mensaje 2'] , fieldB: [ ... ] , ...  } }
			 * que es recibido por Ember y asignado al objeto del modelo...
			 * pero con el nuevo funcionamiento de PROMESAS (ver el mixin Ember.SaveHandlerMixin)
			 * lo convertimos en un gritter o en un App.errormessage
			 * así que debemos aplanar estos mensajes de error (es decir, un solo texto!)
			 */
			$errors = $wrapper->getFieldErrors();
			$errorstxt = '';
			foreach ($errors as $f => $l) {
				$errorstxt .= implode(',', $l);
			}
			return $this->setJsonResponse(array('errors' => $errorstxt), 422, 'Invalid data');	
		}
		catch (\Exception $e) {
			$this->traceFail("Error creating contact, USER: {$this->user->idUser}/{$this->user->username}");
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error', 'errors' => 'Error general. Contacte al administrador!'), 400, 'Error while creating new contact!');	
		}

		$contactdata = $wrapper->convertContactToJson($contact);
		
		$this->traceSuccess("Create contact, idContact: {$contactdata['id']}/ email: {$contactdata['email']}");
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
		$contentsT = json_decode($contentsraw);
		
		$log->log('Content: ' . print_r($contentsT, true));
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$mailhistory = new \EmailMarketing\General\ModelAccess\ContactMailHistory();
		$dateFormat = new \EmailMarketing\General\Misc\DateFormat();
		
		$wrapper = new ContactWrapper();

		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($list->idDbase);
		$wrapper->setDateFormat($dateFormat);
		$wrapper->setContactMailHistory($mailhistory);
		$wrapper->setIdContactlist($idContactlist);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Editar el contacto existente
		if (!isset($contents->email) || trim($contents->email) == '') {
			return $this->setJsonResponse(array('errors' => array('email'=> array('El email es requerido'))), 422, 'Invalid data');	
		}
		try {
			$contact = $wrapper->updateContactFromJsonData($idContact, $contents);
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			$this->traceFail("Error editing contact, idContact: {$idContact} / email: {$contentsT->email} / idContactlist: {$idContactlist}");
			$contact = Contact::findFirst($idContact);
			return $this->setJsonResponse(array('contact' => $wrapper->convertContactToJson($contact), 'errors' => $wrapper->getFieldErrors()), 422, 'Error: Invalid data');	
		}
		catch (\Exception $e) {
			$this->traceFail("Error editing contact, idContact: {$idContact} / email: {$contentsT->email} / idContactlist: {$idContactlist}");
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while updating contact!');	
		}

		$contactdata = $wrapper->convertContactToJson($contact);
		
//		$this->logger->log("Contact: " . print_r($contactdata, true));
		$this->traceSuccess("Edit contact, idContact: {$contactdata['id']} / email: {$contactdata['email']} / idContactlist: {$idContactlist}");
		return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
	}
        
        
    /**
	 * 
	 * @Route("/contactlist/{idContactlist:[0-9]+}/contacts/{idContact:[0-9]+}", methods="DELETE")
	 */
	public function deletecontactbylistAction($idContactlist, $idContact)
	{
		
		$contact = Contact::findFirst(array(
			"conditions" => "idContact = ?1",
			"bind" => array(1 => $idContact)
		));
		$list = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
		
		if (!$contact || $contact->dbase->idDbase != $list->idDbase || $contact->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el contacto');
		}
		
		// Eliminar el Contacto de la Lista
		try {
			$override = ($this->user->userrole == 'ROLE_SUDO') ? TRUE : FALSE;
			$wrapper = new ContactWrapper();
			$response = $wrapper->deleteContactFromList($contact, $list, $override);
		} 
		catch(\Exception $e) {
			$this->logger->log("Exception while deleting contact: {$e}");
			$this->traceFail("Error deleting contact, idContact: {$idContact} / idEmail: {$contact->idEmail} / idContactlist: {$idContactlist}");
			return $this->setJsonResponse(array('errors' => $e->getMessage()), 422, $e->getMessage());
		}
		
		$this->traceSuccess("Contact deleted, idContact: {$idContact} / idEmail: {$contact->idEmail} / idContactlist: {$idContactlist}");
		return $this->setJsonResponse(array ('contact' => $response), 202, 'contact deleted success');
	}

	/**
	 * 
	 * @Get("/dbases")
	 */
	public function dbaselistAction()
	{
		$dbs = array();
		foreach ($this->user->account->dbases as $db) {
			$dbs[] = array('id'=> $db->idDbase, 'name' => $db->name, 'color' => $db->color);
		}
		return $this->setJsonResponse(array('dbase' => $dbs));
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
		
		return $this->setJsonResponse($blockeds,  202, 'blockedemail catched success');
	}
	
	/**
	 * 
	 * @Post("/blockedemails")
	 */
	public function addemailtoblockedlistAction()
	{
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		$log->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		$contents = $contentsT->blockedemail;
		
		$blockedWrapper = new BlockedEmailWrapper();
		
		try {
			$blockedEmail = $blockedWrapper->addBlockedEmail($contents, $this->user->account);
		}
		
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			$this->traceFail("Error adding email to the blocked list, email: {$contents->email}");
			return $this->setJsonResponse(array('status' => 'error'), 422, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$this->traceFail("Error adding email to the blocked list, email: {$contents->email}");
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while blocking email!');	
		}
		
		$this->traceSuccess("Add email to the blocked list, email: {$contents->email}");
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
			$response = $wrapper->removeEmailFromBlockedList($this->user->account, $idBlockedemail);
			$this->traceSuccess("Email removed from the blocked list, email: {$response['email']}");
			return $this->setJsonResponse(array('blockedemails' => $response), 202, 'email unblocked success');
		}
		catch (\InvalidArgumentException $e) {
			$log->log('InvalidArgumentException: [' . $e . ']');
			$this->traceFail("Error removing email from the blocked list, idBlockedemail: {$idBlockedemail}");
			return $this->setJsonResponse(array('status' => 'error'), 422, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			$this->traceFail("Error removing email from the blocked list, idBlockedemail: {$idBlockedemail}");
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while deleting blocked email!');	
		}
	}
	
	/*Finaliza todo lo que tiene que ver con listas de bloqueo globales*/
	
	/*Inicio de segmentos*/
	
	/**
	 * @Get("/segments")
	 */
	public function listsegmentsAction()
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
		
		$idDbase = $this->request->getQuery('dbase', null, null);
		
		$wrapper = new SegmentWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setPager($pager);
		
		try {
			$segment = $wrapper->findSegments($idDbase);
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->traceFail("Error creating segment, idDbase: {$idDbase}");
			return $this->setJsonResponse(array('errors' => $wrapper->getFieldErrors()), 422, 'Invalid data');
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->traceFail("Error creating segment, idDbase: {$idDbase}");
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating segment!');	
		}

		return $this->setJsonResponse($segment, 201, 'success');
	}
	
	/**
	 * @Get("/segment/{idSegment:[0-9]+}/contacts")
	 */
	public function listcontactsbysegmentAction($idSegment)
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
	
		$segment = Segment::findFirst(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $idSegment)
		));
		
		$segmentwrapper = new SegmentWrapper();
		
		$segmentwrapper->setPager($pager);
		
		$contacts = $segmentwrapper->findContactsInSegment($segment);

		return $this->setJsonResponse($contacts);
	}
	
	/**
	 * @Get("/segment/{idSegment:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */
	public function getcontactbysegmentAction($idSegment, $idContact)
	{
		$this->logger->log('contacto por segmento');
		$contact = Contact::findFirst(array(
			"conditions" => "idContact = ?1",
			"bind" => array(1 => $idContact)
		));
		
		$segment = Segment::findFirst(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $idSegment)
		));
			
		if (!$contact || !$segment) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el contacto');
		}
		
		$wrapper = new ContactWrapper();
        $wrapper->setIdDbase($contact->idDbase);
		
		$fielddata = $wrapper->convertContactToJson($contact);
		
		return $this->setJsonResponse(array('contact' => $fielddata) );	
	}
	
	/**
	 * @Post("/segments")
	 */
	public function createsegmentAction()
    {
		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		$contents = $contentsT->segment;
		$account = $this->user->account;
		
		$dbase = Dbase::findFirst(array(
			"conditions" => "idDbase = ?1 and idAccount = ?2",
			"bind" => array(1 => $contents->dbase,
							2 => $account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'error'), 404, 'Dbase not found!');
		}
		
		$segment = Segment::findFirst(array(
			"conditions" => "name = ?1 and idDbase = ?2",
			"bind" => array(1 => $contents->name,
							2 => $dbase->idDbase)
		));
		
		if ($segment) {
			return $this->setJsonResponse(array('errors' => array('segmentname' => 'Ya existe un segmento con el nombre enviado, por favor verifique la información')), 422, 'Invalid data!');
		}
		
		$wrapper = new SegmentWrapper();
		$wrapper->setAccount($account);
		
		try {
			$response = $wrapper->createSegment($contents);
			$this->traceSuccess("Create segment, idSegment:{$response['id']} / idDbase: {$response['dbase']}");
			return $this->setJsonResponse(array('segment' => $response), 201, 'Success');
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->traceFail("Error creating segment, idDbase: {$contents->dbase}");
			return $this->setJsonResponse(array('errors' => $wrapper->getFieldErrors()), 422, 'Invalid data');
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->traceFail("Error creating segment, idDbase: {$contents->dbase}");
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating segment!');	
		}
	}	
	
	/**
	 * 
	 * @Put("/segments/{idSegment:[0-9]+}")
	 */
	public function updatesegmentAction($idSegment)
	{
		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->segment;
		$account = $this->user->account;
		
		$dbase = Dbase::findFirst(array(
			"conditions" => "idDbase = ?1 AND idAccount = ?2",
			"bind" => array(1 => $contents->dbase,
							2 => $account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'error'), 404, 'Dbase not found!');
		}
		
		$segment = Segment::findFirst(array(
			"conditions" => "idSegment = ?1 AND idDbase = ?2",
			"bind" => array(1 => $idSegment,
							2 => $dbase->idDbase)
		));
		
		if (!$segment) {
			return $this->setJsonResponse(array('status' => 'error'), 404, 'Segment not found!');
		}
		
		$wrapper = new SegmentWrapper();
		$wrapper->setAccount($account);
		$wrapper->setSegment($segment);
		$wrapper->setDbase($dbase);
		
		try {
			$response = $wrapper->updateSegment($contents);
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log("InvalidArgumentException: error while updating segment: {$e}");
			$this->traceFail("Error updating segment, idSegment: {$idSegment}");
			return $this->setJsonResponse(array('errors' => $wrapper->getFieldErrors() ), 422, 'Error: ' . $e->getMessage());
		}
		catch (Exception $e) {
			$this->traceFail("Error updating segment, idSegment: {$idSegment}");
			$this->logger->log("Exception: error while updating segment: {$e}");
			return $this->setJsonResponse(array('errors' => array('generalerror' => 'Error while updating segment')), 422, 'Error: ' . $e->getMessage());
		}
		
		$this->traceSuccess("Edit segment, idSegment: {$idSegment}");
		return $this->setJsonResponse(array('segment' => $response), 201, 'Success');
		
	}


	/**
	 * @Route("/segments/{idSegment:[0-9]+}", methods="DELETE")
	 */
	public function deletesegmentAction($idSegment)
	{
		$log = $this->logger;

		$wrapper = new SegmentWrapper();
		
		try {
			$response = $wrapper->deleteSegment($this->user->account, $idSegment);
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			$this->traceFail("Error deleting segment, idSegment: {$idSegment}");
			return $this->setJsonResponse(array('status' => 'error'), 422, 'Error: ' . $e->getMessage());	
		}
		catch (\Exception $e) {
			$this->traceFail("Error deleting segment, idSegment: {$idSegment}");
			$log->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while deleting segment!');	
		}
		
		$this->traceSuccess("Segment deleted, idSegment: {$idSegment}");
		return $this->setJsonResponse(array('segment' => null), 202, 'segment deleted success');
	}
	
	/**
	 * 
	 * @Put("/segment/{idSegment:[0-9]+}/contacts/{idContact:[0-9]+}")
	 */
	public function updatecontactbysegmentAction($idSegment, $idContact)
	{
		$segment = Segment::findFirst(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $idSegment)
		));
		
        if (!$segment || $segment->dbase->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro el segmento');
		}
                
		$log = $this->logger;

		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		
		$this->logger->log('Contact: ' . print_r($contentsT, true));
		// Tomar el objeto dentro de la raiz
		$contents = $contentsT->contact;
		
		$dateFormat = new \EmailMarketing\General\Misc\DateFormat();
		$mailhistory = new \EmailMarketing\General\ModelAccess\ContactMailHistory();
		
		$wrapper = new ContactWrapper();

		$wrapper->setAccount($this->user->account);
		$wrapper->setDateFormat($dateFormat);
		$wrapper->setContactMailHistory($mailhistory);
		$wrapper->setIdDbase($segment->idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		// Editar el contacto existente
		if (!isset($contents->email) || trim($contents->email) == '') {
			return $this->setJsonResponse(array('errors' => array('email'=> array('El email es requerido'))), 422, 'Invalid data');	
		}
		try {
			$contact = $wrapper->updateContactFromJsonData($idContact, $contents);
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			$contact = Contact::findFirst($idContact);
			return $this->setJsonResponse(array('contact' => $wrapper->convertContactToJson($contact), 'errors' => $wrapper->getFieldErrors()), 422, 'Error: Invalid data');	
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
	 * @Get("/contacts")
	 */
	public function searchcontactAction()
	{
		$valueSearch = $this->request->getQuery('text', null, null);
		
		if($valueSearch == null) {
			$contacts = array();
			$total = array('total' => 0);
			return $this->setJsonResponse(array('contact' => $contacts, 'meta' => $total));
		}
		
		try {
			$wrapper = new ContactWrapper();
			$wrapper->setAccount($this->user->account);
			return $this->setJsonResponse($wrapper->findContactByAnyValue($valueSearch));
		}
		catch (Exception $e) {
			$this->logger->log('Exception: ' . $e);
			$contacts = array();
			return $this->setJsonResponse(array('contact' => $contacts));
		}
	}
}