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
		
		$firstperiod = strtotime($f_date);
		$secondperiod = strtotime($s_date);

		if(!$firstperiod || !$secondperiod) {
			throw new ApiException("Fechas Invalidas");
		}

		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setAccounts($accounts);
		$accounting->setAccountingModel('contactsPeriod', 'sentPeriod');
		
		$history = $accounting->getAccountingHistory($firstperiod, $secondperiod);
		
		foreach ($history as $haccount) {
			if( !isset($response[$haccount->account->idAccount]) ) {
				$response[$haccount->account->idAccount] =	array('id' => $haccount->account->idAccount,
																'name' => $haccount->account->companyName,
																'subscription' => $haccount->account->subscriptionMode,
																'mode' => $haccount->account->accountingMode,
																'history' => array());
			}
			
			$accounting->createAccounting(date('d-m-Y', $haccount->startDate), date('d-m-Y', $haccount->endDate), $haccount->idAccount);
			
			$accounting->processAccountingArray('contactsPeriod', 'sentPeriod');

			$data = $accounting->getAccounting();
			
			$response[$haccount->account->idAccount]['history'][] = $this->accountValues($haccount->account, $data, $haccount);
		}

//		$accounting->createAccounting($firstperiod, $secondperiod);
//
//		$accounting->processAccountingArray('contactsPeriod', 'sentPeriod');
//
//		$data = $accounting->getAccounting();
//
//		foreach ($accounts as $account) {
//			
//			$response[] = $this->accountValues($account, $data, $history[$account->idAccount]);
//		}
		
		return $response;
	}

	public function refillAccount($content)
	{
		$this->db->begin();
		$available = 0;
		
		if ( $this->account->accountingMode != $content->mode ) {
			throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		if($content->mode == 'Contacto') {
			$available = $this->account->contactLimit;
			$this->account->contactLimit = $content->amount;
		}
		else if($content->mode == 'Envio') {
			$available = $this->account->messageLimit;
			$this->account->messageLimit+= $content->amount;
		}
		
		$startdate = strtotime($content->start_date);
		$expirydate = strtotime($content->expiry_date);

		if($startdate && $expirydate && $startdate > time() && $startdate < $expirydate) {
			$this->account->expiryDate = $expirydate;
			
			$old_history = Accountinghistory::findFirst(array(
						'conditions' => 'idAccount = ?1 AND endDate IS NULL',
						'bind' => array(1 => $this->account->idAccount)
			));
			
			if($old_history){
				
				$old_history->endDate = $startdate;
				
				if(!$old_history->save()){
					foreach ($old_history->getMessages() as $msg) {
						$this->logger->log($msg);
					}
					$this->db->rollback();
					throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
				}
			}
			else {
				throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
			}
			
			$new_history = new Accountinghistory();
			$new_history->idAccount = $this->account->idAccount;
			$new_history->amount = $content->amount;
			$new_history->available = $available;
			$new_history->startDate = $startdate;
			
			if(!$new_history->save()){
				foreach ($new_history->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				$this->db->rollback();
				throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
			}
		}
		else {
			$this->db->rollback();
			throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		if(!$this->account->save()){
			foreach ($new_history->getMessages() as $msg) {
				$this->logger->log($msg);
			}
			$this->db->rollback();
			throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		$this->db->commit();
		
		return $content;
	}
	
	protected function accountValues($account, $data, $history)
	{
//		$res = array(
//			'id' => $account->idAccount,
//			'name' => $account->companyName,
//			'subscription' => $account->subscriptionMode,
//			'mode' => $account->accountingMode,
//			'expiry_date' => ($account->expiryDate) ? date('d-m-Y', $account->expiryDate) : 0 
//		);
		
		$res = array();

		if($account->accountingMode == 'Contacto') {
			$res['total'] = $history->available;
			$res['consumed'] = $data[$account->idAccount]['contactsPeriod'];
			$res['available'] = $history->available - $data[$account->idAccount]['contactsPeriod'];
		}
		else if($account->accountingMode == 'Envio') {
			$res['available'] = $history->available;
			$res['consumed'] = $data[$account->idAccount]['sentPeriod'];
			$res['total'] = $history->available + $data[$account->idAccount]['sentPeriod'];
		}
		
//		$res['history'] = array();
//		
//		if($history != null) {
//			$res['history'] = $history;
//		}
		
		$res['amount'] = $history->amount;
		$res['start_date'] = date('d-m-Y', $history->startDate);
		$res['end_date'] = ($history->endDate) ? date('d-m-Y', $history->endDate) : '';
		
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
		
		$history = $accounting->getAccountingHistory($firstperiod, $secondperiod, $this->account->idAccount);
		
		foreach ($history as $h) {
			if( !isset($response[$this->account->idAccount]) ) {
				$response[$this->account->idAccount] =	array('id' => $this->account->idAccount,
																'name' => $this->account->companyName,
																'subscription' => $this->account->subscriptionMode,
																'mode' => $this->account->accountingMode,
																'history' => array());
			}
			
			$response[$this->account->idAccount]['history'][] = $this->accountValues($this->account, $data, $h);
			
		}
		
		//$response[] = $this->accountValues($this->account, $data, $history[$this->account->idAccount]);
		
		return $response;
	}
}