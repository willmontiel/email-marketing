<?php

class ContactExporter extends BaseWrapper
{
	private $data;
	private $file;
	
	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new InvalidArgumentException("export data is not valid...");
		}
		$this->data = $data;
	}
	
	public function startExporting()
	{
		
	}

	public function getFileData()
	{
		return $this->file;
	}
}