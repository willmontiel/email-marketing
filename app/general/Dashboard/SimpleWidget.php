<?php

namespace EmailMarketing\General\Dashboard;

class SimpleWidget extends BaseWidget
{
	
	protected function processData()
	{
		// Calcular totales
		try {
			$property = $this->property;
			$time = strtotime('-15 day');
			$query = "	SELECT COUNT(c.{$property}) AS cnt
						FROM Mxc AS c 
							JOIN Mail AS m ON (c.idMail = m.idMail)
						WHERE m.finishedon > {$time}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}
						AND c.{$property} > 0";
			$sql = $this->modelManager->createQuery($query);
			$result = $sql->execute();

			$this->totalValue = $result[0]->cnt;
			// Calcular valores para chart
			
			$query1 = "	SELECT c.{$property} AS date, COUNT(c.{$property}) AS cnt
						FROM Mxc AS c 
							JOIN Mail AS m ON (c.idMail = m.idMail)
						WHERE m.finishedon > {$time}
						AND m.status = 'Sent'
						AND m.idAccount = {$this->account->idAccount}
						AND c.{$property} > 0
						GROUP BY FROM_UNIXTIME(c.{$property},'%Y %D %M')";
			$sql1 = $this->modelManager->createQuery($query1);
			$result1 = $sql1->execute();
			
			if (count($result1) > 0 ) {
				$this->logger->log($property);
				foreach ($result1 as $row) {
					$this->logger->log($row->cnt);
					$this->logger->log($row->date);
				}
			}
			
			
			$a = array();
			for($i = 0; $i < BaseWidget::CHART_INTERVALS; $i++) {
				$o = new \stdClass();
				$o->name = $i;
				$o->value = 0;
				$next = ($i >= 1) ? strtotime('-' . $i . ' ' . $this->period) : time();
				$prev = strtotime('-' . ( $i + 1 ) . ' ' . $this->period);
				foreach ($result1 as $row) {
					if( $prev < $row->date && $row->date < $next ) {
//						$this->logger->log($row->cnt);
//						$this->logger->log($row->date);
						$o->value = $row->cnt;
					}
				}
				$a[] = $o;
			}
//			$this->logger->log(print_r($a, true));
			$this->secondaryValues = $a;
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log($e);
		}
		catch (\Exception $e) {
			$this->logger->log($e);
		}
	}
		
	
}