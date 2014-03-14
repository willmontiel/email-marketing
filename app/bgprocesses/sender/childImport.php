<?php
require_once '../bootstrap/phbootstrap.php';

$child = new ChildImport();

$child->startProcess();

class ChildImport
{
	protected $pid;
	protected $mode ='NORMAL';
	protected $lasttime;
	protected $subscriber;
	protected $push;

	const NUMBER_OF_SECONDS = 20;
	
	public function startProcess()
	{
		$this->pid = getmypid();
		$context = new ZMQContext();
		
		$this->subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
		$this->subscriber->connect(SocketConstants::getImportPub2ChildrenEndPoint());
		
		$this->push = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
		$this->push->connect(SocketConstants::getImportPullFromChildEndPoint());
		
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

							$arrayDecode = json_decode($data);
	
							$idContactlist = $arrayDecode->idContactlist;
							$idImportproccess = $arrayDecode->idImportproccess;
							$fields = $arrayDecode->fields;
							$destiny = $arrayDecode->destiny;
							$delimiter = $arrayDecode->delimiter;
							$header = $arrayDecode->header;
							$idAccount = $arrayDecode->idAccount;
							$ipaddress = $arrayDecode->ipaddress;
							
							$account = Account::findFirstByIdAccount($idAccount);
							$wrapper = new ImportContactWrapper();
							
							$wrapper->setIdProccess($idImportproccess);
							$wrapper->setIdContactlist($idContactlist);
							$wrapper->setAccount($account);
							$wrapper->setIpaddress($ipaddress);
							$wrapper->startImport($fields, $destiny, $delimiter, $header);
							
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
}

return 0;

