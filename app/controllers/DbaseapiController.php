<?php
/**
 * @RoutePrefix("/api/dbase")
 */
class DbaseapiController extends ControllerBase
{
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/contacts")
	 */
	public function searchcontactsAction($idDbase)
	{
		$search = $this->request->getQuery('searchCriteria', null, null);
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
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
				$searchCriteria = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria($search);

				$pager = new PaginationDecorator();

				if ($limit) {
					$pager->setRowsPerPage($limit);
				}
				if ($page) {
					$pager->setCurrentPage($page);
				}
				$contactset = new \EmailMarketing\General\ContactsSearcher\ContactSet();
				$contactset->setSearchCriteria($searchCriteria);
				$contactset->setAccount($account);
				$contactset->setDbase($dbase);
				$contactset->setPaginator($pager);
				$contactset->load();
			}
			catch (Exception $e)
			{
				$this->logger->log('Exception: ' . $e);
			}
			
			$rest = new \EmailMarketing\General\Ember\RESTResponse();
			$rest->addDataSource($contactset);
			
			return $this->setJsonResponse($rest->getRecords());
		}	
                else {
                    $contacts = array();
                    return $this->setJsonResponse(array('contact' => $contacts));
                }
	}
}