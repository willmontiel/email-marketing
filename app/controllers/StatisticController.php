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
		$mailStat = $statWrapper->showMailStatistics($idMail);
		
		if($mailStat) {
			$this->view->setVar("idMail", $idMail);
			$this->view->setVar("summaryChartData", $mailStat['summaryChartData']);
			$this->view->setVar("statisticsData", $mailStat['statisticsData']);
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	public function dbaseAction($idDbase)
	{
		$dbases = Dbase::findByIdAccount($this->user->account->idAccount);
		$statsDbase = Statdbase::find(array(
			'conditions' => 'idDbase = ?1',
			'bind' => array(1 => $idDbase)
		));
		
		if ($statsDbase) {
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
			$this->view->setVar('dbases', $dbases);
		}
		else {
			$this->view->setVar('stat', 0);
		}
	}
	
	public function contactlistAction($idContactList)
	{
		$statsContactList = Statcontactlist::find(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idContactList)
		));
		
		if ($statsContactList) {
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
		}
		else {
			$this->view->setVar('stat', 0);
		}
	}
	
	public function downloadreportAction($resource, $id, $type)
	{
		switch ($resource) {
			case 'mail':
				$report = Mailreportfile::findFirst(array(
					'conditions' => 'idMail = ?1 AND type = ?2',
					'bind' => array(1 => $id,
									2 => $type)
				));
				break;
			case 'dbase':
				$report = Mailreportfile::findFirst(array(
					'conditions' => 'idDbase = ?1 AND type = ?2',
					'bind' => array(1 => $id,
									2 => $type)
				));
				break;
			case 'contactlist':
				$report = Mailreportfile::findFirst(array(
					'conditions' => 'idContactlist = ?1 AND type = ?2',
					'bind' => array(1 => $id,
									2 => $type)
				));
				break;
			default:
				$this->response->redirect('error');
				break;
		}
		
		$this->view->disable();
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=ReporteDeCorreo.csv');
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Type: application/download');
		echo 'Reporte de aperturas' . PHP_EOL;
		readfile($this->mailReportsDir->reports . $report->name);
	}
}