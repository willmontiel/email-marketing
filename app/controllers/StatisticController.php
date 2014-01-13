<?php
class StatisticController extends ControllerBase
{
	public function indexAction()
	{

	}
	
	public function mailAction($idMail)
	{
		$log = $this->logger;
		$log->log('El Id de Mail es: ' . $idMail);
				
		$statWrapper = new StatisticsWrapper();
		$statWrapper->setAccount($this->user->account);
		$mailStat = $statWrapper->showMailStatistics($idMail);
		
		if($mailStat) {
			$this->view->setVar("idMail", $idMail);
			$this->view->setVar("summaryChartData", $mailStat['summaryChartData']);
			$this->view->setVar("statisticsData", $mailStat['statisticsData']);
			$this->view->setVar("compareMail", $mailStat['compareMail']);
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	public function dbaseAction($idDbase)
	{
		$account = $this->user->account;
		
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idAccount = ?1 AND idDbase = ?2',
			'bind' => array(1 => $account->idAccount,
							2 => $idDbase)
		));
		
		if ($dbase) {
			$statsDbase = Statdbase::find(array(
				'conditions' => 'idDbase = ?1',
				'bind' => array(1 => $dbase->idDbase)
			));

			if (count($statsDbase) !== 0) {
				$dbases = Dbase::findByIdAccount($account->idAccount);
				
				$stat = new stdClass();

				foreach ($statsDbase as $s) {
					$idDbase =  $s->idDbase;
					$sent +=  $s->sent;
					$uniqueOpens +=  $s->uniqueOpens;
					$clicks +=  $s->clicks;
					$bounced +=  $s->bounced;
					$spam +=  $s->spam;
					$unsubscribed += $s->unsubscribed;
				}

				$this->logger->log("Sent: " . $sent);
				$stat->idDbase = $idDbase;
				$stat->sent = $sent;
				$stat->uniqueOpens = $uniqueOpens;
				$stat->percentageUniqueOpens = round(($uniqueOpens*100)/$sent);
				$stat->clicks = $clicks;
				$stat->bounced = $bounced;
				$stat->percentageBounced = round(($bounced*100)/$sent);
				$stat->spam = $spam;
				$stat->percentageSpam = round(($spam*100)/$sent);
				$stat->unsubscribed = $unsubscribed;
				$stat->percentageUnsubscribed = round(($unsubscribed*100)/$sent);

				$stat->undelivered = ($sent-$stat->percentageUniqueOpens);

				$this->view->setVar('stat', $stat);
				$this->view->setVar('dbase', $dbase);
				$this->view->setVar('dbases', $dbases);
			}
			else {
				$this->flashSession->warning("No hay estadisticas para base de datos seleccionada");
				return $this->response->redirect("dbase/" . $idDbase);
			}
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	public function contactlistAction($idContactList)
	{	
		$contactList = Contactlist::findFirst(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idContactList)
		));
		
		if ($contactList) {
			$dbase = Dbase::findFirstByIdDbase($contactList->idDbase);
			
			if ($dbase->idAccount == $this->user->account->idAccount) {
				
				$statsContactList = Statcontactlist::find(array(
					'conditions' => 'idContactlist = ?1',
					'bind' => array(1 => $idContactList)
				));
				
				if (count($statsContactList) !== 0) {
					$stat = new stdClass();

					foreach ($statsContactList as $s) {
						$idContactlist =  $s->idContactlist;
						$sent +=  $s->sent;
						$uniqueOpens +=  $s->uniqueOpens;
						$clicks +=  $s->clicks;
						$bounced +=  $s->bounced;
						$spam +=  $s->spam;
						$unsubscribed += $s->unsubscribed;
					}

					$this->logger->log("Sent: " . $sent);
					$stat->idContactlist = $idContactlist;
					$stat->sent = $sent;
					$stat->uniqueOpens = $uniqueOpens;
					$stat->percentageUniqueOpens = round(($uniqueOpens*100)/$sent);
					$stat->clicks = $clicks;
					$stat->bounced = $bounced;
					$stat->percentageBounced = round(($bounced*100)/$sent);
					$stat->spam = $spam;
					$stat->percentageSpam = round(($spam*100)/$sent);
					$stat->unsubscribed = $unsubscribed;
					$stat->percentageUnsubscribed = round(($unsubscribed*100)/$sent);

					$stat->undelivered = ($sent-$stat->percentageUniqueOpens);

					$this->view->setVar('stat', $stat);
					$this->view->setVar('contactList', $contactList);
				}
				else {
					$this->flashSession->warning("No hay estadisticas para la lista de contactos seleccionada");
					return $this->response->redirect("contactlist#/lists");
				}
			} 
			else {
				$this->flashSession->warning("No existe la lista de contactos");
				return $this->response->redirect("contactlist#/lists");
			}
		}
		else {
			$this->flashSession->warning("No existe la lista de contactos");
			return $this->response->redirect("contactlist#/lists");
		}
	}
	
	public function downloadreportAction($id, $type)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $id,
							2 => $account->idAccount)
		));

		if ($mail) {
			
			try {
				$createReport = new Reportingcreator($mail, $type);
				$r = $createReport->createReport();
			}
			catch (Exception $e) {
				$this->logger->log("E: " . $e->getMessage());
				$this->response->redirect('error');
			}
			
			$report = Mailreportfile::findFirst(array(
				'conditions' => 'idMailReportFile = ?1 AND idMail = ?2 AND type = ?3',
				'bind' => array(1 => $r->idMailReportFile,
								2 => $id,
								3 => $type)
			));
		
			$this->view->disable();

			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=reportedecampaña.csv');
			header('Pragma: public');
			header('Expires: 0');
			header('Content-Type: application/download');
			echo $r->title . PHP_EOL;
			echo PHP_EOL;
			echo 'Correos enviados: 20000' . PHP_EOL;
			echo 'Aperturas únicas: ' . $mail->uniqueOpens . " / " . ($mail->uniqueOpens*100)/20000 . "%" .PHP_EOL;
			echo 'Clics sobre enlaces: ' . $mail->clicks . PHP_EOL;
			echo 'Des-suscritos: ' . $mail->unsubscribed . " / " . ($mail->unsubscribed*100)/20000 . "%" .PHP_EOL;
			echo 'Rebotes: ' . $mail->bounced . " / " . ($mail->bounced*100)/20000 . "%" .PHP_EOL;
			echo PHP_EOL;
			readfile($this->mailReportsDir->reports . $report->name);
		}
	}
	
	public function compareAction($idMail, $idMailCompare) {
		$log = $this->logger;
		$log->log('Los IDs son: ' . $idMail . ' y ' . $idMailCompare);
		
		$statWrapper = new StatisticsWrapper();
		$statWrapper->setAccount($this->user->account);
		$mailStat1 = $statWrapper->showMailStatistics($idMail);
		$mailStat2 = $statWrapper->showMailStatistics($idMailCompare);
		
		if($mailStat1 && $mailStat2) {
			$this->view->setVar("idMail1", $idMail);
			$this->view->setVar("summaryChartData1", $mailStat1['summaryChartData']);
			$this->view->setVar("statisticsData1", $mailStat1['statisticsData']);
			$this->view->setVar("idMail2", $idMailCompare);
			$this->view->setVar("summaryChartData2", $mailStat2['summaryChartData']);
			$this->view->setVar("statisticsData2", $mailStat2['statisticsData']);
			$this->view->setVar("compareMail", $mailStat1['compareMail']);
		}
		else {
			$this->response->redirect('error');
		}
		
		
	}
}