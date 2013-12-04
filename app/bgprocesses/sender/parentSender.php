<?php
require_once '../bootstrap/phbootstrap.php';

if(isset($argv[1])) {
	
	switch ($argv[1]) {
		case '-d':
			printf('Demonizando' .PHP_EOL);
			demonize();
			break;
		case '-r':
			printf('Directamente' .PHP_EOL);
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

$parent = new ParentSender();
$parent->startProcess();

function demonize()
{
	if (pcntl_fork() != 0) {
			exit(0);
	}

	if(posix_setsid() < 0) {
		printf('Error en el Setsid' .PHP_EOL);
		exit(0);
	}

	if (pcntl_fork() != 0) {
			exit(0);
	}
	
	fclose(STDIN);
	fclose(STDOUT);
	fclose(STDERR);
}


class ParentSender
{
	protected $sockets;
	protected $pool;
	protected $client;
	protected $tasks;
	
	public function __construct() {
		$this->sockets = new Sockets();
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
		
		$poll = new ZMQPoll();
		$poll->add($this->sockets->getReply(), ZMQ::POLL_IN);
		$poll->add($this->sockets->getPull(), ZMQ::POLL_IN);
		
		$readable = $writeable = array();
			
		while (true) {

			$events = $poll->poll($readable, $writeable, 1000);

			if ($events > 0) {

				foreach ($readable as $socket) {
					
					if ($socket === $this->sockets->getReply()) {
						
						$request = $socket->recv();
						
						$this->sockets->getReply()->send('');
						
						sscanf($request, "%s %s %s", $type, $data, $code);
						
						$registry->handleEvent(new Event($type, $data, $code));
					}
					else if($socket === $this->sockets->getPull()) {
						
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
