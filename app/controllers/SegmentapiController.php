<?php
/**
 * @RoutePrefix("/api/segment")
 */
class SegmentapiController extends ControllerBase
{
	/**
	 * @Get("/{idSegment:[0-9]+}/contacts")
	 */
	public function searchcontactsAction($idSegment)
	{
		$search = $this->request->getQuery('searchCriteria', null, null);
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
                
		$pager = new PaginationDecorator();
		if ($limit) {
				$pager->setRowsPerPage($limit);
		}
		if ($page) {
				$pager->setCurrentPage($page);
		}
                
		$account = $this->user->account;
		
		$segment = Segment::findFirst(array(
			'conditions' => 'idSegment = ?1',
			'bind' => array(1 => $idSegment)
		));
		
		if (!$segment) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el segmento');
		}
		
		try { 
			if ($search != null) {
				$searchCriteria = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria($search);
				$contactset = new \EmailMarketing\General\ModelAccess\ContactSet();
				$contactset->setSearchCriteria($searchCriteria);
				$contactset->setAccount($account);
				$contactset->setSegment($segment);
				$contactset->setPaginator($pager);
				$contactset->load();
				
				$rest = new \EmailMarketing\General\Ember\RESTResponse();
				$rest->addDataSource($contactset);
			
				return $this->setJsonResponse($rest->getRecords());
			}	
			else {
				$this->logger->log('Va null');
				$segmentwrapper = new SegmentWrapper();
				$segmentwrapper->setPager($pager);
				$contacts = $segmentwrapper->findContactsInSegment($segment);
				
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