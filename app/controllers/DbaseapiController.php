<?php
/**
 * @RoutePrefix("/api/dbase")
 */
class DbaseapiController extends ControllerBase
{
	/**
	 * @Get("/api/dbase/{idDbase:[0-9]+}/contacts")
	 */
	public function searchcontactsAction($idDbase)
	{
		$search = $this->request->getQuery('searchCriteria', null, null);
		
		if ($search != null) {
			$contactset = new \EmailMarketing\General\ContactsSearcher\ContactSet();
			$contactset->setAccount($this->user->account);
			$contactset->setDbaseID($idDbase);
			$contactset->setSearchCriteria($search);
			
			try {
				$contactset->load();
			}
			catch (Exception $e)
			{
				$this->logger->log('Exception: ' . $e);
			}
		}	
	}
}