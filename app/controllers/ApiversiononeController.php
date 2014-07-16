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
		try {
			$response = array();
			
			$accounts = Account::find();

			$data = array(	'id' => 'idAccount',
							'name' => 'companyName',
							'mode' => 'accountingMode',
							'subscription' => 'subscriptionMode');

			foreach ($accounts as $account) {
				$res = array();
				foreach ($data as $k => $d) {
					$res[$k] = $account->$d;
				}
				$response['response'][] = $res;
			}
			$response['status'] = 200;
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = 400;
			$response['message'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse($response);
	}
	
	
	/**
	 * @Get("/billing/accounting")
	 */	
	public function timebillingAction()
	{
		try {
			$response = array();
			$wrapper = new AccountWrapper();
			$response['message'] = $wrapper->getAccountsBilling($this->request->get('first_date'), $this->request->get('second_date'));
			$response['status'] = 200;
		}
		catch (ApiException $e) {
			$response['status'] = 400;
			$response['message'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = 400;
			$response['message'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse($response);
	}
	
	
	/**
	 *
	 * @Put("/account/refill/prepay/{idAccount:[0-9]+}")
	 */	
	public function refillprepayAction($idAccount)
	{
		try {
			$response = array();
			$contentsraw = $this->getRequestContent();
			$content = json_decode($contentsraw);

			$account = Account::findFirst(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $idAccount)
			));
			
			if($account->idAccount != $this->user->account->idAccount && $account->subscriptionMode == 'Prepago') {
				throw new ApiException("Usted no tiene permisos para realizar esta acción");
			}
			
			$wrapper = new AccountWrapper();
			$wrapper->setAccount($account);
			$response['message'] = $wrapper->refillAccount($content->account);
			$response['status'] = 200;
		}
		catch (ApiException $e) {
			$response['status'] = 400;
			$response['message'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = 400;
			$response['status'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse($response);
	}
	
	
	/**
	 *
	 * @Put("/account/refill/postpay/{idAccount:[0-9]+}")
	 */	
	public function refillpostpayAction($idAccount)
	{
		try {
			$response = array();
			$contentsraw = $this->getRequestContent();
			$content = json_decode($contentsraw);

			$account = Account::findFirst(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $idAccount)
			));
			
			if($account->idAccount != $this->user->account->idAccount && $account->subscriptionMode == 'Pospago') {
				throw new ApiException("Usted no tiene permisos para realizar esta acción");
			}
			
			$wrapper = new AccountWrapper();
			$wrapper->setAccount($account);
			$response['message'] = $wrapper->refillAccount($content->account);
			$response['status'] = 200;
		}
		catch (ApiException $e) {
			$response['status'] = 400;
			$response['message'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = 400;
			$response['message'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse($response);
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
			
			if($account->idAccount != $this->user->account->idAccount && $this->user->userrole != 'ROLE_SUDO') {
				throw new ApiException("Usted no tiene permisos para realizar esta acción");
			}
			
			$wrapper = new AccountWrapper();
			$wrapper->setAccount($account);
			$response['message'] = $wrapper->getAccountingForAccount($this->request->get('first_date'), $this->request->get('second_date'));
			$response['status'] = 200;
		}
		catch (ApiException $e) {
			$response['status'] = 400;
			$response['message'] = $e->getMessage();
		}
		catch (Exception $e) {
			$this->logger->log('Error API: [ ' . $e->getMessage() . ' ]');
			$response['status'] = 400;
			$response['message'] = "Error. Por favor, comuníquese con el administrador";
		}
		
		return $this->setJsonResponse($response);
	}
}