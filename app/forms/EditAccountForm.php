<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select;

class EditAccountForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('companyName'));
        $this->add(new Text ('fileSpace'));
        $this->add(new Text ('messageQuota'));
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