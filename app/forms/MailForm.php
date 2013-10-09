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
			'maxlength' => 50,
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
			'rows' => 6,
			'type' => 'text',
			'id' => 'redactor_content'
        )));
	}
}
