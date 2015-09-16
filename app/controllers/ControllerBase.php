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
	//	if (isset($this->userObject)) {
			$this->user = $this->userObject;
	//	}
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
	public function verifyAcl($resource, $action)
	{
		$map = $this->cache->get('controllermap-cache');
		$acl = $this->cache->get('acl-cache');
		
		if (!isset($map[$resource .'::'. $action])) {
			return 0;
		}
		
		$reg = $map[$resource .'::'. $action];
		
		foreach($reg as $resources => $actions){
			foreach ($actions as $act) {
				if (!$acl->isAllowed($this->user->userrole, $resources, $act)) {
					$this->logger->log("no tiene permiso sobre ". $resource .",". $action);
					return 0;
				}
				$this->logger->log("validado sobre ". $resource .",". $action);
				return 1;
			}
		}
	}

	/**
	 * Llamar este metodo para enviar respuestas en modo JSON
	 * @param string $content
	 * @param int $status
	 * @param string $message
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function setJsonResponse($content, $status = 200, $message = '') 
	{
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
	
	protected function removePrefix($username)
	{
		$un = explode('_', $username);
		
		if (isset($un[1])) {
			return $un[1];
		}
		return $username;
	}
	
	public function getTargetFromMail($mail)
	{
		$t = json_decode($mail->target);
		$interpreter = new \EmailMarketing\General\Misc\InterpreterTarget();
		$interpreter->setData($t);
		$interpreter->modelData();
		$criteria = $interpreter->getCriteria();
		$tg = $interpreter->getNames();
		
		switch ($criteria) {
				case 'dbases':
					$target = "Base(s) de dato(s): {$tg}";
					break;
				
				case 'contactlists':
					$target = "Lista(s) de contacto(s): {$tg}";
					break;
			
				case 'segments':
					$target = "Segmento(s): {$tg}";
					break;
		}	
		
		return $target;
	}
	
	public function getPrefix($name)
	{
		$name = str_replace(' ', '', $name);
		$prefix = strtolower(substr($name, 0, 4));
		
		return $prefix;
	}
	
	public function beforeExecuteRoute($dispatcher)
	{
//		$this->timerObject->startTimer('controller', 'Controller/action [' . $dispatcher->getControllerName() . ':' . $dispatcher->getActionName() . ']');
	}
	
	public function afterExecuteRoute($dispatcher)
	{
//		$this->timerObject->endTimer('controller');
	}
	
	/**
	 * Lógica para rastros de auditoría
	 */
	
	/**
	 * 
	 * @param string $controller
	 * @param string $action
	 * @param int $date
	 * @param int $ip
	 */
	protected function traceSuccess($msg)
	{
		$controller = $this->dispatcher->getControllerName();
		$action = $this->dispatcher->getActionName();
		$date = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$operation = $controller . '::' .$action;
		
		AuditTrace::createAuditTrace($this->user, 'Success', $operation, $msg, $date, $ip);
	}
	
	
	protected function traceFail($msg)
	{
		$controller = $this->dispatcher->getControllerName();
		$action = $this->dispatcher->getActionName();
		$date = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$operation = $controller . '::' .$action;
		
		AuditTrace::createAuditTrace($this->user, 'Fail', $operation, $msg, $date, $ip);
	}
	
	/**
	 * Retorna el contenido POST de un Request desde 
	 * un objeto inyectado o directamente desde el request
	 */
	
	public function getRequestContent()
	{
		if($this->requestContent && isset($this->requestContent->content)) {
			return $this->requestContent->content;
		}
		else {
			return $this->request->getRawBody();
		}
	}
	
	public function getMessageResponse($status)
	{
		$obj = new stdClass();
		
		switch ($status) {
			case 200:
				$obj->type = "success";
				$obj->msg = "Solicitud resuelta exitosamente";
				$obj->status = "200";
				break;
			
			case 500:
				$obj->type = "error";
				$obj->msg = "Ha ocurrido un error mientras se resolvía la solicitud, contacte al administrador";
				$obj->status = "500";
				break;
			
			case 400:
				$obj->type = "error";
				$obj->msg = "Solicitud incorrecta";
				$obj->status = "400";
				break;
			
			case 404:
				$obj->type = "error";
				$obj->msg = "Recurso no encontrado";
				$obj->status = "404";
				break;
			
			default:
				$obj->type = "success";
				$obj->msg = "error";
				$obj->status = 500;
				break;
		}
		
		return $obj;
	}
}

