<?php
/**
 * @RoutePrefix("/api/v1")
 */
class ApiversiononeController extends ControllerBase
{
	/**
	 * @Get("/account")
	 */
	public function listaccountsAction()
	{
		if($this->user->userrole == 'ROLE_SUDO') {
			$accounts = Account::find();
		}
		else {
			$accounts = $this->user->account;
		}
		
		$data = array(	'id' => 'idAccount',
						'messageLimit' =>	'messageLimit',
						'contactLimit' => 'contactLimit', 
						'accountingMode' => 'accountingMode',
						'companyName' => 'companyName',
						'subscriptionMode' => 'subscriptionMode');
		
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
	 * @Get("/billing")
	 */	
	public function timebillingAction()
	{
		if($this->user->userrole == 'ROLE_SUDO') {
			$accounts = Account::find();
		}
		else {
			$accounts = $this->user->account;
		}
		
		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setAccounts($accounts);
		$accounting->startAccounting();
		
		try {
			$data = $accounting->getAccounting();

			$response = array();

			foreach ($accounts as $account) {
				$res = array(
					'id' => $account->idAccount,
					'companyName' => $account->companyName,
					'subscriptionMode' => $account->subscriptionMode,
					'accountingMode' => $account->accountingMode
				);

				if($account->accountingMode == 'Contacto') {
					$res['limit'] = $account->contactLimit;
					$res['consumed'] = $data['contactsCurrentMonth'];
				}
				else if($account->accountingMode == 'Envio') {
					$res['limit'] = $account->messageLimit;
					$res['consumed'] = $data['sentCurrentMonth'];
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
