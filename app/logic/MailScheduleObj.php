<?php
class MailScheduleObj
{
	public function __construct(Mail $mail) 
	{
		$this->mail = $mail;
	}
	
	public function scheduleTask()
	{
		$task = new Mailschedule();
		
		$task->mail = $this->mail;
		$task->scheduleDate = $this->mail->scheduleDate;
		
		if (!$task->save()) {
			return false;
		}
		return true;
	}
}