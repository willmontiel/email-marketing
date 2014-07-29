<?php

class NextMailingObj {
	
	function __construct()
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setFrequency($frequency)
	{
		$this->frequency = $frequency;
	}
	
	/*
	 * Format days
	 * array('monday', 'wednesday', 'sunday')
	 */
	public function setDaysAllowed($days)
	{
		$this->days = $days;
	}
	
	/*
	 *Format time g:i a
	 *  12:20 pm
	 */
	public function setSendTime($time)
	{
		$this->time = $time;
	}
	
	public function setLastSentDate($lastsend = null)
	{
		$this->lastsend = $lastsend;
	}
	
	public function getNextSendTime()
	{
		$today = strtotime( date('d-m-Y', time()) . ' ' . $this->time );
		$today_txt = strtolower( date('l', time()) );
		
		if(in_array($today_txt, $this->days) && time() < $today) {
			return $today;
		}
		
		$next_send = 0;
		
		if($this->days) {
			foreach ($this->days as $day) {
				$day_at_time = strtotime('next ' . $day . ' ' . $this->time );
				if($this->lastsend) {
					$next_send = ( ( $next_send > $day_at_time || $next_send == 0 ) && ( $day_at_time > $this->lastsend ) ) ? $day_at_time : $next_send;
				}
				else {
					$next_send = ( $next_send > $day_at_time || $next_send == 0 ) ? $day_at_time : $next_send;
				}
			}
		}
		
		return $next_send;
	}
}

?>
