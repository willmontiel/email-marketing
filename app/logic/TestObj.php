<?php
class TestObj 
{
	
	public function __construct($db = null)
	{
		$this->db = ($db)?$db:Phalcon\DI::getDefault()->get('db');
	}
	
	public function save()
	{
		$db = $this->db;
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => 156)
		));

		$db->begin();
		
		try {
			$mxc = new Mxc();
			$mxc->idMail = 156;
			$mxc->idContact = 168;
			$mxc->status = 'sent';
			$mxc->opening = 0;
			$mxc->clicks = 0;
			$mxc->bounced = 0;
			$mxc->spam = 0;
			$mxc->contactlists = '50,51';

			$mail->uniqueOpens += 1;

			$event = new Mailevent();

			$event->idMail = 156;
			$event->idContact = 1000;
			$event->description = 'opening';
			$event->userAgent = 'Windows, internet explorer';
			$event->location = 'Cali, Valle, Colombia';

			if (!$mxc->save()) {
				foreach ($mxc->getMessages() as $msg) {
					$this->logger->log('Error. ' . $msg);
				}
				throw new InvalidArgumentException('Error saving mxc');
			}

			if (!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log('Error. ' . $msg);
				}
				throw new InvalidArgumentException('Error saving mail');
			}

			if (!$event->save()) {
				foreach ($event->getMessages() as $msg) {
					$this->logger->log('Error. ' . $msg);
				}
				throw new InvalidArgumentException('Error saving event');
			}
			$db->commit();
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Exception. ' . $e);
			$db->rollback();
		}
	}
}
