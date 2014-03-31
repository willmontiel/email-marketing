<?php

namespace EmailMarketing\General\Dashboard;

class SocialWidget extends BaseWidget
{
	protected function processData()
	{
//		$ids = array();
//		$share = 'share_' . $this->property;
//		$open = 'open_' . $this->property;
//		$click = 'click_' . $this->property;
//
//		foreach ($this->mails as $mail) {
//			$ids[] = $mail->idMail;
//		}
//
//		if(!empty($ids)) {
//			$idsbycomma = implode(',', $ids);
//			$query1 = "	SELECT SUM(m." . $share . ") AS share, SUM(m." . $open . ") AS open
//						FROM mxc AS m 
//						WHERE m.idMail IN ({$idsbycomma})";
//			$query2 = $this->modelManager->createQuery($query1);
//			$result1 = $query2->execute();
//
//			$query3 = "	SELECT IF(SUM(l." . $click . "),SUM(l." . $click . "),0) AS click
//						FROM mxcxl AS l
//						WHERE l.idMail IN ({$idsbycomma})";
//			$query4 = $this->modelManager->createQuery($query3);
//			$result2 = $query4->execute();
//		}
//		
//		$this->totalValue = (isset($result1[0]->share)) ? $result1[0]->share : 0;
//		
//		$o = new \stdClass();
//		$o->name = 'Aperturas';
//		$o->value = (isset($result1[0]->open)) ? $result1[0]->open : 0;
//		$c = new \stdClass();
//		$c->name = 'Clics';
//		$c->value = (isset($result2[0]->click)) ? $result2[0]->click : 0;
//		
//		array_push($this->secondaryValues, $o, $c);

		// Secondary queda asi
		// [ { name: 'Aperturas', value: 123456.4 }, { name: 'Clics': value: 121212 } ]
		$this->secondaryValues = json_decode('[{"name": "Aperturas", "value": 12},{"name": "Clics", "value": 18}]');
		$this->totalValue = rand(20, 20000);
	}
}
