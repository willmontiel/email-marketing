<?php
class ApikeyController extends ControllerBase
{
	public function indexAction()
	{
		$idAccount = $this->user->account->idAccount;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$roles = array('ROLE_SUDO', 'ROLE_ADMIN');	
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => User::find("idAccount = $idAccount AND userrole IN ('" . implode("','", $roles) . "')"),
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
				$obj->updateAPIKey();
			}
			else {
				$key = $obj->createAPIKey();
			}
			
			//$apikey = array('apikey' => $key->apikey, 'secret' =>);
			return $this->setJsonResponse(array('APIKey' => $key), 200, 'Se ha creado la API Key exitosamente');
			}
			catch (\Exception $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
				return $this->setJsonResponse(null, 500, $e->getMessage());
			}
			catch(\InvalidArgumentException $e) {
				$this->logger->log('Error al Crear API Key. Error [ ' . $e . ' ]');
			}
		}
		else {
			//Retornar error
		}
	}
}

