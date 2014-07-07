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
		
		return $this->setJsonResponse(array('response' => $response));
	}
	
	
	/**
	 * @Get("/billing/accounting")
	 */	
	public function timebillingAction()
	{
		try {
			$response = array();
			$wrapper = new AccountWrapper();
			$response[] = $wrapper->getAccountsBilling($this->request->get('first_date'), $this->request->get('second_date'));
		}
		catch (ApiException $e) {
			$response['status'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse(array('response' => $response));
	}
	
	
	/**
	 *
	 * @Put("/account/update/{idAccount:[0-9]+}")
	 */	
	public function updateaccountAction($idAccount)
	{
		try {
			$response = array();
			$contentsraw = $this->getRequestContent();
			$content = json_decode($contentsraw);

			$account = Account::findFirst(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $idAccount)
			));
			
			if($account->idAccount != $this->user->account->idAccount) {
				throw new ApiException("Usted no tiene permisos para realizar esta acción");
			}
			
			$wrapper = new AccountWrapper();
			$wrapper->setAccount($account);
			$response[] = $wrapper->updateAccountSettings($content->account);
		}
		catch (ApiException $e) {
			$response['status'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse(array('response' => $response));
	}
	
	
	/**
	 * @Get("/account/consult/{idAccount:[0-9]+}")
	 */	
	public function accountinformationAction($idAccount)
	{
		try {
			$response = array('status'  => 'OK');
			$account = Account::findFirst(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $idAccount)
			));

			$wrapper = new AccountWrapper();
			$wrapper->setAccount($account);
			$response[] = $wrapper->getAccountInfo($this->request->get('first_date'), $this->request->get('second_date'));
		}
		catch (ApiException $e) {
			$response['status'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse(array('response' => $response));
	}
}