<?php
class childObj
{
	protected $socket;
	
	public function setSocket($socket)
	{
		$this->socket = $socket;
	}
	
	public function startProcess($data)
	{
		printf('Procesando ' . $data.PHP_EOL);
		$salida = 1;
		while($salida < 5) {
//			printf('Ciclo numero ' . $salida .PHP_EOL);
			sleep(5);

			$msg = $this->socket->Messages();
			if($msg) {
//				printf('Child ' . $msg. PHP_EOL);
				printf('Llego ' . $msg . ' al PID ' . getmypid() .PHP_EOL);
			}
			$salida++;
		}
	}
}