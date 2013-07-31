<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerBase
 *
 * @author Will
 */
class ControllerBase extends \Phalcon\Mvc\Controller
{
	public function initialize()
	{
		//Recuperar idAccount del usuario
		$this->user = $this->userObject;
	}

	/**
	 * Verifica que el usuario actual tenga permisos para ejecutar una accion
	 * de lo contrario lo redirecciona a un destino de error
	 * @param string $resource el nombre del recurso
	 * @param string $action la operacion que se desea validar sobre el recurso
	 * @param string $destination la url para redireccion
	 * @param string $message el mensaje, por defecto es "Operacion no permitida"
	 * @return \Phalcon\HTTP\ResponseInterface
	 */
	public function verifyAcl($resource, $action, $destination, $message='Operacion no permitida')
	{
		if (!$this->acl->isAllowed($this->user->userrole, $resource, $action)) {	
			$this->flashSession->error($message);
			return $this->response->redirect($destination);
		}
		return null;
	}
}

