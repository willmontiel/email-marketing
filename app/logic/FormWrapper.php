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
		
		$form->urlSuccess = (strpos($content->urlsuccess, "http://") === FALSE && strpos($content->urlsuccess, "https://") === FALSE ) ? 'http://' . $content->urlsuccess : $content->urlsuccess;
		$form->urlError = (strpos($content->urlerror, "http://") === FALSE && strpos($content->urlerror, "https://") === FALSE) ? 'http://' . $content->urlerror : $content->urlerror;
		
		$form->optin = ($content->optin)?'Si':'No';
		$form->optinMail = $content->optinmail;
		$form->welcome = ($content->welcome)?'Si':'No';
		$form->welcomeMail = $content->welcomemail;

		$form->welcomeUrl = (!empty($content->welcomeurl) && strpos($content->welcomeurl, "http://") === FALSE && strpos($content->welcomeurl, "https://") === FALSE ) ? 'http://' . $content->welcomeurl : $content->welcomeurl;
		
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

		$form->urlSuccess = (strpos($content->urlsuccess, "http://") === FALSE && strpos($content->urlsuccess, "https://") === FALSE ) ? 'http://' . $content->urlsuccess : $content->urlsuccess;
		$form->urlError = (strpos($content->urlerror, "http://") === FALSE && strpos($content->urlerror, "https://") === FALSE) ? 'http://' . $content->urlerror : $content->urlerror;
		
		$form->optin = ($content->optin)?'Si':'No';
		$form->optinMail = $content->optinmail;
		$form->welcome = ($content->welcome)?'Si':'No';
		$form->welcomeMail = $content->welcomemail;
		
		$form->welcomeUrl = (!empty($content->welcomeurl) && strpos($content->welcomeurl, "http://") === FALSE && strpos($content->welcomeurl, "https://") === FALSE ) ? 'http://' . $content->welcomeurl : $content->welcomeurl;
		
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
		
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		$action = 'form/frame';
		$parameters = array(1, $form->idForm, $form->idDbase);
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		return '<iframe src="' . $link . '" style="height: ' . $this->getHeightForFrame(json_decode($form->content)) . 'px"></iframe>';
	}
	
	public function getHeightForFrame($fullcontent)
	{
		$content = $fullcontent->content;
		$total = 30;
		$total+= ( $fullcontent->title ) ? 40 : 5 ;
		foreach ($content as $cont){
			if(!$cont->hide) {
				switch ($cont->type) {
					case 'MultiSelect':
						$total+= 90;
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
