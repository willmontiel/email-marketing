<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element,
	Phalcon\Forms\Element\TextArea;

class MailForm extends Form
{
    public function initialize()
    {
		$this->add(new Text('name', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus" 
        )));
		
		$this->add(new Text('subject', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
		$this->add(new Text('fromName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
		$this->add(new EmailElement('fromEmail', array(
			'maxlength' => 50,
			'type' => 'email',
			'required' => 'required' 
        )));
		
		$this->add(new EmailElement('replyTo', array(
			'maxlength' => 50,
			'type' => 'email'
        )));
		
		$this->add(new Text('toWho', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required'
        )));
		
		$this->add(new TextArea('content', array(
			'rows' => 20,
			'required' => 'required',
			'id' => 'redactor_content'
        )));
		
		$this->add(new Text('fbtitlecontent', array(
			'type' => 'text',
        )));
		
		$this->add(new TextArea('fbdescriptioncontent', array(
			'rows' => 2,
			'id' => 'fbdescriptioncontent'
        )));
		
		$this->add(new TextArea('fbmessagecontent', array(
			'rows' => 2,
			'id' => 'fbmessagecontent'
        )));
		
		$this->add(new TextArea('twpublicationcontent', array(
			'rows' => 2,
			'maxlength' => 140,
			'id' => 'twpublicationcontent'
        )));
	}
}
