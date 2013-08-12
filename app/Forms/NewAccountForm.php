<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element;

class NewAccountForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('companyName', array(
        'maxlength' => 50,
		'type' => 'text',
        'required' => 'required',
		'autofocus' => "autofocus" 
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
		
        $this->add(new Text ('messageQuota', array(
        'maxlength' => 30,
		'type' => 'text',
        'required' => 'required' 
        )));
		
        $this->add(new Select("modeUse", array(
            '1' => 'Por Contacto',
            '2' => 'Envio',
        )));
        $this->add(new Select("modeAccounting", array(
            '1' => 'Prepago',
            '2' => 'Postpago',
        )));
    }
}