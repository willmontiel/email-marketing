<?php
class ApikeyController extends ControllerBase
{
	public function indexAction()
	{
		$idAccount = $this->user->account->idAccount;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => User::find("idAccount = $idAccount"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function createAction($idUser)
	{
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 ",
			"bind" => array(1 => $idUser)
		));
		
		if($user->idAccount == $this->user->idAccount) {
			
			try {
				$obj = new ApiKeyObj();
				$obj->setUser($user);

				if($user->apikey) {
					throw new InvalidArgumentException('Ya existe una API Key para este usuario');
				}
				else {
					$key = $obj->createAPIKey();
				}

				return $this->setJsonResponse(array('APIKey' => $key), 200, 'Se ha creado la API Key exitosamente');
			}
			catch (\Exception $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
			catch(\InvalidArgumentException $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
		}
		else {
			return $this->setJsonResponse(null, 500, 'No se pudo crear la API Key, por favor contacte al administrador');
		}
	}
	
	
	public function remakeAction($idUser)
	{
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 ",
			"bind" => array(1 => $idUser)
		));
		
		if($user->idAccount == $this->user->idAccount) {
			
			try {
				$obj = new ApiKeyObj();
				$obj->setUser($user);

				if(!$user->apikey) {
					throw new InvalidArgumentException('No existe una API Key para este usuario');
				}
				else {
					$key = $obj->updateAPIKey();
				}
				
				return $this->setJsonResponse(array('APIKey' => $key), 200, 'Se ha actualizado la API Key exitosamente');
			}
			catch (\Exception $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
			catch(\InvalidArgumentException $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
		}
		else {
			return $this->setJsonResponse(null, 500, 'No se pudo actualizar la API Key, por favor contacte al administrador');
		}
	}
	
	public function changestatusAction($idUser)
	{
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 ",
			"bind" => array(1 => $idUser)
		));
		
		if($user->idAccount == $this->user->idAccount) {
			
			try {
				$status = $this->request->getPost("state");
				
				$obj = new ApiKeyObj();
				$obj->setUser($user);

				if(!$user->apikey) {
					throw new InvalidArgumentException('No existe una API Key para este usuario');
				}
				else {
					$key = $obj->updateAPIKeyStatus($status);
				}
				$key->firstname = $user->firstName;
				$key->lastname = $user->lastName;
				$key->username = $user->username;

				return $this->setJsonResponse(array('APIKey' => $key), 200, 'Se ha actualizado la API Key exitosamente');
			}
			catch (\Exception $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
			catch(\InvalidArgumentException $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
		}
		else {
			return $this->setJsonResponse(null, 500, 'No se pudo actualizar el estado de la API Key, por favor contacte al administrador');
		}
	}

	public function deleteAction($idUser)
	{
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 ",
			"bind" => array(1 => $idUser)
		));
		
		if($user->idAccount == $this->user->idAccount) {
			
			try {
				$obj = new ApiKeyObj();
				$obj->setUser($user);

				if(!$user->apikey) {
					throw new InvalidArgumentException('No existe una API Key para este usuario');
				}
				else {
					$obj->deleteAPIKey();
				}
				
				$this->flashSession->error('Se ha eliminado la API Key exitosamente');
				return $this->response->redirect("apikey");
			}
			catch (\Exception $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				$this->flashSession->error($e->getMessage());
				return $this->response->redirect("apikey");
			}
			catch(\InvalidArgumentException $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				$this->flashSession->error($e->getMessage());
				return $this->response->redirect("apikey");
			}
		}
		else {
			return $this->setJsonResponse(null, 500, 'No se pudo eliminar la API Key, por favor contacte al administrador');
		}
	}
}

