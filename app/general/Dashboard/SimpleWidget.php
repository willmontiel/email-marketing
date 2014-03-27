<?php

namespace EmailMarketing\General\Dashboard;

class SimpleWidget extends BaseWidget
{
	
	protected function processData()
	{
		try {
			// Calcular totales
			$property = $this->property;
			$time = new \DateTime('-15 day');
			$time->setTime(0, 0, 0);
			$query = "	SELECT COUNT(c.{$property}) AS cnt
						FROM Mxc AS c 
							JOIN Mail AS m ON (c.idMail = m.idMail)
						WHERE m.finishedon > {$time->getTimestamp()}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}
						AND c.{$property} > {$time->getTimestamp()}";
			$sql = $this->modelManager->createQuery($query);
			$result = $sql->execute();

			$this->totalValue = $result[0]->cnt;
			
			
			// Calcular valores para chart
			$query1 = "	SELECT c.{$property} AS date, COUNT(c.{$property}) AS cnt
						FROM Mxc AS c 
							JOIN Mail AS m ON (c.idMail = m.idMail)
						WHERE m.finishedon > {$time->getTimestamp()}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}
						AND c.{$property} > {$time->getTimestamp()}
						GROUP BY c.{$property}, FROM_UNIXTIME(c.{$property},'%Y %D %M')";
			$sql1 = $this->modelManager->createQuery($query1);
			$result1 = $sql1->execute();

			$a = array();
			for($i = 0; $i < BaseWidget::CHART_INTERVALS; $i++) {
				$o = new \stdClass();
				$o->name = $i;
				$o->value = 0;
				if($i >= 1) {
					$nexttime = new \DateTime('-' . $i . ' day');
					$nexttime->setTime(0, 0, 0);
					$next = $nexttime->getTimestamp();
				}
				else {
					$next = time();
				}
				$prev = new \DateTime('-' . ( $i + 1 ) . ' day');
				$prev->setTime(0, 0, 0);
				foreach ($result1 as $row) {
					if( $prev->getTimestamp() < $row->date && $row->date < $next ) {
						$this->logger->log($row->date);
						$o->value+= $row->cnt;
					}
				}
				$a[] = $o;
			}
			$this->secondaryValues = array_reverse($a);
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log($e);
		}
		catch (\Exception $e) {
			$this->logger->log($e);
		}
	}
		
	
}