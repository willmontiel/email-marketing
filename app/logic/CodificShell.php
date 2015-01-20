<?php

class CodificShell implements IElementProcessorRow 
{
	private $logger;
	private $appPath;
	private $pdf;
	
	public function __construct() 
	{
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->pdf = Phalcon\DI::getDefault()->get('pdf');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function processline($numRow, $row)
	{
		// Guardamos la informacion en un array para generar el shell
		$this->cmdline[] = array('pagenum'=> $numRow,
									 'id' => $row[0],
									 'password'=> $row[8]
								);
	}
	
	public function save($pdf)
	{	
		$explode = "{$this->appPath->path}/{$this->pdf->explodedbatch}/{$pdf->idAccount}/";
		if (!file_exists($explode)) {
			mkdir($explode, 0777, true);
		}
		
		$encrypted = "{$this->appPath->path}/{$this->pdf->encryptedbatch}/{$pdf->idAccount}/";
		if (!file_exists($encrypted)) {
			mkdir($encrypted, 0777, true);
		}
		
		$source = "{$this->appPath->path}/{$this->pdf->sourcebatch}/{$pdf->idAccount}/";
		if (!file_exists($source)) {
			mkdir($source, 0777, true);
		}
		
		foreach ($this->cmdline as $row) {
			$cmdline[] = "pdftk {$explode}pg_{$row['pagenum']}.pdf output {$encrypted}{$row['id']}.pdf user_pw {$row['password']} allow printing";
		}
		
		// grabar archivo de ejecucion
		file_put_contents("{$source}source_{$pdf->idPdfbatch}.sh", implode(PHP_EOL, $cmdline) . PHP_EOL);
		
		return count($this->cmdline);
	}
}