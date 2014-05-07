<?php
class ShareController extends ControllerBase
{
	public function statisticsAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $account->idAccount)
		));
		
		if ($mail && $mail->status == 'Sent') {
			$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));

			$parameters = array(1, $mail->idMail, 'summary');
			$urlSummary = $linkdecoder->encodeLink('share/results', $parameters);
			
			$parameters2 = array(1, $mail->idMail, 'complete');
			$urlComplete = $linkdecoder->encodeLink('share/results', $parameters2);
			
			$url = array($urlSummary, $urlComplete);
		
			$this->traceSuccess("Share statistics, idMail: {$idMail}");
			return $this->setJsonResponse($url, 200);
		}
		
		return $this->setJsonResponse("Mail not found!", 404);
	}
	
	public function resultsAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri($this->urlManager->getBaseUri(true));

			$idenfifiers = $linkEncoder->decodeLink('share/results', $parameters);
			list($idLink, $idMail, $type) = $idenfifiers;

			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			));

			if ($mail) {
				$statWrapper = new StatisticsWrapper();
				
				if ($type == 'complete') {
					$statWrapper->setAccount($this->user->account);
					$mailStat = $statWrapper->showMailStatistics($mail);
				}
				else if ($type == 'summary') {
					$statWrapper->setAccount($this->user->account);
					$mailStat = $statWrapper->showMailStatistics($mail);
					$this->view->setVar("mail", $mail);
					$this->view->setVar("summaryChartData", $mailStat['summaryChartData']);
					$this->view->setVar("statisticsData", $mailStat['statisticsData']);
					$this->view->setVar("compareMail", $mailStat['compareMail']);
				}
			}
		}
		catch (Exception $e) {
			$this->logger->log("Exception: {$e}");
			$this->traceFail("Share statistics, idMail: {$idMail}");
			$this->response->redirect('error/link');
		}
	}
}