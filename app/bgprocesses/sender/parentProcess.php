<?php
require_once '../bootstrap/phbootstrap.php';

umask(0000);

if(isset($argv[1]) && isset($argv[2])) {
	if($argv[1] === '-m') {
		switch ($argv[2]) {
			case '-d':
				demonize();
				if (isRunning(SocketConstants::getMailRequestsEndPoint())) {
					echo "The process is already running!\n";
					exit(0);
				}
				printf('Daemon' .PHP_EOL);
				fclose(STDIN);
				fclose(STDOUT);
				fclose(STDERR);
				$processObj = new SenderProcess();
				break;
			case '-r':
				if (isRunning(SocketConstants::getMailRequestsEndPoint())) {
					echo "The process is already running!\n";
					exit(0);
				}
				printf('Directo' .PHP_EOL);
				$processObj = new SenderProcess();
				break;
			case '-k':
				if (!isRunning(SocketConstants::getMailRequestsEndPoint())) {
					echo "There's no process to terminate\n";
					exit(0);
				}
				killProcess(SocketConstants::getMailRequestsEndPoint());
				exit(0);
				break;
			case '-s':
				if (!isRunning(SocketConstants::getMailRequestsEndPoint())) {
					echo "There's no process\n";
					exit(0);
				}
				processStatus(SocketConstants::getMailRequestsEndPoint());
				exit(0);
				break;
			default :
				printf('Comando no reconocido' .PHP_EOL);
				exit(0);
				break;
		}
	}
	else if($argv[1] === '-i') {
		switch ($argv[2]) {
			case '-r':
				if (isRunning(SocketConstants::getImportRequestsEndPoint())) {
					echo "The process is already running!\n";
					exit(0);
				}
				printf('Directo' .PHP_EOL);
				$processObj = new ImportProcess();
				break;
			case '-k':
				if (!isRunning(SocketConstants::getImportRequestsEndPoint())) {
					echo "There's no process to terminate\n";
					exit(0);
				}
				killProcess(SocketConstants::getImportRequestsEndPoint());
				exit(0);
				break;
			case '-s':
				if (!isRunning(SocketConstants::getImportRequestsEndPoint())) {
					echo "There's no process\n";
					exit(0);
				}
				processStatus(SocketConstants::getImportRequestsEndPoint());
				exit(0);
				break;
			default :
				printf('Comando no reconocido' .PHP_EOL);
				exit(0);
				break;
		}
	}
	else {
		printf('Comando no reconocido' .PHP_EOL);
		exit(0);
	}
	$parent = new ParentProcess($processObj);
	$parent->startProcess();
}
else {
	printf('Comando no reconocido' .PHP_EOL);
	exit(0);
}

function demonize()
{
	if (pcntl_fork() != 0) {
			exit(0);
	}

	if(posix_setsid() < 0) {
		exit(0);
	}

	if (pcntl_fork() != 0) {
			exit(0);
	}
}

function isRunning($socket)
{
	$context = new ZMQContext(1);
	$selfrequester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$selfrequester->connect($socket);
	
	$poll = new ZMQPoll();
	$poll->add($selfrequester, ZMQ::POLL_OUT);
	$selfrequester->setSockOpt(ZMQ::SOCKOPT_LINGER, 0);

	$readable = array();
	$writeable = array();
	$response = null;

	$events = $poll->poll($readable, $writeable, 2000);
	if ($events && count($writeable) > 0) {
		$selfrequester->send('Are-You-There');
	}
	else {
		return false;
	}
	printf('Checking...' .PHP_EOL);
	
	$writeable = array();
	$poll->clear();
	$poll->add($selfrequester, ZMQ::POLL_IN);
	$events = $poll->poll($readable, $writeable, 2000);
	if ($events && count($readable) > 0) {
		$response = $selfrequester->recv(ZMQ::MODE_NOBLOCK);
		return true;
	}
	
	$poll->clear();
	return false;
}

function killProcess($socket)
{
	$context = new ZMQContext(1);
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect($socket);
	$requester->send('Time-To-Die');
	$r = $requester->recv();
	printf('Recibi: ' . $r . PHP_EOL);
}

function processStatus($socket)
{
	$context = new ZMQContext(1);
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect($socket);
	$requester->send('Show-Status-Console');
	$r = $requester->recv();
	printf($r);
}

class ParentProcess
{
	protected $di;
	protected $processObj;
	protected $context;

	public function __construct($processObj) {
		$this->processObj = $processObj;
		$this->di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->context = new ZMQContext(3);
		
		$context = $this->context;

		$publisher = new ZMQSocket($context, ZMQ::SOCKET_PUB);
		$publisher->bind($this->processObj->getPublisherToChildrenSocket());
		$this->di->set('publisher', $publisher);

		$reply = new ZMQSocket($context, ZMQ::SOCKET_REP);
		$reply->bind($this->processObj->getReplyToClientSocket());
		$this->di->set('reply', $reply);
	}

	public function startProcess()
	{
		$log = $this->di['logger'];
		
		$registry = new Registry();
		
		$this->processObj->createHandlers($registry);
		$this->processObj->setPoolConditions();
		
		$context = $this->context;
		
		$pull = new ZMQSocket($context, ZMQ::SOCKET_PULL);
		$pull->bind($this->processObj->getPullFromChildSocket());
				
		$reply = $this->di['reply'];
		
		$poll = new ZMQPoll();
		$poll->add($reply, ZMQ::POLL_IN);
		$poll->add($pull, ZMQ::POLL_IN);
		
		$readable = $writeable = array();
			
		while (true) {
			$events = $poll->poll($readable, $writeable, 1000);
			if ($events > 0) {
				foreach ($readable as $socket) {
					$request = $socket->recv();
					echo 'Parent:: Recibi esto -> ' . $request . PHP_EOL;
					$log->log('Parent:: Recibi esto -> ' . $request);
					sscanf($request, "%s %s %s", $type, $data, $code);
					$registry->handleEvent(new Event($type, $data, $code));
				}
			}
			else {
				$registry->handleEvent(new Event('Idle'));
				$registry->handleEvent(new Event('WP'));
			}
		}
	}
}
