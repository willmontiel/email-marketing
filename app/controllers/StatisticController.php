<?php
class StatisticController extends ControllerBase
{
	public function indexAction()
	{

	}
	
	public function mailAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2 AND status = "Sent"',
			'bind' => array(1 => $idMail, 2 => $this->user->account->idAccount)
		));
		
		if($mail) {
			$target = $this->getTargetFromMail($mail);
			$statWrapper = new StatisticsWrapper();
			$statWrapper->setAccount($this->user->account);
			$mailStat = $statWrapper->showMailStatistics($mail);
			
			if($mailStat) {
				$this->view->setVar("mail", $mail);
				$this->view->setVar("target", $target);
				$this->view->setVar("summaryChartData", $mailStat['summaryChartData']);
				$this->view->setVar("statisticsData", $mailStat['statisticsData']);
				$this->view->setVar("statisticsSocial", $mailStat['statisticsSocial']);
				$this->view->setVar("statisticsClicksSocial", $mailStat['statisticsClicksSocial']);
				$this->view->setVar("compareMail", $mailStat['compareMail']);
			}
			else {
				$this->response->redirect('error');
			}
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	private function getTargetFromMail($mail)
	{
		$t = json_decode($mail->target);
		$target = "Indefinida";
		
		switch ($t->destination) {
			case 'contactlists':
				$target = "Listas de contactos: ";
				foreach ($t->ids as $id) {
					$list = Contactlist::findFirst(array(
						'conditions' => "idContactlist = ?1",
						'bind' => array(1 => $id)
					));
					
					
					if ($list) {
						$target .= "{$list->name}, ";
					}
				}
				break;
			
			case 'dbases':
				$target = "Bases de datos: ";
				foreach ($t->ids as $id) {
					$list = Dbase::findFirst(array(
						'conditions' => "idDbase = ?1",
						'bind' => array(1 => $id)
					));

					if ($list) {
						$target .= "{$list->name}, ";
					}
				}
				break;
			
			case 'segments':
				$target = "Segmentos: ";
				foreach ($t->ids as $id) {
					$list = Segment::findFirst(array(
						'conditions' => "idSegment = ?1",
						'bind' => array(1 => $id)
					));

					if ($list) {
						$target .= "{$list->name}, ";
					}
				}
				break;
			
			default:
				break;
		}
		
		return $target;
	}
	
	
	public function dbaseAction($idDbase)
	{
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idAccount = ?1 AND idDbase = ?2',
			'bind' => array(1 => $this->user->account->idAccount,
							2 => $idDbase)
		));
		
		if ($dbase) {
			
			$statWrapper = new StatisticsWrapper();
			$statWrapper->setAccount($this->user->account);
			$statWrapper->setDbase($dbase);
			
			$statistics = $statWrapper->showDbaseStatistics($dbase);
			
			$statWrapper->groupDomainsByDbase("opens");
			$statWrapper->regroupDomains();
			$domainsByOpens = $statWrapper->getDomains();
			
			$statWrapper->groupDomainsByDbase("bounced");
			$statWrapper->regroupDomains();
			$domainsByBounced = $statWrapper->getDomains();
			
			$statWrapper->groupDomainsByDbase("unsubscribed");
			$statWrapper->regroupDomains();
			$domainsByUnsubscribed = $statWrapper->getDomains();
			
			$statWrapper->groupDomainsByDbase("spam");
			$statWrapper->regroupDomains();
			$domainsBySpam = $statWrapper->getDomains();
			
			
			
			if($statistics) {
				$this->view->setVar('statisticsData', $statistics['statisticsData']);
				$this->view->setVar('summaryChartData', $statistics['summaryChartData']);
				$this->view->setVar('compareDbase', $statistics['compareDbase']);
				
				$this->view->setVar('domainsByOpens', $domainsByOpens);
				$this->view->setVar('domainsByBounced', $domainsByBounced);
				$this->view->setVar('domainsByUnsubscribed', $domainsByUnsubscribed);
				$this->view->setVar('domainsBySpam', $domainsBySpam);
				
				$this->view->setVar('dbase', $dbase);
			}
			else {
				$this->flashSession->warning("No hay estadisticas para la base de datos seleccionada");
				return $this->response->redirect("contactlist#/lists");
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
				
				$statWrapper = new StatisticsWrapper();
				$statWrapper->setAccount($this->user->account);
				$statWrapper->setContactlist($contactList);
				
				$statistics = $statWrapper->showContactlistStatistics($contactList, $dbase);
				
				$statWrapper->groupDomaninsByContactlist('opens');
				$statWrapper->regroupDomains();
				$domainsByOpens = $statWrapper->getDomains();

				$statWrapper->groupDomaninsByContactlist('bounced');
				$statWrapper->regroupDomains();
				$domainsByBounced = $statWrapper->getDomains();

				$statWrapper->groupDomaninsByContactlist('unsubscribed');
				$statWrapper->regroupDomains();
				$domainsByUnsubscribed = $statWrapper->getDomains();

				$statWrapper->groupDomaninsByContactlist('spam');
				$statWrapper->regroupDomains();
				$domainsBySpam = $statWrapper->getDomains();
				
				if($statistics) {
					$this->view->setVar('statisticsData', $statistics['statisticsData']);
					$this->view->setVar('summaryChartData', $statistics['summaryChartData']);
					$this->view->setVar('compareList', $statistics['compareList']);
					
					$this->view->setVar('domainsByOpens', $domainsByOpens);
					$this->view->setVar('domainsByBounced', $domainsByBounced);
					$this->view->setVar('domainsByUnsubscribed', $domainsByUnsubscribed);
					$this->view->setVar('domainsBySpam', $domainsBySpam);
					
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
				$this->logger->log("Exception: {$e}");
				$this->traceFail("Creating report, idMail: {$id}, type: {$type}");
				$this->flashSession("Ha ocurrido un error, por favor contacte al administrador");
				return $this->response->redirect("statistic/mail/{$id}");
			}
			
			$report = Mailreportfile::findFirst(array(
				'conditions' => 'idMailReportFile = ?1 AND idMail = ?2 AND type = ?3',
				'bind' => array(1 => $r->idMailReportFile,
								2 => $id,
								3 => $type)
			));
			
			if (!$report) {
				$this->traceFail("Creating report, report do not exists, idMail: {$id}, type: {$type}");
				$this->flashSession("Ha ocurrido un error, por favor contacte al administrador");
				return $this->response->redirect("statistic/mail/{$id}");
			}
			
			$this->view->disable();

			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=reportedecampaña.csv');
			header('Pragma: public');
			header('Expires: 0');
			header('Content-Type: application/download');
			echo $r->title . PHP_EOL;
			echo PHP_EOL;
			echo ' ,Total,Porcentaje' . PHP_EOL;
			echo 'Correos enviados:, ' . $mail->totalContacts . PHP_EOL;
			echo 'Aperturas únicas:, ' . $mail->uniqueOpens . " , " . ($mail->uniqueOpens*100)/$mail->totalContacts . "%" .PHP_EOL;
			echo 'Clics sobre enlaces:, ' . $mail->clicks . " , " . PHP_EOL;
			echo 'Des-suscritos:, ' . $mail->unsubscribed . " , " . ($mail->unsubscribed*100)/$mail->totalContacts . "%" .PHP_EOL;
			echo 'Rebotes:, ' . $mail->bounced . " , " . ($mail->bounced*100)/$mail->totalContacts . "%" .PHP_EOL;
			echo PHP_EOL;
			echo 'FECHA,DIRECCIÓN DE CORREO ELÉCTRONICO' .PHP_EOL;
			readfile($this->mailReportsDir->reports . $report->name);
			
			$this->traceSuccess("Creating report, idMail: {$id}, type: {$type}");
		}
		else {
			$this->flashSession("No existe el correo, por favor verifique la información");
			return $this->response->redirect("error");
		}
	}
	
	public function comparemailsAction($idMail, $idMailCompare) 
	{
		$mail1 = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2 AND status = "Sent"',
			'bind' => array(1 => $idMail, 2 => $this->user->account->idAccount)
		));
		
		$mail2 = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2 AND status = "Sent"',
			'bind' => array(1 => $idMailCompare, 2 => $this->user->account->idAccount)
		));
		
		if($mail1 && $mail2) {
			try {
				$statWrapper = new StatisticsWrapper();
				$statWrapper->setAccount($this->user->account);

				$mailStat1 = $statWrapper->showMailStatistics($mail1);
				$mailStat2 = $statWrapper->showMailStatistics($mail2);
				
				$this->logger->log(print_r($mailStat1['statisticsData'], true));
				if($mailStat1 && $mailStat2) {
					$this->view->setVar("mail1", $mail1);
					$this->view->setVar("summaryChartData1", $mailStat1['summaryChartData']);
					$this->view->setVar("statisticsData1", $mailStat1['statisticsData']);
					$this->view->setVar("mail2", $mail2);
					$this->view->setVar("summaryChartData2", $mailStat2['summaryChartData']);
					$this->view->setVar("statisticsData2", $mailStat2['statisticsData']);
					$this->view->setVar("compareMail", $mailStat1['compareMail']);
					$this->traceSuccess("Comparing mails, idMail1: {$idMail} / idMail2: {$idMailCompare}");
				}
				else {
					$this->response->redirect('error');
				}
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Comparing mails, idMail1: {$idMail} / idMail2: {$idMailCompare}");
				$this->response->redirect("statistic/mail/{$idMail}");
			}
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	public function comparelistsAction($idList, $idListCompare) {
		$log = $this->logger;
		
		$contactList1 = Contactlist::findFirst(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idList)
		));
		$contactList2 = Contactlist::findFirst(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idListCompare)
		));
		
		$dbase = Dbase::findFirstByIdDbase($contactList1->idDbase);

		if($dbase && $contactList1 && $contactList2 && ($contactList2->idDbase == $dbase->idDbase) && ($dbase->idAccount == $this->user->account->idAccount)) {
			try {
				$statWrapper = new StatisticsWrapper();
				$statWrapper->setAccount($this->user->account);

				$listStat1 = $statWrapper->showContactlistStatistics($contactList1, $dbase);
				$listStat2 = $statWrapper->showContactlistStatistics($contactList2, $dbase);
				
				if($listStat1 && $listStat2) {
					$this->view->setVar("compare1", $contactList1);
					$this->view->setVar("summaryChartData1", $listStat1['summaryChartData']);
					$this->view->setVar("statisticsData1", $listStat1['statisticsData']);
					$this->view->setVar("compare2", $contactList2);
					$this->view->setVar("summaryChartData2", $listStat2['summaryChartData']);
					$this->view->setVar("statisticsData2", $listStat2['statisticsData']);
					$this->view->setVar("compareList", $listStat1['compareList']);
					$this->traceSuccess("Comparing contactlists, idContactlist1: {$idList} / idContactlist2: {$idListCompare}");
				}
				else {
					$this->response->redirect('error');
				}
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Comparing contactlists, idContactlist1: {$idList} / idContactlist2: {$idListCompare}");
				$this->response->redirect("statistic/contactlist/{$idList}");
			}
		}
		else {
			$this->response->redirect('error');
		}
	}
	
	public function comparedbasesAction($idDbase, $idDbaseCompare) {
		$log = $this->logger;
		
		$dbase1 = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase, 2 => $this->user->account->idAccount)
		));
		$dbase2 = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbaseCompare, 2 => $this->user->account->idAccount)
		));
		
		if($dbase1 && $dbase2) {
			try {
				$statWrapper = new StatisticsWrapper();
				$statWrapper->setAccount($this->user->account);

				$dbaseStat1 = $statWrapper->showDbaseStatistics($dbase1);
				$dbaseStat2 = $statWrapper->showDbaseStatistics($dbase2);

				if($dbaseStat1 && $dbaseStat2) {
					$this->view->setVar("compare1", $dbase1);
					$this->view->setVar("summaryChartData1", $dbaseStat1['summaryChartData']);
					$this->view->setVar("statisticsData1", $dbaseStat1['statisticsData']);
					$this->view->setVar("compare2", $dbase2);
					$this->view->setVar("summaryChartData2", $dbaseStat2['summaryChartData']);
					$this->view->setVar("statisticsData2", $dbaseStat2['statisticsData']);
					$this->view->setVar("compareDbase", $dbaseStat1['compareDbase']);
					$this->traceSuccess("Comparing dbases, idDbase1: {$idDbase} / idDbase2: {$idDbaseCompare}");
				}
				else {
					$this->response->redirect('error');
				}
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Comparing dbases, idDbase1: {$idDbase} / idDbase2: {$idDbaseCompare}");
				$this->response->redirect("statistic/dbase/{$idDbase}");
			}
		}
		else {
			$this->response->redirect('error');
		}
	}
}