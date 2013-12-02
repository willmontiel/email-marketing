<?php
require_once '../bootstrap/phbootstrap.php';

$child = new ChildSender();

$child->initialize();

class ChildSender
{
	protected $pid;
	protected $mode ='NORMAL';
	protected $lasttime;
	protected $subscriber;
	protected $push;

	const NUMBER_OF_SECONDS = 20;
	
	public function initialize()
	{
		$this->pid = getmypid();
		$communication = new ChildCommunication();
		$communication->setSocket($this);
		$context = new ZMQContext();
		
		$this->subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
		$this->subscriber->connect("tcp://localhost:5558");
		
		$this->push = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
		$this->push->connect("tcp://localhost:5557");
		
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
//							printf('Ping numero '.$data. ' en ' .$pid. PHP_EOL);
							$response = sprintf("%s %s Echo-Reply", 'Child-'.$this->pid, $data);
							break;
						case 'Echo-Tmp-Request':
//							printf('EmoPing numero '.$data. ' en ' .$pid. PHP_EOL);
							$this->mode = 'TEMP';
							$response = sprintf("%s %s Echo-Tmp-Reply", 'Child-'.$this->pid, $data);
							break;
						case 'Processing-Task':
							printf('Soy el PID ' . $pid . ' Y me Llego Esto: ' . $data . PHP_EOL);
//							sleep(30);
							$account = Account::findFirstByIdAccount(13);
							$communication->setAccount($account);
							$communication->startProcess($data);
							
							printf('PID ' . $pid . ' Acabo' . PHP_EOL);
							$response = sprintf("%s %s Process-Available", 'Child-'.$this->pid, $this->pid);
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
			$response = sprintf("%s %s $type", 'Process-Response', $this->pid);
			$this->push->send($response);
			return $type;
		}
		return NULL;
	}
}

return 0;