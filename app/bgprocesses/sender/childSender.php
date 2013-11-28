<?php
//require_once '../bootstrap/phbootstrap.php';

$child = new ChildSender();

$child->initialize();

class ChildSender
{
	protected $pid;
	protected $mode ='NORMAL';
	protected $lasttime;
	
	const NUMBER_OF_SECONDS = 20;
	
	public function initialize()
	{
		$this->pid = getmypid();
		
		$context = new ZMQContext();
		
		$subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
		$subscriber->connect("tcp://localhost:5558");
		
		$push = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
		$push->connect("tcp://localhost:5557");
		
		$filter = "$this->pid";
		$subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, $filter);
		
		$poll = new ZMQPoll();
		$poll->add($subscriber, ZMQ::POLL_IN);
		$readable = $writable = array();
		
		while (true) {
			$events = $poll->poll($readable, $writeable, 1000);
			
			if ($events && count($readable) > 0) {
				
				$request = $subscriber->recv(ZMQ::MODE_NOBLOCK);	
				
				if($request) {
					
					sscanf($request, "%d %s %s", $pid, $type, $data);
					
					switch ($type) {
						case 'Echo-Tmp-Request':
							printf('EmoPing numero '.$data .PHP_EOL);
							$this->mode = 'TEMP';
							$response = sprintf("%s %s Echo-Tmp-Reply", 'Child-'.$this->pid, $data);
							break;
						case 'Echo-Request':
							printf('Ping numero '.$data .PHP_EOL);
							$response = sprintf("%s %s Echo-Reply", 'Child-'.$this->pid, $data);
							break;
						case 'Process-Task':
							printf('Soy el PID ' . $pid . ' Y me Llego Esto: ' . $data . PHP_EOL);
							
							sleep(30);		
							
							printf('PID ' . $pid . ' Acabo' . PHP_EOL);
							$response = sprintf("%s %s Available", 'Child-'.$this->pid, $this->pid);
							break;
					}
					$push->send($response);
					$this->lasttime = time();
				}
			}
			else {
				if ((time() - $this->lasttime) > self::NUMBER_OF_SECONDS && $this->mode == 'TEMP') {
					$response = sprintf("%s %s Kill-Process", 'Child-'.$this->pid, $this->pid);
					$push->send($response);
					exit(0);
				}
			}
		}
	}
}

return 0;