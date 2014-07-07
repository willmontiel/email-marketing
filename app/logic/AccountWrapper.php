<?php

class AccountWrapper extends BaseWrapper
{
	
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

	public function updateAccountSettings($content)
	{
		$this->account->accountingMode = $content->mode;
		
		if($content->mode == 'Contacto') {
			$this->account->contactLimit = $content->limit;
		}
		else if($content->mode == 'Envio') {
			$this->account->messageLimit = $content->limit;
		}
		
		$unixdate = strtotime($content->expiry_date);

		if($unixdate) {
			$this->account->expiryDate = $unixdate;
		}
		else {
			throw new ApiException('No se pudo actualizar la Cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
		if(!$this->account->save()){
			throw new ApiException('No se pudo actualizar la Cuenta ' . $this->account->companyName . '. Por favor comuniquese con el administrador');
		}
		
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