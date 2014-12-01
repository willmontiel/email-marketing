<?php

/**
 * How to catch unable connection with simple_html_dom
 * 
 * $ctx = stream_context_create(array(
 *	'http' => array('timeout' => 10)
 * ));
 * 
 * $html = file_get_html('http://mailing.sigmamovil.com/ss/display.php?M=6895334&C=7727f4803272bc13f058855d4f6249d2&L=1280&N=6035', false, $ctx);
 *
 * if (!$html) {
 *		echo "Unable connection\n";
 * }
 * else {
 *		Do your things
 * }
 */

class LoadHtml
{
	protected $logger;
	protected $asset;

	public function __construct() 
	{
		$this->asset = Phalcon\DI::getDefault()->get('asset');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->url = Phalcon\DI::getDefault()->get('url');
	}

	/**
	 * Función que descarga código html desde una url y la guarda las imagenes de ser necesario en una carpeta determinada en el servidor
	 * @param string $url 
	 * @param string $image 
	 * @param string $dir
	 * @return string
	 */
	public function gethtml($url, $image, $dir, Account $account, $script = false)
	{
		$some = new simple_html_dom();
		
		$ctx = stream_context_create(array(
			'http' => array('timeout' => 30)
		));
		
		$html = file_get_html($url, false, $ctx);
		
		if (!$html) {
			throw new Exception("Unable to connect with the server");
		}
		
		$this->logger->log("Se importó el html");
		
		$htmlbase = $html->find('head base');
		
		$this->logger->log("recorriendo las cabeceras html");
		if (count($htmlbase) > 0) {
			$htmlbase = $htmlbase[0];
		}
		if ($htmlbase && $image == 'load') {
			$base = $htmlbase->href;
			$htmlbase->outertext = '';
		}
		else {
			$path = pathinfo($url); 
			$base = $path['dirname'];
		}
		
		$this->base = $base;
		$this->dir = $dir;
		$this->account = $account;
		
		if ($image == "load") {		
			foreach($html->find('img') as $element) {
				if (trim($element->src) !== null && trim($element->src) !== '' && substr(trim($element->src), 0, 5) !== 'data:'  )  {
					$oldlocation = $element->src;
					$location = $this->addImageToMap($oldlocation);
					$element->src = $location;
				}
			}
		}
		
		if(!$script) {
			$busqueda = array("<script" , "</script>");
			$reemplazar = array("<!-- ", " -->");
		}
		else {
			$busqueda = array();
			$reemplazar = array();
		}
		
		$newhtml = str_replace($busqueda,$reemplazar, $html->__toString());
		
		$this->logger->log("Proceso finalizado");
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
		
		$imageInfo = getimagesize($imageurl);
		$imageSize = $this->getRemoteFileSize($imageurl);
		$dimensions = $imageInfo[0] . " x " . $imageInfo[1];
		
		$asset = $this->saveAssetInDB($path['basename'], $imageSize, $dimensions, $path['extension']);
		// Set local path
		$imagepath = $dir . '/' . $asset->idAsset . '.' . $path['extension'];
		
		$this->image_map[$img] = $imagepath;
		//$imagenewurl  = $this->asset->url . $this->account->idAccount . "/images/" . $asset->idAsset . '.' . $path['extension'];
	
		file_put_contents($imagepath, file_get_contents($imageurl));
		$thumbnail = new Thumbnail($this->account);
		
		$thumbnail->createThumbnail($asset, $imagepath, $path['basename']);
		
		// Los assets deben quedar de la siguiente forma para ser procesados en ChildCommunication "/asset/show/idAsset"
		
		$imagenewurl = $this->url->get('asset/show') . '/' . $asset->idAsset;
		
		return $imagenewurl;
	}
	
	public function getRemoteFileSize($url) {
        $info = get_headers($url,1);
 
        if (is_array($info['Content-Length'])) {
            $info = end($info['Content-Length']);
        }
        else {
            $info = $info['Content-Length'];
        }
 
        return $info;
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
		
		$asset->idAccount = $this->account->idAccount;
		$asset->fileName = $fileName;
		$asset->fileSize = $fileSize;
		$asset->dimensions = $dimensions;
		$asset->type = $type;
		$asset->createdon = time();
		
		if (!$asset->save()) {
			foreach ($asset->getMessages() as $msg) {
				$this->logger->log("Error: ". $msg);
				throw new \Exception("Exception: {$msg}");
			}
		}
		return $asset;
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