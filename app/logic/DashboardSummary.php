<?php

class DashboardSummary
{
	protected $account;
	
	public function setAccount(Account $account)
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
	 * @return type $mails
	 */	
	public function lastPeriodMails($days, $period)
	{
		$this->days = $days;
		$this->period = $period;
		
		$mails = Mail::find(array(
				"conditions" => "idAccount = ?1 AND finishedon > ?2 AND status = 'Sent'",
				"bind" => array(
						1 => $this->account->idAccount,
						2 => strtotime('-' . $days . ' ' . $period) //strtotime('-15 day')
					),
				));
		return $mails;
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
	public function fullPeriodStats($mails)
	{
		$allstats = array(
			'opens' => 0,
			'clicks' => 0,
			'unsubscribed' => 0,
			'bounced' => 0
		);
		
		foreach ($mails as $mail) {
			$allstats['opens']+= $mail->uniqueOpens;
			$allstats['clicks']+= $mail->clicks;
			$allstats['unsubscribed']+= $mail->unsubscribed;
			$allstats['bounced']+= $mail->bounced;
		}
		
		return $allstats;
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
	public function fullSocialStats($mails)
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		$ids = array();
		
		foreach ($mails as $mail) {
			$ids[] = $mail->idMail;
		}
		$idsbycomma = implode(',', $ids);
		$query1 = "	SELECT SUM(m.share_fb) AS share_fb, SUM(m.share_tw) AS share_tw, SUM(m.open_fb) AS open_fb, SUM(m.open_tw) AS open_tw
					FROM mxc AS m 
					WHERE m.idMail IN ({$idsbycomma})";
		$query2 = $modelManager->createQuery($query1);
		$result1 = $query2->execute();
		
		$query3 = "	SELECT IF(SUM(l.click_fb),SUM(l.click_fb),0) AS click_fb, IF(SUM(l.click_tw),SUM(l.click_tw),0) AS click_tw
					FROM mxcxl AS l
					WHERE l.idMail IN ({$idsbycomma})";
		$query4 = $modelManager->createQuery($query3);
		$result2 = $query4->execute();
		
		$result = array(
			'share_fb' => $result1[0]->share_fb,
			'share_tw' => $result1[0]->share_tw,
			'open_fb' => $result1[0]->open_fb,
			'open_tw' => $result1[0]->open_tw,
			'click_fb' => $result2[0]->click_fb,
			'click_tw' => $result2[0]->click_tw,
		);
		
		return $result;
	}
	
	/**
	 * Metodo para obtener las estadisticas de los correos enviados en un diferentes tipos de periodos o rangos.
	 * 
	 * Recibe los correos que deben ser evaluados y el intervalo que los separa.
	 * 
	 * Retorna los estados de Apertura, Clics, Desuscritos y Rebotados sumados segun el intervalo.
	 * 
	 * @param type $mails
	 * @param int $intervals
	 * @return array
	 */	
	public function geStatsValuesFromMailsInPeriods($mails, $intervals)
	{
		$stats = array();
		for($i = 0; $i < $intervals; $i++) {
			$stats[$i] = array(
				'opens' => 0,
				'clicks' => 0,
				'unsubscribed' => 0,
				'bounced' => 0
			);
			$next = ($i >= 1) ? strtotime('-' . $i . ' ' . $this->period) : time();
			$prev = strtotime('-' . ( $i + 1 ) . ' ' . $this->period);
			foreach ($mails as $mail) {
				if( $prev < $mail->finishedon && $mail->finishedon < $next ) {
					$stats[$i] = $this->addStatsToArray($mail, $stats[$i]);
				}
			}
		}
		
		return $stats;
	}
	
	protected function addStatsToArray(Mail $mail, $stats)
	{
		$stats['opens']+= $mail->uniqueOpens;
		$stats['clicks']+= $mail->clicks;
		$stats['unsubscribed']+= $mail->unsubscribed;
		$stats['bounced']+= $mail->bounced;

		return $stats;
	}
	
	public function getLastMailsWithStats($howmany)
	{
		$mails = Mail::find(array(
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