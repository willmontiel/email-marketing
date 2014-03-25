<?php

namespace EmailMarketing\General\Dashboard;

class SocialWidget extends BaseWidget
{
	protected function processData()
	{
		try {
			// Calcular totales
			$time = strtotime('-15 day');
			$share = 'share_' . $this->property;
			$open = 'open_' . $this->property;
			$click = 'click_' . $this->property;
			
			$sql1 = "	SELECT SUM(c." . $share . ") AS share, SUM(c." . $open . ") AS open
						FROM Mxc AS c
							JOIN Mail AS m ON (c.idMail = m.idMail)
						WHERE m.finishedon > {$time}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}";
			$query1 = $this->modelManager->createQuery($sql1);
			$result1 = $query1->execute();

			$sql2 = "	SELECT IF(SUM(l." . $click . "),SUM(l." . $click . "),0) AS click
						FROM mxcxl AS l
							JOIN Mail AS m ON (l.idMail = m.idMail)
						WHERE m.finishedon > {$time}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}";
			$query2 = $this->modelManager->createQuery($sql2);
			$result2 = $query2->execute();

			$this->totalValue = (isset($result1[0]->share)) ? $result1[0]->share : 0;
			
			
			// Calcular valores para aperturas y clics
			// Secondary queda asi
			// [ { name: 'Aperturas', value: 123456.4 }, { name: 'Clics': value: 121212 } ]
			$o = new \stdClass();
			$o->name = 'Aperturas';
			$o->value = (isset($result1[0]->open)) ? $result1[0]->open : 0;
			$c = new \stdClass();
			$c->name = 'Clics';
			$c->value = (isset($result2[0]->click)) ? $result2[0]->click : 0;

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
