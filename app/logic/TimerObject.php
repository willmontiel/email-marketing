<?php

class TimerObject {
	protected $timerdata = array();

	/**
	 * Iniciar un contador de tiempo (inicializa totales si no ha existido)
	 * @param string $idx (el indice)
	 * @param string $description (la descripcion)
	 */
	public function startTimer($idx, $description = 'No description')
	{
		if (!isset($this->timerdata[$idx])) {
			$this->timerdata[$idx] = array('description' => $description, 'count' => 0, 'totaltime' => 0);
		}
		$this->timerdata[$idx]['starttime'] = microtime(true);
		$this->timerdata[$idx]['count']++;
	}
	
	/**
	 * Cierra el timer para un contador de tiempo
	 * @param string $idx
	 */
	public function endTimer($idx)
	{
		if (isset($this->timerdata[$idx])) {
			$start = $this->timerdata[$idx]['starttime'];
			$end = microtime(true);
			$this->timerdata[$idx]['totaltime'] += ($end - $start);
			unset($this->timerdata[$idx]['starttime']);
		}
		
	}

	/**
	 * Retorna la informacion del timer en un arreglo
	 * @return array
	 */
	public function getTimerData()
	{
		return $this->timerdata;
	}

	/**
	 * Convierte a cadena el timer
	 * @return string
	 */
	public function __toString() 
	{
		$txt  = PHP_EOL . '******************************************************************************************************' . PHP_EOL;
		$txt .= '*** Timing data                                                                                    ***' . PHP_EOL;
		foreach ($this->timerdata as $k => $tobj) {
			$txt .= \sprintf('*** %s (%s), ran %d times for a total of %.4f seconds ***' . PHP_EOL, $tobj['description'], $k, $tobj['count'], $tobj['totaltime']);
		}
		$txt .= '******************************************************************************************************' . PHP_EOL;
		
		return $txt;
	}
}
