<?php

class PdfCreator extends BaseWrapper
{
	private $logger;
	private $appPath;
	private $dir;
	private $data;
	
	public function __construct() 
	{
		$this->appPath = Phalcon\DI::getDefault()->get('appPath');
		$this->dir = Phalcon\DI::getDefault()->get('pdftemplates');
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
		$csvFile = "{$this->appPath->path}/{$this->dir->folderrelative}";
		//Validamos si existe el archivo CSV para obtener la estructura XML
		if (file_exists($fileOutputCsv)) {
			// Convertimos el archivo de csv con la informacion de los clientes a CSV
			$fileCsv = new ProcessFile();

			$xml = new CodificXml();
			$fileCsv->addElementProcessor($xml);

			$InfoBd = new SaveInfoBD();
			$fileCsv->addElementProcessor($InfoBd);

			$shell = new CodificShell();
			$fileCsv->addElementProcessor($shell);


			$fileCsv->setSourceFile($fileOutputCsv);

			$result = $fileCsv->process();

			//Si el procesamiento fue correcto guardamos la informacion
			if ($result) {
				//$idenvioInter = str_pad($idenvio, 4, '0', STR_PAD_LEFT);
				$shell->save($idenvio, $fileOutpuShell);
				$xml->save($idenvio);
				$InfoBd->save($idenvio);
			}


			echo "se ha terminado la conversion del archivo a XML" . "\n";
		}
	}
}