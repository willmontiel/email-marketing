<?php
class SegmentController extends ControllerBase
{
	public function showAction($idSegment)
	{
		$account = $this->user->account;
		
		$segment = Segment::findFirst(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $idSegment)
		));
		
		if ($segment) {
			$this->logger->log('Hay segmento');
			$dbase = Dbase::findFirst(array(
				'conditions' => 'idDbase = ?1',
				'bind' => array(1 => $segment->idDbase)
			));
			
			if ($dbase->idAccount == $account->idAccount) {
				$this->logger->log('Hay segmento 2');
				$fields = Customfield::findByIdDbase($segment->idDbase);
				$this->view->setVar("fields", $fields);
				$this->view->setVar('datasegment', $segment);
			}
			else {
				return $this->response->redirect('error');
			}
		}
		else {
			return $this->response->redirect('error');
		}
	}
}