<?php
class MailScheduleObj
{
	public function __construct(Mail $mail) 
	{
		$this->mail = $mail;
	}
	
	public function taskSchedule()
	{
		$task = new Mailschedule();
		
		$task->idMail = $this->mail;
		$task->scheduleDate = $this->scheduleDate;
		
		if (!$task->save()) {
			return false;
		}
		return true;
	}
}