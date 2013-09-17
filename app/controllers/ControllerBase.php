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
	protected $_isJsonResponse = false;

	public function initialize()
	{
		$this->user = $this->userObject;
		//$this->user = User::findFirstByIdUser(3);
		
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
	public function verifyAcl($resource, $action, $message='Operacion no permitida')
	{
		if (!$this->acl->isAllowed($this->user->userrole, $resource, $action)) {	
			$this->flashSession->error($message);
			return $this->response->redirect('error/index');
		}
		return null;
	}
	
	

	/**
	 * Llamar este metodo para enviar respuestas en modo JSON
	 */
	public function setJsonResponse($content, $status = 200, $message = '') {
		$this->view->disable();

		$this->_isJsonResponse = true;
		$this->response->setContentType('application/json', 'UTF-8');
		
		if ($status != 200) {
			$this->response->setStatusCode($status, $message);
		}
		if (is_array($content)) {
			$content = json_encode($content);
		}
		$this->response->setContent($content);
		return $this->response;
	}
	
	
	public function beforeExecuteRoute($dispatcher)
	{
		$this->timerObject->startTimer('controller', 'Controller/action [' . $dispatcher->getControllerName() . ':' . $dispatcher->getActionName() . ']');
	}

	public function afterExecuteRoute($dispatcher)
	{
		$this->timerObject->endTimer('controller');
	}

}

