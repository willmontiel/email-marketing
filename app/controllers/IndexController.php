<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$dashboard = new DashboardSummary();
		$dashboard->setAccount($this->user->account);
		$mails = $dashboard->lastPeriodMails('15', 'day');
		$allstats = $dashboard->fullPeriodStats($mails);
		$socialstats = $dashboard->fullSocialStats($mails);
		$values = $dashboard->getStatsValuesFromMailsInPeriods($mails, 15);
		$lastmails = $dashboard->getLastMailsWithStats(3);
		$stats = array_merge($allstats, $socialstats);
		$this->logger->log('Estadisticas totales del periodo ' . print_r($stats, true));
		$this->logger->log('Estadisticas por intervalos ' . print_r($values, true));

		$this->view->setVar('values', $stats);
		$this->view->setVar('statvalues', $values);
		$this->view->setVar('lastmails', $lastmails);
		$this->view->setVar('confAccount', $this->user->account);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
    }
}