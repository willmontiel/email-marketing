<?php
require_once '../bootstrap/phbootstrap.php';

if(isset($argv[1])) {
	switch ($argv[1]) {
		case '-d':
			demonize();
			if (isRunning()) {
				echo "The process is already running!\n";
				exit(0);
			}
			printf('Daemon' .PHP_EOL);
			fclose(STDIN);
			fclose(STDOUT);
			fclose(STDERR);
			break;
		case '-r':
			if (isRunning()) {
				echo "The process is already running!\n";
				exit(0);
			}
			printf('Directo' .PHP_EOL);
			break;
		case '-k':
			if (!isRunning()) {
				echo "There's no process to terminate\n";
				exit(0);
			}
			killProcess();
			exit(0);
			break;
		case '-s':
			if (!isRunning()) {
				echo "There's no process\n";
				exit(0);
			}
			processStatus();
			exit(0);
			break;
		default :
			printf('Comando no reconocido' .PHP_EOL);
			exit(0);
			break;
	}

	$parent = new ParentSender();
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

function isRunning()
{
	$context = new ZMQContext(1);
	$selfrequester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$selfrequester->connect("tcp://localhost:5556");
	
	$poll = new ZMQPoll();
	$poll->add($selfrequester, ZMQ::POLL_OUT);
	$selfrequester->setSockOpt(ZMQ::SOCKOPT_LINGER, 0);

	$readable = array();
	$writeable = array();
	$response = null;

	$events = $poll->poll($readable, $writeable, 1000);
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
	$events = $poll->poll($readable, $writeable, 1000);
	if ($events && count($readable) > 0) {
		$response = $selfrequester->recv(ZMQ::MODE_NOBLOCK);
		return true;
	}
	
	$poll->clear();
	return false;
}

function killProcess()
{
	$context = new ZMQContext(1);
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	$requester->send('Time-To-Die');
	$r = $requester->recv();
	printf('Recibi: ' . $r . PHP_EOL);
}

function processStatus()
{
	$context = new ZMQContext(1);
	$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
	$requester->connect("tcp://localhost:5556");
	$requester->send('Show-Status');
	$r = $requester->recv();
	printf($r);
//	$x ="aqui no va\nesto";
//	echo $x;
}

class ParentSender
{
	protected $di;
	protected $pool;
	protected $client;
	protected $tasks;
	
	public function __construct() {
		$this->di =  \Phalcon\DI\FactoryDefault::getDefault();
	}

	public function startProcess()
	{
		$registry = new Registry();
		
		// Crear los objetos
		$this->client = new ClientHandler($registry);
		$this->pool = new PoolHandler($registry);
		$this->tasks = new TasksHandler($registry);
		$this->client->register();
		$this->pool->register();
		$this->tasks->register();
		
		$this->tasks->setPool($this->pool);
		$this->client->setTasks($this->tasks);
		$this->client->setPool($this->pool);
		
		$this->pool->createInitialChildren();
		
		$context = new ZMQContext(1);
		
		$pull = new ZMQSocket($context, ZMQ::SOCKET_PULL);
		$pull->bind("tcp://*:5557");		
				
		$reply = $this->di['reply'];
		
		$poll = new ZMQPoll();
		$poll->add($reply, ZMQ::POLL_IN);
		$poll->add($pull, ZMQ::POLL_IN);
		
		$readable = $writeable = array();
			
		while (true) {

			$events = $poll->poll($readable, $writeable, 1000);

			if ($events > 0) {

				foreach ($readable as $socket) {
					
					if ($socket === $reply) {
						
						$request = $socket->recv();
						
						sscanf($request, "%s %s %s", $type, $data, $code);
						
						$registry->handleEvent(new Event($type, $data, $code));
					}
					else if($socket === $pull) {
						
						$request = $socket->recv();
						
						sscanf($request, "%s %s %s", $type, $data, $code);
						
						$registry->handleEvent(new Event($type, $data, $code));
					}
				}
			}
			else {
				$registry->handleEvent(new Event('Idle'));
				$registry->handleEvent(new Event('WP'));
			}
		}
	}
}
