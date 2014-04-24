<?php

class DbaseController extends ControllerBase
{
	protected $user;
	
	protected function findAndValidateDbaseAccount($id)
	{
		
		if ($db = Dbase::findFirstByIdDbase($id)) {
			if ($this->user->account == $db->account) {
                return $db;
            }
        }  
		$this->response->redirect('error');
		return null;
	}
	
	public function indexAction()
    {
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		
//		$this->logger->log("Page: {$currentPage}");
		$idAccount = $this->user->account->idAccount;
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data"  => Dbase::find("idAccount = $idAccount"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page"  => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
//		$this->logger->log("Page: {$page->current}, {$page->before}, {$page->next}");
		
		$this->view->setVar("page", $page);
    }
    
    public function newAction()
    {
        //Instanciar el formulario y Relacionarlo con los atributos del Model Dbase
        $db = new Dbase();
		
        $editform = new EditForm($db);

        if ($this->request->isPost()) {   
            $editform->bind($this->request->getPost(), $db);
			$idAccount = $this->user->account->idAccount;
			$name = $editform->getValue('name');
			
			if(!isset($name) || trim($name) === '' || $name == NULL) {
				$this->flashSession->error('Debe ingresar un nombre para la base de datos');
				return $this->response->redirect('dbase/new');
			}
		
			$nameExist = Dbase::findFirst(array(
				"conditions" => "idAccount = ?1 AND name = ?2",
				"bind" => array(1 => $idAccount,
								2 => $name)
			));
			if ($nameExist) {
				$this->flashSession->error('El nombre de la Base de Datos ya se encuentra registrado, por favor verifique la información');
				return $this->response->redirect('dbase/new');
			}
			else {
				$db->idAccount = $idAccount;
				$db->Ctotal = 0;
				$db->Cactive = 0;
				$db->Cinactive = 0;
				$db->Cunsubscribed  = 0;
				$db->Cbounced  = 0;
				$db->Cspam   = 0;

				if ($editform->isValid() && $db->save()) {
					$this->flashSession->success('Base de Datos Creada Exitosamente!');
					$this->response->redirect('dbase/show/'. $db->idDbase);
				}
				else {
					foreach ($db->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					return $this->response->redirect('dbase/new');
					
				}	
			}
		}
			$this->view->setVar('colors', DbaseWrapper::getColors());
			$this->view->editform = $editform;
    }
    
    public function showAction($id)
    {
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
			$this->view->setVar("sdbase", $db);
			$fields = Customfield::findByIdDbase($db->idDbase);
			$this->view->setVar("fields", $fields);

        }
    }
   
    public function editAction($id)
    {
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
            $this->view->setVar("edbase", $db);
			//Instanciar el formulario y Relacionarlo con los atributos del Model Dbase
			$editform = new EditForm($db);
			
			if ($this->request->isPost()) {   
				$editform->bind($this->request->getPost(), $db);
				
				$idAccount = $db->idAccount;
				$name = $editform->getValue('name');
				
				$nameExist = Dbase::findFirst(array(
					"conditions" => "idAccount = ?1 AND name = ?2 AND idDbase != ?3",
					"bind" => array(1 => $idAccount,
									2 => $name,
									3 => $db->idDbase)
				));
				if ($nameExist) {
					$this->flashSession->error('El nombre de la Base de Datos ya se encuentra registrado, por favor verifique la información');
					return $this->response->redirect('dbase/edit/'. $id);
				}
				else {
					if ($editform->isValid() && $db->save()) {
						$this->flashSession->success('Base de Datos Actualizada Exitosamente!');
						$this->response->redirect('dbase/show/'. $id);
					}
					else {
						foreach ($db->getMessages() as $msg) {
							$this->flashSession->error($msg);
						}
						return $this->response->redirect("dbase/edit/". $id);
					}
				}
			}
			$this->view->setVar('colors', DbaseWrapper::getColors());
			$this->view->editform = $editform;
        } 

    }
    
    public function deleteAction($id)
    {
        //Recuperar la informacion de la BD que se desea SI existe
        $db = $this->findAndValidateDbaseAccount($id);
		if ($db) {
			if($this->user->userrole === 'ROLE_SUDO') {
				$db->delete();
				$response = 'Base de Datos Eliminada!';
			}
			else {
				try {
					$wrapper = new DbaseWrapper();
					$wrapper->deleteDBAsUser($db);
					$response = 'Base de Datos Eliminada!';
				} catch(\Exception $e) {
					$response = $e->getMessage();
				}
			}
			$this->flashSession->warning($response);
		} 
		else {
			$this->flashSession->error('Base de Datos no existe');
		}
		return $this->response->redirect('dbase');
    }
}
