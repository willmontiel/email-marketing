<?php

class AccountWrapper extends BaseWrapper
{
	function __construct()
	{
		$this->db = Phalcon\DI::getDefault()->get('db');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function refillAccount($content)
	{
		$this->db->begin();
		
		if ( $this->account->accountingMode != $content->mode ) {
			throw new ApiException('No se pudo actualizar la cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		$startdate = strtotime($content->start_date);
		$expirydate = strtotime($content->expiry_date);

		if($startdate && $expirydate && $startdate >= strtotime('today midnight') && $startdate < $expirydate) {
			
			if($content->mode == 'Contacto') {
				$this->account->contactLimit = $content->amount;
			}
			else if($content->mode == 'Envio') {
				if($startdate < $this->account->expiryDate) {
					$this->account->messageLimit+= $content->amount;
				}
				else {
					$this->account->messageLimit = $content->amount;
				}
			}
			
			$old_history = Accountinghistory::findFirst(array(
						'conditions' => 'idAccount = ?1 AND endDate =?2',
						'bind' => array(1 => $this->account->idAccount,
										2 => $this->account->expiryDate)
			));
			
			$this->account->expiryDate = $expirydate;
			
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
			
			$new_history = new Accountinghistory();
			$new_history->idAccount = $this->account->idAccount;
			$new_history->amount = $content->amount;
			$new_history->startDate = $startdate;
			$new_history->endDate = $expirydate;
			
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
		
		foreach ($accounts as $account) {
			if($account->accountingMode == 'Contacto'){
				$response[] = $this->getPostpayContactBilling($account, $accounting, $firstperiod, $secondperiod);
			}
			else {
				$response[] = $this->getPostpaySendBilling($account, $accounting, $firstperiod, $secondperiod);
			}
			
		}

		return $response;
	}
	
	public function getAccountingForAccount($f_date, $s_date)
	{
		$response = array();
		
		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setSimpleAccountingModel($this->account, 'contactsPeriod', 'sentPeriod');
		
		$firstperiod = strtotime($f_date);
		$secondperiod = strtotime($s_date);

		if(!$firstperiod || !$secondperiod) {
			throw new ApiException("Fechas Invalidas");
		}
		
		if($this->account->accountingMode == 'Contacto'){
			$response[] = $this->getPrepayContactBilling($this->account, $accounting, $firstperiod, $secondperiod);
		}
		else {
			$response[] = $this->getPrepaySendBilling($this->account, $accounting, $firstperiod, $secondperiod);
		}
		
		return $response;
	}
	
	protected function getPrepayContactBilling(Account $account, \EmailMarketing\General\Misc\AccountingObject $accounting, $firstperiod, $secondperiod)
	{
		$response[$this->account->idAccount] =	array(	'id' => $account->idAccount,
														'name' => $account->companyName,
														'subscription' => $account->subscriptionMode,
														'mode' => $account->accountingMode,
														'history' => array());
		
		$history = $accounting->getAccountingHistory($firstperiod, $secondperiod, $account->idAccount);
		
		foreach ($history as $haccount) {
			if(!isset($response[$this->account->idAccount]['history']['end_date']) || $haccount->endDate > strtotime($response[$this->account->idAccount]['history']['end_date'])) {
				
				$response[$this->account->idAccount]['history'] = array('amount' => $haccount->amount,
																	'start_date' => date('d-m-Y', $haccount->startDate),
																	'end_date' => date('d-m-Y', $haccount->endDate));
			}
		}
		
		return $response;
	}
	
	protected function getPrepaySendBilling(Account $account, \EmailMarketing\General\Misc\AccountingObject $accounting, $firstperiod, $secondperiod)
	{
		$response[$account->idAccount] = array(	'id' => $account->idAccount,
												'name' => $account->companyName,
												'subscription' => $account->subscriptionMode,
												'mode' => $account->accountingMode,
												'history' => array());
		
		$accounting->createAccounting(date('d-m-Y', $firstperiod), date('d-m-Y', $secondperiod), $account->idAccount);
		$accounting->processAccountingArray('contactsPeriod', 'sentPeriod');
		$data = $accounting->getAccounting();
		
		$response[$account->idAccount]['history'][] = array('consumed' => $data[$account->idAccount]['sentPeriod'],
															'start_date' => date('d-m-Y', $firstperiod),
															'end_date' => date('d-m-Y', $secondperiod));
		
		return $response;
	}
	
	protected function getPostpayContactBilling(Account $account, \EmailMarketing\General\Misc\AccountingObject $accounting, $firstperiod, $secondperiod)
	{
		$response[$account->idAccount] = array(	'id' => $account->idAccount,
												'name' => $account->companyName,
												'subscription' => $account->subscriptionMode,
												'mode' => $account->accountingMode,
												'history' => array());
		
		$history = $accounting->getAccountingHistory($firstperiod, $secondperiod, $account->idAccount);
		
		foreach ($history as $haccount) {
			$response[$account->idAccount]['history'][] = array('amount' => $haccount->amount,
																'start_date' => date('d-m-Y', $haccount->startDate),
																'end_date' => date('d-m-Y', $haccount->endDate));
			
		}
		
		return $response;
	}
	
	protected function getPostpaySendBilling(Account $account, \EmailMarketing\General\Misc\AccountingObject $accounting, $firstperiod, $secondperiod)
	{
		$response[$account->idAccount] = array(	'id' => $account->idAccount,
												'name' => $account->companyName,
												'subscription' => $account->subscriptionMode,
												'mode' => $account->accountingMode,
												'history' => array());
		
		$accounting->createAccounting(date('d-m-Y', $firstperiod), date('d-m-Y', $secondperiod), $account->idAccount);
		$accounting->processAccountingArray('contactsPeriod', 'sentPeriod');
		$data = $accounting->getAccounting();
		
		$response[$account->idAccount]['history'][] = array('consumed' => $data[$account->idAccount]['sentPeriod'],
															'start_date' => date('d-m-Y', $firstperiod),
															'end_date' => date('d-m-Y', $secondperiod));
		
		return $response;
	}
}