<?php
class SegmentController extends ControllerBase
{
	public function showAction($idSegment)
	{
		$segment = Segment::findFirst(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $idSegment)
		));
		
		$fields = Customfield::findByIdDbase($segment->idDbase);
		
		$this->view->setVar("fields", $fields);
		
		$this->view->setVar('datasegment', $segment);
	}
}