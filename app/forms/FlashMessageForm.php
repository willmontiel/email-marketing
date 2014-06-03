<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element,
	Phalcon\Forms\Element\TextArea,
	Phalcon\Forms\Element\Check;

class FlashMessageForm extends Form
{
    public function initialize()
    {
		$this->add(new Text('name', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus",
			'class' => 'form-control',
			'id' => 'name'
        )));
		
		$this->add(new TextArea('message', array(
			'maxlength' => 1000,
			'type' => 'text',
			'rows' => 3,
			'required' => 'required' ,
			'class' => 'form-control',
			'id' => 'message'
        )));
		
		$this->add(new Select('accounts', Account::find(), array(
			'using' => array('idAccount', 'companyName'),
			'multiple' => 'multiple',
			'class' => 'form-control',
			'name' => 'accounts[]',
			'id' => 'accounts'
		)));
		
		$this->add(new Check('allAccounts', array(
			'value' => 'all',
			'id' => 'all'
        )));
		
		$this->add(new Check('certainAccounts', array(
			'value' => 'any',
			'id' => 'any'
        )));
		
		$this->add(new Select("type", array(
            'info' => 'Info',
            'warning' => 'Warning',
			'success' => 'Success',
			'danger' => 'Danger'
        )));
		
		$this->add(new Text('start', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'class' => 'add-on input-date-picker'
        )));
		
		$this->add(new Text('end', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'class' => 'add-on input-date-picker'
        )));
	}
}
