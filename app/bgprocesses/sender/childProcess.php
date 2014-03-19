<?php
require_once '../bootstrap/phbootstrap.php';

abstract class ChildProcess {
	
	protected $pid;
	protected $mode ='NORMAL';
	protected $lasttime;
	protected $subscriber;
	protected $push;
	
	const NUMBER_OF_SECONDS = 20;
	
	public function getImportChild()
	{
		return new ChildImport();
	}
	
	public function getSenderChild()
	{
		return new ChildSender();
	}
	
	public function startProcess()
	{
		$this->pid = getmypid();
		$context = new ZMQContext();
		
		$this->subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
		$this->subscriber->connect($this->publishToChildren());
		
		$this->push = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
		$this->push->connect($this->pullFromChild());
		
		$filter = "$this->pid";
		$this->subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, $filter);
		
		$poll = new ZMQPoll();
		$poll->add($this->subscriber, ZMQ::POLL_IN);
		$readable = $writable = array();
		
		while (true) {
			$events = $poll->poll($readable, $writeable, 1000);
			
			if ($events && count($readable) > 0) {
				
				$request = $this->subscriber->recv(ZMQ::MODE_NOBLOCK);	
				
				if($request) {
					
					sscanf($request, "%d %s %s", $pid, $type, $data);
					switch ($type) {
						case 'Echo-Request':
							$response = sprintf("%s %s Echo-Reply", 'Child-'.$this->pid, $data);
							break;
						case 'Echo-Tmp-Request':
							$this->mode = 'TEMP';
							
							$response = sprintf("%s %s Echo-Tmp-Reply", 'Child-'.$this->pid, $data);
							break;
						case 'Processing-Task':
							printf('Soy el PID ' . $pid . ' Y me Llego Esto: ' . $data . PHP_EOL);

							$this->executeProcess($data);
							
							Phalcon\DI::getDefault()->get('logger')->log(Phalcon\DI::getDefault()->get('timerObject'));
							
							printf('PID ' . $pid . ' Acabo' . PHP_EOL);
							
							$response = sprintf("%s %s Process-Available", 'Child-'.$this->pid, $this->pid);
							break;
						case 'Processing-Task':
							$response = sprintf("%s %s %s", 'Child-'.$this->pid, 0, 'Work-Checked');
							break;
						case 'Echo-Kill':
							printf($pid . ' Es hora de que muera' . PHP_EOL);
							exit(0);
							break;
					}
					$this->push->send($response);
					$this->lasttime = time();
				}
			}
			else {
				if ((time() - $this->lasttime) > self::NUMBER_OF_SECONDS && $this->mode == 'TEMP') {
					$response = sprintf("%s %s Kill-Process", 'Child-'.$this->pid, $this->pid);
					$this->push->send($response);
					exit(0);
				}
			}
		}
	}
	
	public function Messages()
	{
		$request = $this->subscriber->recv(ZMQ::MODE_NOBLOCK);	
		if($request) {
			sscanf($request, "%d %s %s", $pid, $type, $data);
			switch ($type) {
				case 'Echo-Kill':
					printf($pid . ' Estoy trabajando pero debo morir' . PHP_EOL);
					exit(0);
					break;
			}
			return $type;
		}
		return NULL;
	}
	
	public function responseToParent($header, $content)
	{
		$response = sprintf("%s %s %s", 'Child-'.$this->pid, $content, $header);
		$this->push->send($response);
	}
	
	abstract public function executeProcess($data);
	abstract public function publishToChildren();
	abstract public function pullFromChild();
	
}