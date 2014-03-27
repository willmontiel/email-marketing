<?php
namespace EmailMarketing\General\Dashboard;

class DashboardSummary
{
	protected $account;
	protected $mails;
	
	const NUMBER_OF_MAILS = 3;
	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	/**
	 * Metodo para obtener los Widgets que seran implementados en la vista.
	 * 
	 * Retorna los Widgtes.
	 * 
	 * @return array
	 */	
	public function fullPeriodStats()
	{
		$widgets = array();
		
		$widgets[] = new SimpleWidget($this->account, 'opening', 'Aperturas');
		$widgets[] = new SimpleWidget($this->account, 'clicks', 'Clics');
		$widgets[] = new SimpleWidget($this->account, 'unsubscribe', 'Desuscripciones');
		$widgets[] = new SimpleWidget($this->account, 'bounced', 'Rebotes');
		
		return $widgets;
	}
	
	/**
	 * Metodo para obtener los Widgets de las redes sociales que seran implementados en la vista.
	 * 
	 * Retorna los Widgtes de las redes sociales.
	 * 
	 * @return array
	 */	
	public function fullSocialStats()
	{
		$widgets = array();
		
		$widgets[] = new SocialWidget($this->account, 'fb', 'Shared');
		$widgets[] = new SocialWidget($this->account, 'tw', 'Tweets');
		
		return $widgets;
	}
	
	/**
	 * Metodo para obtener los ultimos correos que se han enviado en esta cuenta, con sus estadisticas.
	 * 
	 * Retorna los correos.
	 * 
	 * @return array
	 */			
	public function getLastMailsWithStats()
	{
		$mails = \Mail::find(array(
			"conditions" => "idAccount = ?1 AND status = 'Sent'",
			"bind" => array(
						1 => $this->account->idAccount,
					),
			"order" => "finishedon DESC",
			"limit" => self::NUMBER_OF_MAILS
		));
		
		return $mails;
	}
}