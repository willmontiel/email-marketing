<?php
/**
 * @RoutePrefix("/api/contactlist")
 */
class ContactlistapiController extends ControllerBase
{
	/**
	 * @Get("/{idContactlist:[0-9]+}/contacts")
	 */
	public function searchcontactsAction($idContactlist)
	{
		$search = $this->request->getQuery('searchCriteria', null, null);
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
//		$this->logger->log('Criterio de búsqueda: ' . $search);
//		$this->logger->log('Limit: ' . $limit);
//		$this->logger->log('Page: ' . $page);
                
		$pager = new PaginationDecorator();
		if ($limit) {
				$pager->setRowsPerPage($limit);
		}
		if ($page) {
				$pager->setCurrentPage($page);
		}
                
		$account = $this->user->account;
		
		$contactlist = Contactlist::findFirst(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idContactlist)
		));
		
		if (!$contactlist) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la lista de contactos');
		}
		
		try { 
			if ($search != null) {
				$searchCriteria = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria($search);
				$contactset = new \EmailMarketing\General\ModelAccess\ContactSet();
				$contactset->setSearchCriteria($searchCriteria);
				$contactset->setAccount($account);
				$contactset->setContactlist($contactlist);
				$contactset->setPaginator($pager);
				$contactset->load();
				
				$rest = new \EmailMarketing\General\Ember\RESTResponse();
				$rest->addDataSource($contactset);
			
				return $this->setJsonResponse($rest->getRecords());
			}	
			else {
				$wrapper = new ContactWrapper();
				$wrapper->setAccount($account);
				$wrapper->setIdDbase($contactlist->idDbase);
				$wrapper->setPager($pager);
				$wrapper->setIdContactlist($contactlist->idContactlist);

				$contacts = $wrapper->findContactsComplete($contactlist);
				$contacts['lists'] = array(ContactListWrapper::convertListToJson($contactlist, $contactlist->dbase->account));

				return $this->setJsonResponse($contacts);
			}
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