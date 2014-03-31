<?php
namespace EmailMarketing\General\Dashboard;

class DashboardSummary
{
	protected $account;
	protected $mails;
	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	/**
	 * Metodo para obtener los ultimos correos que se han enviado.
	 * 
	 * Recibe los numeros de dias o meses, junto con el periodo que puede ser dias, meses o años
	 * Estos dos ultimos se establecen globales para ser utilizados por otros metodos
	 * 
	 * Retorna los correos que cumplen con estas condiciones
	 * 
	 * @param string $days
	 * @param string $period
	 */	
	public function lastPeriodMails($days, $period)
	{
		$this->days = $days;
		$this->period = $period;
		
		$this->mails = \Mail::find(array(
				"conditions" => "idAccount = ?1 AND finishedon > ?2 AND status = 'Sent'",
				"bind" => array(
						1 => $this->account->idAccount,
						2 => strtotime('-' . $days . ' ' . $period) //strtotime('-15 day')
					),
				));
	}
	
	/**
	 * Metodo para obtener el estado total de todos los correos enviados en el ultimo periodo.
	 * 
	 * Recibe los correos que deben ser evaluados sus estados.
	 * 
	 * Retorna los estados de Apertura, Clics, Desuscritos y Rebotados
	 * 
	 * @param type $mails
	 * @return array
	 */	
	public function fullPeriodStats()
	{
		$widgets = array();
		
		$widgets[] = new SimpleWidget($this->account, 'opening', 'Aperturas', $this->period, 'opens');
		$widgets[] = new SimpleWidget($this->account, 'clicks', 'Clics', $this->period, 'clicks');
		$widgets[] = new SimpleWidget($this->account, 'unsubscribe', 'Desuscripciones', $this->period, 'unsubscribed');
		$widgets[] = new SimpleWidget($this->account, 'bounced', 'Rebotes', $this->period, 'bounced');
		
		return $widgets;
	}
	
	/**
	 * Metodo para obtener el estado total de las redes sociales en los correos enviados en el ultimo periodo.
	 * 
	 * Recibe los correos que deben ser evaluados sus estados sociales.
	 * 
	 * Retorna los estados de Compartido, Apertura y Clics en Facebook y Twitter.
	 * 
	 * @param type $mails
	 * @return array
	 */	
	public function fullSocialStats()
	{
		$widgets = array();
		
		$widgets[] = new SocialWidget($this->account, 'fb', 'Shared', $this->period, 'fb');
		$widgets[] = new SocialWidget($this->account, 'tw', 'Tweets', $this->period, 'tw');
		
		return $widgets;
	}
		
	public function getLastMailsWithStats($howmany)
	{
		$mails = \Mail::find(array(
			"conditions" => "idAccount = ?1 AND status = 'Sent'",
			"bind" => array(
						1 => $this->account->idAccount,
					),
			"order" => "finishedon DESC",
			"limit" => $howmany
		));
		
		return $mails;
	}
}