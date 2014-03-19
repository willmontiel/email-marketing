<?php
/**
 * @RoutePrefix("/api/dbase")
 */
class DbaseapiController extends ControllerBase
{
	/**
	 * @Get("/{idDbase:[0-9]+}/contacts")
	 */
	public function searchcontactsAction($idDbase)
	{
		$search = $this->request->getQuery('searchCriteria', null, null);
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
                $this->logger->log('Criterio de búsqueda: ' . $search);
                $this->logger->log('Limit: ' . $limit);
                $this->logger->log('Page: ' . $page);
                
                $pager = new PaginationDecorator();
                if ($limit) {
                        $pager->setRowsPerPage($limit);
                }
                if ($page) {
                        $pager->setCurrentPage($page);
                }
                
		$account = $this->user->account;
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase,
							2 => $account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la base de datos');
		}
		
		if ($search != null) {
			try {   
                                $this->logger->log('search no esta vacio');
				$searchCriteria = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria($search);
				$contactset = new \EmailMarketing\General\ModelAccess\ContactSet();
				$contactset->setSearchCriteria($searchCriteria);
				$contactset->setAccount($account);
				$contactset->setDbase($dbase);
				$contactset->setPaginator($pager);
				$contactset->load();
			}
			catch (Exception $e) {
                            $this->logger->log('Exception: ' . $e);
                            return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
			}
                        catch (InvalidArgumentException $e) {
                            $this->logger->log('Invalid Argument Exception: ' . $e);
                            return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
                        }
			
			$rest = new \EmailMarketing\General\Ember\RESTResponse();
			$rest->addDataSource($contactset);
			
			return $this->setJsonResponse($rest->getRecords());
		}	
                else {
                    try {
                        $this->logger->log('search esta vacio');
                        $wrapper = new ContactWrapper();
                        $wrapper->setAccount($account);
                        $wrapper->setIdDbase($dbase->idDbase);
                        $wrapper->setPager($pager);
                        $result = $wrapper->findContacts($dbase);
			return $this->setJsonResponse($result);	
                    } 
                    catch (Exception $e) {
                        $this->logger->log('Exception: ' . $e);
                        return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
                    }
                    catch (InvalidArgumentException $e) {
                        $this->logger->log('Invalid Argument Exception: ' . $e);
                        return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
                    }
                }
	}
}