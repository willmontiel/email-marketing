<?php
class SegmentWrapper extends BaseWrapper
{
	public function startCreatingSegmentProcess()
	{
		
	}
	public function startDeletingSegmentProcess(Account $account, $idSegment)
	{
		$idDbase = Dbase::findByIdAccount($account->idAccount);
		$segment = Segment::findFirst(array(
				"conditions" => "idSegment = ?1 AND idDbase = ?2",
				"bind" => array(
					1 => $idSegment,
					2 => $idDbase
				)
			)
		);
		
		if (!$segment ) {
			throw new \Exception('El segmento no existe');
		}
		$deletedSegment = $this->deleteSegment($segment);
	}
	
	public function deleteSegment($segment)
	{
		
	}
	
}
