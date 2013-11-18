<?php
class MailScheduleObj
{
	public function __construct(Mail $mail) 
	{
		$this->mail = $mail;
	}
	
	public function scheduleTask()
	{
		$task = Mailschedule::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $this->mail->idMail)
		));
		
		if ($task) {
			$scheduleTask = $task;
		}
		else {
			$scheduleTask = new Mailschedule();
		}
		
		$scheduleTask->mail = $this->mail;
		$scheduleTask->scheduleDate = $this->mail->scheduleDate;
		
		if (!$scheduleTask->save()) {
			return false;
		}
		return true;
	}
}