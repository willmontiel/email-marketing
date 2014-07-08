<?php

class AccountWrapper extends BaseWrapper
{
	function __construct()
	{
		$this->db = Phalcon\DI::getDefault()->get('db');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function getAccountsBilling($f_date, $s_date)
	{
		$response = array();

		$accounts = Account::find();

		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setAccounts($accounts);
		$accounting->setAccountingModel('contactsPeriod', 'sentPeriod');

		$firstperiod = strtotime($f_date);
		$secondperiod = strtotime($s_date);

		if(!$firstperiod || !$secondperiod) {
			throw new ApiException("Fechas Invalidas");
		}

		$accounting->createAccounting($firstperiod, $secondperiod);

		$accounting->processAccountingArray('contactsPeriod', 'sentPeriod');

		$data = $accounting->getAccounting();

		foreach ($accounts as $account) {
			
			$response[] = $this->accountValues($account, $data);
		}
		
		return $response;
	}

	public function refillAccount($content)
	{
		$this->db->begin();
		
		if ( $this->account->accountingMode != $content->mode ) {
			throw new ApiException('No se pudo actualizar la Cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		if($content->mode == 'Contacto') {
			$this->account->contactLimit = $content->amount;
		}
		else if($content->mode == 'Envio') {
			$this->account->messageLimit = $content->amount;
		}
		
		$startdate = strtotime($content->start_date);
		$enddate = strtotime($content->expiry_date);

		if($startdate && $enddate) {
			$this->account->expiryDate = $enddate;
			
			$new_history = new Accountinghistory();
			$new_history->idAccount = $this->account->idAccount;
			$new_history->amount = $content->amount;
			$new_history->startDate = $startdate;
			$new_history->endDate = $enddate;
			
			if(!$new_history->save()){
				foreach ($new_history->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				$this->db->rollback();
				throw new ApiException('No se pudo actualizar la Cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
			}
		}
		else {
			$this->db->rollback();
			throw new ApiException('No se pudo actualizar la Cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		if(!$this->account->save()){
			$this->db->rollback();
			throw new ApiException('No se pudo actualizar la Cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		$this->db->commit();
		
		return $content;
	}
	
	protected function accountValues($account, $data)
	{
		$res = array(
			'id' => $account->idAccount,
			'name' => $account->companyName,
			'subscription' => $account->subscriptionMode,
			'mode' => $account->accountingMode,
			'expiry_date' => ($account->expiryDate) ? date('d-m-Y', $account->expiryDate) : 0 
		);

		if($account->accountingMode == 'Contacto') {
			$res['limit'] = $account->contactLimit;
			$res['consumed'] = $data[$account->idAccount]['contactsPeriod'];
			$res['available'] = $account->contactLimit - $data[$account->idAccount]['contactsPeriod'];
		}
		else if($account->accountingMode == 'Envio') {
			$res['available'] = $account->messageLimit;
			$res['consumed'] = $data[$account->idAccount]['sentPeriod'];
			$res['limit'] = $account->messageLimit + $data[$account->idAccount]['sentPeriod'];
		}
		
		return $res;
	}
	
	public function getAccountInfo($f_date, $s_date)
	{
		$response = array();
		
		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setSimpleAccountingModel($this->account, 'contactsPeriod', 'sentPeriod');
		
		$firstperiod = strtotime($f_date);
		$secondperiod = strtotime($s_date);

		if(!$firstperiod || !$secondperiod) {
			throw new ApiException("Fechas Invalidas");
		}
		
		$accounting->createAccounting($firstperiod, $secondperiod, $this->account->idAccount);
		
		$data = $accounting->getAccounting();
		
		$response[] = $this->accountValues($this->account, $data);
		
		return $response;
	}
}