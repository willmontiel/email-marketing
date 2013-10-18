<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class LoadHtml
{
	/**
	 *
	 * @var \Phalcon\Logger\Adapter\File
	 */
	protected $log = null;
	
	protected function di()
    {
        return \Phalcon\DI\FactoryDefault::getDefault();
    }
	
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File("../app/logs/debug.log");
		$di = $this->di();
		$this->asset = $di['asset'];
	}

	/**
	 * Función que descarga código html desde una url y la guarda las imagenes de ser necesario en una carpeta determinada en el servidor
	 * @param string $url 
	 * @param boolean $image 
	 * @param string $dir
	 * @return string
	 */
	public function gethtml($url, $image, $dir, $idAccount)
	{
		$some = new simple_html_dom();
		
		$html = file_get_html($url);
		
		$htmlbase = $html->find('head base');
		if (count($htmlbase) > 0) {
			$htmlbase = $htmlbase[0];
		}
		if ($htmlbase && $image == 'load') {
//			echo "Found base: {$htmlbase} - href={$htmlbase->href}\n\n";
			$base = $htmlbase->href;
			$htmlbase->outertext = '';
		}
		else {
			$path = pathinfo($url); 
			$base = $path['dirname'];
		}
		
		$this->base = $base;
		$this->dir = $dir;
		$this->idAccount = $idAccount;
		
		if ($image == "load") {		
			foreach($html->find('img') as $element) {
				if (trim($element->src) !== null && trim($element->src) !== '' && substr(trim($element->src), 0, 5) !== 'data:'  )  {
					$oldlocation = $element->src;
					$location = $this->addImageToMap($oldlocation);
					$element->src = $location;
					$this->log->log('Locations [old: ' . $oldlocation . '], [new: ' . $location . '], Base: '. $this->asset->url);

				}
			}
		}
		
		$busqueda = array("<script" , "</script>");
		$reemplazar = array("<!-- ", " -->");
		
		$newhtml = str_replace($busqueda,$reemplazar, $html->__toString());
		$this->log->log("esto es: " . $newhtml);
		return $newhtml;
		
	}
	
	private function addImageToMap($img)
	{
		$urlbase = $this->base;
		$dir = $this->dir;
		
		if (in_array($img, $this->image_map)) {
			return $this->image_map[$img];
		}

		$path = pathinfo($img);
		
		// URL remoto (no hace parte de la pagina)
		// no se afecta
		if (substr($path['dirname'], 0, 7) == 'http://' || substr($path['dirname'], 0, 8) == 'https://') {
			$this->image_map[$img] = $img;
			return $img;
		}
		
		if (substr($path['dirname'], 0, 1) == '/') {
			// URL absoluto
			$imageurl = $this->getURLbase($urlbase) . $img;
		}
		else {
			// URL relativo
			$imageurl = $urlbase . '/' . $img;
		}

		// Set local path
		$imagepath = $dir . '/' . $path['basename'];
		
		$this->image_map[$img] = $imagepath;
		$imagenewurl  = $this->asset->url . $this->idAccount . "/images/" . $path['basename'];
	
		file_put_contents($imagepath, file_get_contents($imageurl));
		$imageInfo = getimagesize($imageurl);
		$imageSize = filesize($imagepath);
		$dimensions = $imageInfo[0] . " x " . $imageInfo[1];
		
		
		$this->saveAssetInDB($path['basename'], $imageSize, $dimensions, $path['extension']);
		
		return $imagenewurl;
	}
	
	/**
	 * Funcion que se encarga de guardar la información de cada asset cargado en la base de datos
	 * @param string $fileName
	 * @param int $fileSize
	 * @param string $dimensions
	 * @param string $type
	 */
	public function saveAssetInDB($fileName, $fileSize, $dimensions = null, $type = null)
	{
		
		$asset = new Asset();
		
		$asset->idAccount = $this->idAccount;
		$asset->fileName = $fileName;
		$asset->fileSize = $fileSize;
		$asset->dimensions = $dimensions;
		$asset->type = $type;
		$asset->createdon = time();
		
		if (!$asset->save()) {
			foreach ($asset->getMessages() as $msg) {
				$this->log->log("Error: ". $msg);
			}
		}
	}
	
	private function getURLbase($url)
	{
		$pinfo = parse_url($url);
		$urlr = (isset($pinfo['scheme']))?$pinfo['scheme']:'http';
		$urlr .= '://';
		$urlr .= (isset($pinfo['host']))?$pinfo['host']:'';
		$urlr .= (isset($pinfo['port']))?':' . $pinfo['port']:'';
		$urlr .= (isset($pinfo['user']))?':' . $pinfo['user']:'';
		$urlr .= (isset($pinfo['pass']))?'@' . $pinfo['pass']:'';

		return $urlr;
	}
}