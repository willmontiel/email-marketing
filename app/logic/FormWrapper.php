<?php

class FormWrapper extends BaseWrapper
{
	function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger'); 
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
		$form->title = '';
		$form->content = '';
		$form->urlSuccess = $content->urlsuccess;
		$form->urlError = $content->urlerror;
		$form->urlWelcome = $content->urlwelcome;
		$form->optin = $content->optin;
		$form->optinMail = $content->optinmail;
		$form->welcome = $content->welcome;
		$form->welcomeMail = $content->welcomemail;
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
	
	public function saveFormContent(Form $form, $content)
	{
		$form->title = $content->title;
		$form->content = $content->content;
		
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
		$jsonObject['urlsuccess'] = $phObject->urlSuccess;
		$jsonObject['urlerror'] = $phObject->urlError;
		$jsonObject['urlwelcome'] = $phObject->urlWelcome;
		$jsonObject['optin'] = $phObject->optin;
		$jsonObject['optinmail'] = $phObject->optinMail;
		$jsonObject['welcome'] = $phObject->welcome;
		$jsonObject['welcomemail'] = $phObject->welcomeMail;
		$jsonObject['notify'] = $phObject->notify;
		$jsonObject['notifymail'] = $phObject->notifyMail;
		
		return $jsonObject;
	}
}
