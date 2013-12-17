<?php
/**
 * @RoutePrefix("/apistatistics")
 */
class ApistatisticsController extends ControllerBase
{
	/**
	 * @Get("/openstatistics")
	 */
	public function openstatisticsAction($idMail)
	{
		$log = $this->logger;
		
		$values[0] = array(
			'id' => 1,
			'title' =>'Enero',
			'value' => '20'
		);
		$values[1] = array(
			'id' => 2,
			'title' =>'Febrero',
			'value' => '30'
		);
		$values[2] = array(
			'id' => 3,
			'title' =>'Marzo',
			'value' => '50'
		);

		return $this->setJsonResponse(array('openstatistic' => $values));
	}
	
	/**
	 * @Get("/opendetaillists")
	 */
	public function opendetaillistsAction()
	{
		$log = $this->logger;
		
		$contact[0] = array(
			'email' => 'recipient00001@test001.local.discardallmail.drh.net',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$contact[1] = array(
			'email' => 'recipient00002@test002.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$contact[2] = array(
			'email' => 'recipient00003@test003.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Mac'
		);

		return $this->setJsonResponse(array('opendetaillist' => $contact));
	}
}
