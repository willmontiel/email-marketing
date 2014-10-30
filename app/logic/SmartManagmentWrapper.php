<?php

class SmartManagmentWrapper extends BaseWrapper
{
	protected $data;
	protected $logger;
	protected $smart;
	
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function setSmart($smart)
	{
		$this->smart = $smart;
	}
	
	public function saveSmart()
	{
		$this->validateData();
		$this->saveSmartManagment();
		$this->saveRules();
	}
	
	public function editSmart()
	{
		$this->validateData();
		$this->editSmartManagment();
		$this->deleteRules();
		$this->saveRules();
	}
	
	protected function saveSmartManagment()
	{
		$this->smart = new Smartmanagment();
		$this->smart->name = $this->data->name;
		$this->smart->target = $this->data->target;
		$this->smart->content = 'null';
		$this->smart->status = ($this->data->status == 'true' ? 1 : 0);
		$this->smart->updatedon = time();
		$this->smart->createdon = time();
		
		if (!$this->smart->save()) {
			foreach ($this->smart->getMessages() as $msg) {
				$this->logger->log("Error while saving smart... {$msg}");
				throw new Exception("Exception... {$msg}");
			}
		}
	}
	
	protected function editSmartManagment()
	{
		$this->smart->name = $this->data->name;
		$this->smart->target = $this->data->target;
		$this->smart->status = ($this->data->status == 'false' ? 0 : 1);
		$this->smart->updatedon = time();
		
		if (!$this->smart->save()) {
			foreach ($this->smart->getMessages() as $msg) {
				$this->logger->log("Error while saving smart... {$msg}");
				throw new Exception("Exception... {$msg}");
			}
		}
	}
	
	protected function deleteRules()
	{
		$rules = Rule::findByIdSmartmanagment($this->smart->idSmartmanagment);
		
		if (count($rules) > 0) {
			foreach ($rules as $rule) {
				if (!$rule->delete()) {
					foreach ($rule->getMessages() as $msg) {
						$this->logger->log("Error while deleting rule... {$msg}");
						throw new Exception("Exception... {$msg}");
					}
				}
			}
		}
	}
	
	protected function saveRules()
	{
		foreach ($this->data->rules as $rule) {
			$r = new Rule();
			$r->idSmartmanagment = $this->smart->idSmartmanagment;
			$r->rule = json_encode($rule);
			$r->updatedon = time();
			$r->createdon = time();
			
			if (!$r->save()) {
				foreach ($r->getMessages() as $msg) {
					$this->logger->log("Error while saving rule... {$msg}");
					throw new Exception("Exception... {$msg}");
				}
			}
		}
	}
	
	public function getSmart()
	{
		return $this->smart;
	}
	
	protected function validateData()
	{
		
	}
}