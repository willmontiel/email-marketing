<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element;

class AccountForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('companyName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus" 
        )));
		
		$this->add(new Text('virtualMta', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required',
        )));
		
        $this->add(new EmailElement('email', array(
			'maxlength' => 80,
			'type' => 'email',
			'required' => 'required'
        )));
		
        $this->add(new Text('firstName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required'
        )));
		
        $this->add(new Text ('lastName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required'
        )));
		
        $this->add(new Password ('password', array(
			'maxlength' => 40,
			'type' => 'text',
			'required' => 'required'
        )));
		
        $this->add(new Password ('password2', array(
			'maxlength' => 40,
			'type' => 'text',
			'required' => 'required' 
        )));
		
        $this->add(new Text ('username', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
        $this->add(new Text ('fileSpace', array(
			'maxlength' => 30,
			'type' => 'text',
			'required' => 'required' 
        )));
		
        $this->add(new Text ('messageLimit', array(
			'maxlength' => 30,
			'type' => 'text',
			'required' => 'required' 
        )));
		
		$this->add(new Text ('contactLimit', array(
			'maxlength' => 30,
			'type' => 'text',
			'required' => 'required' 
        )));
		
        $this->add(new Select("accountingMode", array(
            'Contacto' => 'Por Contacto',
            'Envio' => 'Envío',
        )));
        $this->add(new Select("subscriptionMode", array(
            'Prepago' => 'Prepago',
            'Pospago' => 'Pospago',
        )));
    }
}