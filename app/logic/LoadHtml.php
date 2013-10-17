<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class LoadHtml
{
	/**
	 * Función que descarga código html desde una url y la guarda las imagenes de ser necesario en una carpeta determinada en el servidor
	 * @param string $url 
	 * @param boolean $image 
	 * @param string $dir
	 * @return simple_html_dom
	 */
	public function gethtml($url, $image, $dir)
	{
		$some = new simple_html_dom();
		
		$html = file_get_html($url);
		
		$htmlbase = $html->find('head base');
		
		if (count($htmlbase) > 0 AND $image == "load") {
			$htmlbase = $htmlbase[0];
		}
		
		if ($htmlbase) {
			$base = $htmlbase->href;
			$htmlbase->outertext = '';
		}
		
		else {
			$path = pathinfo($dir); 
			$base = $path['dirname'];
		}
		
		if ($image == "load") {		
			foreach($html->find('img') as $element) {
				if (trim($element->src) !== null && trim($element->src) !== '') {
					$oldlocation = $element->src;
					$location = $this->addImageToMap($oldlocation, $base, $dir);
	
					$element->src = $location;
				}
			}
		}
		
		return $html;
		
	}
	
	private function addImageToMap($img, $urlbase, $dir)
	{
		$log = new Phalcon\Logger\Adapter\File("../app/logs/debug.log");
		
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
		$log->log("este es el nombre: " . $path['basename']);
		$this->image_map[$img] = $imagepath;
		$imagenewurl  = $this->asset->url .  $path['basename'];
		
		// DOWNLOAD
//		echo "Downloading {$imageurl} into {$imagepath} and referencing it as: {$imagenewurl}\n";
		file_put_contents($imagepath, file_get_contents($imageurl));
		
//		$this->saveAssetInDB($path['basename'], $path['extension'], $dimensions, $type);
		
		return $imagenewurl;
	}
	
	/**
	 * Funcion que se encarga de guardar la información de cada asset cargado en la base de datos
	 * @param string $fileName
	 * @param int $fileSize
	 * @param string $dimensions
	 * @param string $type
	 */
	private function saveAssetInDB($fileName, $fileSize, $dimensions = null, $type)
	{
		$asset = new Asset();
		
		$asset->idAccount = $this->user->account->idAccount;
		$asset->fileName = $fileName;
		$asset->fileSize = $fileName;
		$asset->dimensions = $fileName;
		$asset->Type = $type;
		$asset->createdon = time();
		
		$asset->save();
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