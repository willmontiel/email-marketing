<?php

class DashboardSummary
{
	protected $account;
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
}