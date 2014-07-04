<?php
/**
 * @RoutePrefix("/api/v1")
 */
class ApiversiononeController extends ControllerBase
{
	/**
	 * @Route("/echo", methods={"GET", "POST", "PUT"})
	 */
	public function echoAction()
	{
		return $this->setJsonResponse(array('success' => 'true','method' => $this->request->getMethod(), 'response' => $this->request->getRawBody()));
	}
	
	
	/**
	 * @Get("/billing/accounts")
	 */
	public function listaccountsAction()
	{
		$accounts = Account::find();
		
		$data = array(	'id' => 'idAccount',
						'name' => 'companyName',
						'mode' => 'accountingMode',
						'subscription' => 'subscriptionMode');
		
		$response = array();
		
		foreach ($accounts as $account) {
			$res = array();
			foreach ($data as $k => $d) {
				$res[$k] = $account->$d;
			}
			$response[] = $res;
		}
		
		return $this->setJsonResponse(array('accounts' => $response));
	}
	
	
	/**
	 * @Get("/billing/accounting")
	 */	
	public function timebillingAction()
	{
		try {
			$response = array();

			$accounts = Account::find();
		
			$accounting = new \EmailMarketing\General\Misc\AccountingObject();
			$accounting->setAccounts($accounts);
			$accounting->setAccountingModel('contactsPeriod', 'sentPeriod');
			
			$firstperiod = strtotime( $this->request->get('first_date') );
			$secondperiod = strtotime( $this->request->get('second_date') );
			
			if(!$firstperiod || !$secondperiod) {
				throw new Exception("Fechas Invalidas");
			}
			
			$accounting->createAccounting($firstperiod, $secondperiod);
			
			$accounting->processAccountingArray('contactsPeriod', 'sentPeriod');
			
			$data = $accounting->getAccounting();

			foreach ($accounts as $account) {
				$res = array(
					'id' => $account->idAccount,
					'name' => $account->companyName,
					'subscription' => $account->subscriptionMode,
					'mode' => $account->accountingMode
				);

				if($account->accountingMode == 'Contacto') {
					$res['limit'] = $account->contactLimit;
					$res['consumed'] = $data[$account->idAccount]['contactsPeriod'];
				}
				else if($account->accountingMode == 'Envio') {
					$res['limit'] = $account->messageLimit;
					$res['consumed'] = $data[$account->idAccount]['sentPeriod'];
				}
				$response[] = $res;
			}
		}
		catch (Exception $e) {
			$response['status'] = $e->getMessage();
		}
		
		return $this->setJsonResponse(array('accounts' => $response));
	}
	
	
	/**
	 *
	 * @Put("/account/update/{idAccount:[0-9]+}")
	 */	
	public function updateaccountAction($idAccount)
	{
		$contentsraw = $this->request->getRawBody();
		$this->logger->log('Got this: [' . $contentsraw . ']');
		$contentsT = json_decode($contentsraw);
		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
	}
}

?>
