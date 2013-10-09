<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select;

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
		
		$this->add(new Text('fromEmail', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
		$this->add(new Text('replyTo', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
		$this->add(new Text('toWho', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required'
        )));
	}
}
