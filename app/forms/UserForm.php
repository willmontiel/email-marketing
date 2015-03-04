<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element;

class UserForm extends Form
{
    public function initialize($user, $thuser)
    {
        $this->add(new EmailElement('email', array(
			'maxlength' => 80,
			'type' => 'email',
			'required' => 'required',
			'class' => 'form-control'
        )));
		
        $this->add(new Text('firstName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required',
			'class' => 'form-control'
        )));
		
        $this->add(new Text ('lastName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required',
			'class' => 'form-control'
        )));
		
        $this->add(new Password ('password', array(
			'maxlength' => 40,
			'type' => 'text',
			'required' => 'required',
			'class' => 'form-control'
        )));
		
        $this->add(new Password ('password2', array(
			'maxlength' => 40,
			'type' => 'text',
			'required' => 'required',
			'class' => 'form-control'
        )));
		
        $this->add(new Text ('username', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'id' => 'user',
			'class' => 'form-control'
        )));
				
//        $this->add(new Select("userrole", array(
//            'ROLE_ADMIN' => 'Administrador de la cuenta',
//			'ROLE_USER' => 'Usuario estándar',
//			'ROLE_STATISTICS' => 'Usuario de estadisticas',
//			'ROLE_MAIL_SERVICES' => 'Servicios de correo',
//			'ROLE_TEMPLATE' => 'Servicios de plantillas',
//			), array(
//			'class' => 'form-control'
//		)));
		
//		$this->add(new Select("userrole2", array(
//			'ROLE_SUDO' => 'Super administrador',
//            'ROLE_ADMIN' => 'Administrador local',
//			'ROLE_STATISTICS' => 'Administrador local',
//			'ROLE_USER' => 'Usuario estándar',
//			'ROLE_WEB_SERVICES' => 'Servicios web',
//			'ROLE_MAIL_SERVICES' => 'Servicios de correo',
//			'ROLE_TEMPLATE' => 'Servicios de plantillas',
//			), array(
//			'class' => 'form-control'
//        )));
		
		$roles = Role::find();
		$r = array();
		if ($thuser->userrole == 'ROLE_SUDO') {
			foreach ($roles as $rol) {
				$r[$rol->name] = $rol->name;
			}
		}
		else {
			foreach ($roles as $rol) {
				if ($rol->name != 'ROLE_SUDO') {
					$r[$rol->name] = $rol->name; 
				}
			}
		}
		
		$this->add(new Select('userrole', 
			$r, 
			array(
				'required' => 'required',
				'class' => 'select2 form-control'
			)
		));
		
		$this->add(new Password ('passForEdit', array(
			'maxlength' => 40,
			'type' => 'text',
			'class' => 'form-control',
//			'required' => 'required'
        )));
		
		$this->add(new Password ('pass2ForEdit', array(
			'maxlength' => 40,
			'type' => 'text',
			'class' => 'form-control',
//			'required' => 'required'
        )));
    }
}