<?php

class PdfCreator extends BaseWrapper
{
	private $logger;
	private $appPath;
	private $dir;
	private $data;
	private $pdf;
	private $toProcess = 0;
	private $processed = 0;
	private $status = 'en proceso';
	private $name;
	
	public function __construct() 
	{
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->dir = Phalcon\DI::getDefault()->get('pdf');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function setData($data)
	{
		if (!is_object($data) || empty($data)) {
			throw new InvalidArgumentException("export data is not valid...");
		}
		$this->data = $data;
	}
		
	public function startProcess()
	{
		try {
			$this->searchPdfRecord();
			$this->initialize();
			$this->createBatch();
			
			$this->status = 'finalizado';
			$this->updateBatch();
		}
		catch (Exception $ex) {
			$this->logger->log("Exception: {$ex}");
			$this->status = 'cancelado';
			$this->updateBatch();
		}
	}
	
	private function initialize()
	{
		$this->name = $this->extractName();
		$dirBase = "{$this->appPath->path}";
		$dir = "{$this->dir->csvbatch}/{$this->pdf->idAccount}/";
		
		$csvFile = "{$dirBase}/{$dir}/{$this->pdf->idPdfbatch}.csv";
		
		//Validamos si existe el archivo CSV para obtener la estructura XML
		if (!file_exists($csvFile)) {
			throw new Exception("Se encontró un error mientras se procesaba la solicitud, esto puede deberse a que el directorio o archivo en donde estan los recursos no existe o no tiene permisos");
		}
		
		// Convertimos el archivo de csv con la informacion de los clientes a CSV
		$fileCsv = new ProcessFile();

		$xml = new CodificXml();
		$fileCsv->addElementProcessor($xml);

		$shell = new CodificShell();
		$fileCsv->addElementProcessor($shell);

		$fileCsv->setSourceFile($csvFile);

		$result = $fileCsv->process();

		//Si el procesamiento fue correcto guardamos la informacion
		if (!$result) {
			throw new Exception("Se encontró un error mientras se procesaba la solicitud, esto puede deberse a que el directorio o archivo en donde estan los recursos no existe o no tiene permisos");
		}

		//$idenvioInter = str_pad($idenvio, 4, '0', STR_PAD_LEFT);
		$this->toProcess = $shell->save($this->pdf);
		$xml->save($this->pdf);

		$this->updateBatch();
	}
	
	private function createBatch()
	{
		$xml = $this->getXmlFolder();
		$xsl = $this->getXslFolder();
		$pdf = $this->getPdfFile();
		$log = $this->getFopLogFile();
		$fopConf = $this->getFopConf();
		$pdftk = $this->getShellFile(); 
		$encrypted = $this->getEncryptedFolder();
		$exploded = $this->getExplodedFolder();
		
		$this->createPdfMaster($xml, $xsl, $pdf, $fopConf, $log);
		$this->burstPdf($pdf, $exploded);
		
		$file = fopen($pdftk, "r");
		//Output a line of the file until the end is reached
		
		$i = 0;
		while(!feof($file)) {
			$row = fgets($file);
			if (!empty($row)) {
				$this->encryptePdf($row);
				$i++;
				
				if ($i == 50) {
					$this->processed += $i;
					$this->updateProcessed();
				}
			}
		}
		
		fclose($file);
		
		$this->processed += $i;
		$this->updateProcessed();
		
		$this->zipPdfFolder($encrypted, "{$encrypted}/{$this->pdf->idPdfbatch}.zip");
		
		$this->removeFiles("{$this->appPath->path}/{$this->dir->fop}/{$this->pdf->idAccount}/");
		$this->removeFiles("{$this->appPath->path}/{$this->dir->explodedbatch}/{$this->pdf->idAccount}/{$this->pdf->idPdfbatch}/");
		
	}
	
	private function getXmlFolder()
	{
		return "{$this->appPath->path}/{$this->dir->sourcebatch}/{$this->pdf->idAccount}/{$this->pdf->idPdfbatch}.xml";
	}
	
	private function getXslFolder()
	{
		return "{$this->appPath->path}/{$this->dir->relativetemplatesfolder}/{$this->pdf->idPdftemplate}/{$this->pdf->idPdftemplate}.xsl";
	}
	
	private function getPdfFile()
	{
		$pdf = "{$this->appPath->path}/{$this->dir->fop}/{$this->pdf->idAccount}/";
		if (!file_exists($pdf)) {
			mkdir($pdf, 0777, true);
		}
		
		$pdf .= "{$this->pdf->idPdfbatch}.pdf";
		
		return $pdf;
	}
	
	private function getFopLogFile()
	{
		$log = "{$this->appPath->path}/{$this->dir->foplog}/{$this->pdf->idAccount}/";
		if (!file_exists($log)) {
			mkdir($log, 0777, true);
		}
		
		$log .= "log_{$this->pdf->idPdfbatch}.log";
		
		return $log;
	}
	
	private function getFopConf()
	{
		return "{$this->appPath->path}/{$this->dir->config}/fop.xconf";
	}
	
	private function getShellFile()
	{
		return "{$this->appPath->path}/{$this->dir->sourcebatch}/{$this->pdf->idAccount}/source_{$this->pdf->idPdfbatch}.sh"; 
	}
	
	private function getEncryptedFolder()
	{
		return "{$this->appPath->path}/{$this->dir->encryptedbatch}/{$this->pdf->idAccount}/{$this->pdf->idPdfbatch}";
	}
	
	private function getExplodedFolder()
	{
		return "{$this->appPath->path}/{$this->dir->explodedbatch}/{$this->pdf->idAccount}/{$this->pdf->idPdfbatch}/page_%02d.pdf";
	}
	
	private function createPdfMaster($xml, $xsl, $pdf, $fopConf, $log)
	{
		$output = array();
		$cmd = "fop -xml {$xml} -xsl {$xsl} -pdf {$pdf} -c {$fopConf} 2> {$log}";
		exec($cmd, $output, $status);
		
		if ($status) {
			$error = implode(', ', $output);
			throw new Exception("Se encontró un error mientras se creaba el PDF maestro: {$error}");
		}
	}
	
	private function burstPdf($pdf, $exploded)
	{
		$output = array();
		$cmd = "pdftk {$pdf} burst output {$exploded}";
		exec($cmd, $output, $status);
		
		if ($status) {
			$error = implode(', ', $output);
			throw new Exception("Se encontró un error mientras se particionaba el pdf maestro: {$error}");
		}
	}
	
	
	private function encryptePdf($cmd)
	{
		$output = array();
		$cmd = escapeshellcmd($cmd);
		exec($cmd, $output, $status);
		
		if ($status) {
			$error = implode(', ', $output);
			throw new Exception("Se encontró un error mientras se encriptaban los archivos PDF: {$error}");
		}
	}
	
	
	private function zipPdfFolder($source, $destiny)
	{
		$output = array();
		$cmd = escapeshellcmd("zip -rmj {$destiny} {$source}");
		exec($cmd, $output, $status);
		
		if ($status) {
			$error = implode(', ', $output);
			throw new Exception("Se encontró un error mientras se comprimían los archivos PDF: {$error}");
		}
	}
	
	private function updateProcessed()
	{
		$this->pdf->processed = $this->processed;
		$this->pdf->updatedon = time();

		if (!$this->pdf->save()) {
			foreach ($this->pdf->getMessages() as $m) {
				throw new Exception("Error while updating pdf batch: {$m}");
			}
		}
	}
	
	private function updateBatch()
	{
		$this->pdf->status = $this->status;
		$this->pdf->processed = $this->processed;
		$this->pdf->toProcess = $this->toProcess;
		$this->pdf->updatedon = time();

		if (!$this->pdf->save()) {
			foreach ($this->pdf->getMessages() as $m) {
				throw new Exception("Error while updating pdf batch: {$m}");
			}
		}
	}
	
	private function extractName()
	{
		$name = str_replace(" ", "_", $this->pdf->name);
		$name = strtolower($name);
		
		return $name;
	}
	
	private function removeFiles($dir)
	{
		$files = glob($dir . '*', GLOB_MARK);
		
		foreach ($files as $file) {
			unlink($file);
		}
	}
	
	private function searchPdfRecord()
	{
		$pdf = Pdfbatch::findFirstByIdPdfbatch($this->data->idPdfbatch);
		
		if (!$pdf) {
			throw new InvalidArgumentException("Pdf template do not exists...");
		}
		
		$this->pdf = $pdf;
	}
}