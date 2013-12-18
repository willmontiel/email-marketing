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
				
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if($mail) {
			$summaryChartData[] = array(
				'title' => "Aperturas",
				'value' => $mail->uniqueOpens
			);
			$summaryChartData[] = array(
				'title' => "Rebotados",
				'value' => $mail->bounced
			);
			$summaryChartData[] = array(
				'title' => "No Aperturas",
				'value' => $mail->totalContacts - ( $mail->uniqueOpens + $mail->bounced)
			);
			
			$this->view->setVar("summaryChartData", $summaryChartData);
			$this->view->setVar("idMail", $idMail);
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	public function dbaseAction($idDbase)
	{
		$log = $this->logger;
		//$log->log('El Id de Base de Datos es: ' . $idDbase);
		$summaryChartData[] = array(
			'title' => "Aperturas",
			'value' => 65
		);
		$summaryChartData[] = array(
			'title' => "Rebotados",
			'value' => 20
		);
		$summaryChartData[] = array(
			'title' => "No Aperturas",
			'value' => 15
		);
	
		$this->view->setVar("summaryChartData", $summaryChartData);
		$this->view->setVar("idDbase", $idDbase);
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
}