<?php

namespace EmailMarketing\General\Dashboard;

class SocialWidget extends BaseWidget
{
	protected function processData()
	{
		try {
			// Calcular totales
			/*$time = new \DateTime('-' . BaseWidget::CHART_INTERVALS . ' day');
			$time->setTime(0, 0, 0);
			$share = 'share_' . $this->property;
			$open = 'open_' . $this->property;
			$click = 'click_' . $this->property;
			
			$sql1 = "	SELECT SUM(c." . $share . ") AS share, SUM(c." . $open . ") AS open
						FROM Mxc AS c
							JOIN Mail AS m ON (c.idMail = m.idMail)
						WHERE m.finishedon > {$time->getTimestamp()}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}";
			$query1 = $this->modelManager->createQuery($sql1);
			$result1 = $query1->execute();

			$sql2 = "	SELECT IF(SUM(l." . $click . "),SUM(l." . $click . "),0) AS click
						FROM Mxcxl AS l
							JOIN Mail AS m ON (l.idMail = m.idMail)
						WHERE m.finishedon > {$time->getTimestamp()}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}";
			$query2 = $this->modelManager->createQuery($sql2);
			$result2 = $query2->execute();

			$this->totalValue = (isset($result1[0]->share)) ? $result1[0]->share : 0;*/
			
			$mail = \Mail::findFirst(array(
				"conditions" => "idAccount = ?1 AND status = 'Sent'",
				"bind" => array(
							1 => $this->account->idAccount,
						),
				"order" => "finishedon DESC",
			));
			
			$snQuery = "SELECT SUM(m.share_{$this->property}) AS share, 
							SUM(m.open_{$this->property}) AS open 
						FROM Mxc AS m
						WHERE m.idMail = :idMail:";
			$social = $this->modelManager->createQuery($snQuery);
			$socialStats = $social->execute(array(
				'idMail' => $mail->idMail
			));
			
			$snCliksQuery = "SELECT SUM(l.click_{$this->property}) AS click 
							FROM Mxcxl AS l
							WHERE l.idMail = :idMail:";
			$socialClicks = $this->modelManager->createQuery($snCliksQuery);
			$socialClickStats = $socialClicks->execute(array(
				'idMail' => $mail->idMail
			));
			
			$this->totalValue = (isset($socialStats[0]->share)) ? $socialStats[0]->share : 0;
			
			// Calcular valores para aperturas y clics
			// Secondary queda asi
			// [ { name: 'Aperturas', value: 123456.4 }, { name: 'Clics': value: 121212 } ]
			$o = new \stdClass();
			$o->name = 'Aperturas';
			$o->value = (isset($socialStats[0]->open)) ? $socialStats[0]->open : 0;
			$c = new \stdClass();
			$c->name = 'Clics';
			$c->value = (isset($socialClickStats[0]->click)) ? $socialClickStats[0]->click : 0;
			array_push($this->secondaryValues, $o, $c);
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log($e);
		}
		catch (\Exception $e) {
			$this->logger->log($e);
		}
	}
}
