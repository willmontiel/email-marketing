<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\TextArea;

class EditForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('name', array(
        'maxlength' => 50,
		'type' => 'text',
		'required' => 'required',
		'autofocus' => "autofocus"
        )));
        $this->add(new TextArea('description', array(
        'maxlength' => 150,
		'type' => 'text',
		'required' => 'required'
        )));
        $this->add(new TextArea('Cdescription', array(
        'maxlength' => 150,
		'type' => 'text',
		'required' => 'required'
        )));
    }
}
