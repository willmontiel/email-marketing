<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element;

class UserForm extends Form
{
    public function initialize()
    {
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
			'required' => 'required',
			'id' => 'user'
        )));
		
        $this->add(new Select("userrole", array(
            'ROLE_ADMIN' => 'Administrador de la cuenta',
			'ROLE_USER' => 'Usuario estandár'
        )));
		
		$this->add(new Select("userrole2", array(
			'ROLE_SUDO' => 'Super administrador',
            'ROLE_ADMIN' => 'Administrador local'
        )));
		
		$this->add(new Password ('passForEdit', array(
			'maxlength' => 40,
			'type' => 'text'
        )));
		
		$this->add(new Password ('pass2ForEdit', array(
			'maxlength' => 40,
			'type' => 'text' 
        )));
    }
}