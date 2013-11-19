<?php
class ChildSender
{
	public function doSomething()
	{
		$sl = rand(5, 20);
		$pid = getmypid();
		echo "$pid is sleeping $sl seconds!\n";
		sleep($sl);
		echo "$pid dies\n";
	}
}

$x = new ChildSender();

$x->doSomething();