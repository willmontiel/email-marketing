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
						'mode' => 'accountingMode');
		
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
		$accounts = Account::find();
		
		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setAccounts($accounts);
		$accounting->startAccounting();
		
		try {
			$data = $accounting->getAccounting();

			$response = array();

			foreach ($accounts as $account) {
				$res = array(
					'id' => $account->idAccount,
					'name' => $account->companyName,
					'subscription' => $account->subscriptionMode,
					'mode' => $account->accountingMode
				);

				if($account->accountingMode == 'Contacto') {
					$res['limit'] = $account->contactLimit;
					$res['consumed'] = $data[$account->idAccount]['contactsCurrentMonth'];
				}
				else if($account->accountingMode == 'Envio') {
					$res['limit'] = $account->messageLimit;
					$res['consumed'] = $data[$account->idAccount]['sentCurrentMonth'];
				}
				$response[] = $res;
			}
		}
		catch (Exception $e) {
			$this->logger->log("Exception: {$e}");
		}
		
		return $this->setJsonResponse(array('accounts' => $response));
	}
}

?>
