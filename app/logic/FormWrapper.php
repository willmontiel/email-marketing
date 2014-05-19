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
		Phalcon\DI::getDefault()->get('logger')->log(print_r($content, true));
		$form = new Form();
		$form->idDbase = $this->dbase->idDbase;
		$form->name = $content->name;
		$form->type = $content->type;
		$form->title = $content->title;
		$form->content = $content->content;
		$form->target = ($content->listselected) ? $content->listselected : 'none';
		
		$form->urlSuccess = (strpos($content->urlsuccess, "http://") === FALSE && strpos($content->urlsuccess, "https://") === FALSE ) ? 'http://' . $content->urlsuccess : $content->urlsuccess;
		$form->urlError = (strpos($content->urlerror, "http://") === FALSE && strpos($content->urlerror, "https://") === FALSE) ? 'http://' . $content->urlerror : $content->urlerror;
		
		$form->optin = ($content->optin)?'Si':'No';
		$form->optinMail = $content->optinmail;
		
		$form->notify = $content->notify?'Si':'No';
		$form->notifyMail = $content->notifymail;
		$form->notifyEmail = $content->notifyemail;
		
		if($content->type == 'Inscription') {
			$form->welcome = ($content->welcome)?'Si':'No';
			$form->welcomeMail = $content->welcomemail;
		}
		else {
			$form->welcome = ($content->updatenotify)?'Si':'No';
			$form->welcomeMail = $content->updatenotifymail;
		}
		
		$form->welcomeUrl = (!empty($content->welcomeurl) && strpos($content->welcomeurl, "http://") === FALSE && strpos($content->welcomeurl, "https://") === FALSE ) ? 'http://' . $content->welcomeurl : $content->welcomeurl;
		
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
		$form->type = $content->type;
		$form->title = $content->title;
		$form->content = $content->content;
		$form->target = ($content->listselected) ? $content->listselected : 'none';

		$form->urlSuccess = (strpos($content->urlsuccess, "http://") === FALSE && strpos($content->urlsuccess, "https://") === FALSE ) ? 'http://' . $content->urlsuccess : $content->urlsuccess;
		$form->urlError = (strpos($content->urlerror, "http://") === FALSE && strpos($content->urlerror, "https://") === FALSE) ? 'http://' . $content->urlerror : $content->urlerror;
		
		$form->optin = ($content->optin)?'Si':'No';
		$form->optinMail = $content->optinmail;

		$form->notify = $content->notify?'Si':'No';
		$form->notifyMail = $content->notifymail;
		$form->notifyEmail = $content->notifyemail;
		
		if($content->type == 'Inscription') {
			$form->welcome = ($content->welcome)?'Si':'No';
			$form->welcomeMail = $content->welcomemail;
		}
		else {
			$form->welcome = ($content->updatenotify)?'Si':'No';
			$form->welcomeMail = $content->updatenotifymail;
		}
		
		$form->welcomeUrl = (!empty($content->welcomeurl) && strpos($content->welcomeurl, "http://") === FALSE && strpos($content->welcomeurl, "https://") === FALSE ) ? 'http://' . $content->welcomeurl : $content->welcomeurl;

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
		$jsonObject['type'] = $phObject->type;
		$jsonObject['title'] = $phObject->title;
		$jsonObject['content'] = $phObject->content;
		$jsonObject['listselected'] = $phObject->target;
		$jsonObject['urlsuccess'] = $phObject->urlSuccess;
		$jsonObject['urlerror'] = $phObject->urlError;
		$jsonObject['welcomeurl'] = $phObject->welcomeUrl;
		$jsonObject['optin'] = ($phObject->optin=='Si');
		$jsonObject['optinmail'] = $phObject->optinMail;
		$jsonObject['notify'] = ($phObject->notify=='Si');
		$jsonObject['notifymail'] = $phObject->notifyMail;
		$jsonObject['notifyemail'] = $phObject->notifyEmail;
		
		if($phObject->type == 'Inscription'){
			$jsonObject['welcome'] = ($phObject->welcome=='Si');
			$jsonObject['welcomemail'] = $phObject->welcomeMail;
			$jsonObject['updatenotify'] = null;
			$jsonObject['updatenotifymail'] = null;
		}
		else {
			$jsonObject['welcome'] = null;
			$jsonObject['welcomemail'] = null;
			$jsonObject['updatenotify'] = ($phObject->welcome=='Si');
			$jsonObject['updatenotifymail'] = $phObject->welcomeMail;
		}
		
		$jsonObject['framecode'] = $this->getFrameCode($phObject);
		
		return $jsonObject;
	}
	
	public function getFrameCode($form)
	{
		if(!$form->content) {
			return null;
		}
		
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		if($form->type === 'Inscription') {
			$action = 'form/frame';
			$parameters = array(1, $form->idForm, $form->idDbase);
			$link = $linkdecoder->encodeLink($action, $parameters);

			return '<iframe src="' . $link . '" style="height: ' . $this->getHeightForFrame(json_decode($form->content)) . 'px"></iframe>';
		}
		
		return 'Finalizado';
	}
	
	public function getHeightForFrame($fullcontent)
	{
		$content = $fullcontent->content;
		$total = 30;
		$total+= ( $fullcontent->title ) ? 40 : 5 ;
		foreach ($content as $cont){
			if(!$cont->hide) {
				switch ($cont->type) {
					case 'Date':
						$total+= 200;
						break;
					default:
						$total+= 90;
						break;
				}
			}
		}
		
		$total+= 40;
		
		return $total;
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
