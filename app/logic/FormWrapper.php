<?php

class FormWrapper extends BaseWrapper
{
	function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
	}

	public function setDbase(Dbase $dbase)
	{
		$this->dbase = $dbase;
	}
	
	public function saveInformation($content)
	{
		$form = new Form();
		$form->idDbase = $this->dbase->idDbase;
		$form->name = $content->name;
		$form->title = $content->title;
		$form->content = $content->content;
		$form->target = $content->listselected;
		$form->urlSuccess = $content->urlsuccess;
		$form->urlError = $content->urlerror;
		$form->optin = ($content->optin)?'Si':'No';
		$form->optinMail = $content->optinmail;
		$form->welcome = ($content->welcome)?'Si':'No';
		$form->welcomeMail = $content->welcomemail;
		$form->welcomeUrl = $content->welcomeurl;
		$form->notify = $content->notify;
		$form->notifyMail = $content->notifymail;
		
		if (!$form->save()) {
			foreach ($form->getMessages() as $message) {
				$this->logger->log('Error creando Formulario: [' . $message . ']');
			}
			throw new \Exception('Error grabando informacion de formulario');
		}
		
		return $this->fromPObjectToJObject($form);
		
	}
	
	public function updateFormContent(Form $form, $content)
	{
		$form->idDbase = $this->dbase->idDbase;
		$form->name = $content->name;
		$form->title = $content->title;
		$form->content = $content->content;
		$form->target = $content->listselected;
		$form->urlSuccess = $content->urlsuccess;
		$form->urlError = $content->urlerror;
		$form->optin = ($content->optin)?'Si':'No';
		$form->optinMail = $content->optinmail;
		$form->welcome = ($content->welcome)?'Si':'No';
		$form->welcomeMail = $content->welcomemail;
		$form->welcomeUrl = $content->welcomeurl;
		$form->notify = $content->notify;
		$form->notifyMail = $content->notifymail;
		
		if (!$form->save()) {
			foreach ($form->getMessages() as $message) {
				$this->logger->log('Error creando Formulario: [' . $message . ']');
			}
			throw new \Exception('Error grabando informacion de formulario');
		}
		
		return $this->fromPObjectToJObject($form);
	}
	
	public function fromPObjectToJObject($phObject)
	{
		$jsonObject = array();
		$jsonObject['id'] = $phObject->idForm;
		$jsonObject['name'] = $phObject->name;
		$jsonObject['title'] = $phObject->title;
		$jsonObject['content'] = $phObject->content;
		$jsonObject['listselected'] = $phObject->target;
		$jsonObject['urlsuccess'] = $phObject->urlSuccess;
		$jsonObject['urlerror'] = $phObject->urlError;
		$jsonObject['welcomeurl'] = $phObject->welcomeUrl;
		$jsonObject['optin'] = ($phObject->optin=='Si');
		$jsonObject['optinmail'] = $phObject->optinMail;
		$jsonObject['welcome'] = ($phObject->welcome=='Si');
		$jsonObject['welcomemail'] = $phObject->welcomeMail;
		$jsonObject['notify'] = $phObject->notify;
		$jsonObject['notifymail'] = $phObject->notifyMail;
		$jsonObject['framecode'] = $this->getFrameCode($phObject);
		
		return $jsonObject;
	}
	
	public function getFrameCode($form)
	{
		if(!$form->content) {
			return null;
		}
		
		$url = $this->urlObj->getBaseUri(TRUE) . 'form/frame/' . $form->idForm;
		
		return '<iframe src="' . $url . '"></iframe>';
	}
	
	public function getListsInJson(Dbase $dbase)
	{
		$listjson = array();
		
		foreach ($dbase->contactlist as $contactlist) {
			$listjson[] = $this->convertContactListToJson($contactlist);
		}
		
		return $listjson;
	}
	
	public function convertContactListToJson($contactlist)
	{
		$object = array();
		$object['id'] = $contactlist->idContactlist;
		$object['name'] = $contactlist->name;
		return $object;
	}
}
