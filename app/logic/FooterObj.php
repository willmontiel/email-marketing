<?php
//Phalcon\DI::getDefault()->get('logger')
class FooterObj
{
	private $footersfolder;
	private $footer;
	private $asset;
	private $url;
	
	function __construct()
	{
		$this->footersfolder = Phalcon\DI::getDefault()->get('footersfolder');
		$this->asset = Phalcon\DI::getDefault()->get('asset');
		$this->url = Phalcon\DI::getDefault()->get('url');
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setFooter(Footer $footer)
	{
		$this->footer = $footer;
	}

	
	public function createFooter($content, $name)
	{
		$this->startTransaction();
		
		$this->initializeFooter($name);
		$finaleditorjson = $this->saveImagesInFolder($content);
		$footercontent = $this->getHtmlAndText(json_decode($finaleditorjson));
		
		$this->footer->editor = $finaleditorjson;
		$this->footer->html = $footercontent->html;
		$this->footer->plainText = $footercontent->plaintext;
		
		if (!$this->footer->save()) {
			$this->rollbackTransaction();
			foreach ($this->footer->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log($msg);
			}
			throw new Exception("we have a error while saving new footer...");
		}
		$this->commitTransaction();
	}
	
	public function updateFooter($content, $name)
	{
		$this->startTransaction();
		
		$finaleditorjson = $this->saveImagesInFolder($content);
		$footercontent = $this->getHtmlAndText(json_decode($finaleditorjson));
		
		$this->footer->name = $name;
		$this->footer->editor = $finaleditorjson;
		$this->footer->html = $footercontent->html;
		$this->footer->plainText = $footercontent->plaintext;
		if (!$this->footer->save()) {
			$this->rollbackTransaction();
			foreach ($this->footer->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log($msg);
			}
			throw new Exception("we have a error while saving new footer...");
		}
		$this->commitTransaction();
	}
	
	protected function initializeFooter($name)
	{
		$this->footer = new Footer();
		$this->footer->name = $name;
		$this->footer->editor = null;
		$this->footer->html = null;
		$this->footer->plainText = null;
		
		if (!$this->footer->save()) {
			$this->rollbackTransaction();
			foreach ($this->footer->getMessages() as $msg) {
				throw new Exception("we have a error while saving new footer... {$msg}");
			}
		}
	}

	protected function getHtmlAndText($content)
	{
		$html = $this->getHtmlFromEditor($content);
		
		$textobj = new PlainText();
		$plainText = $textobj->getPlainText($html);
		
		$obj = new stdClass();
		$obj->html = $html;
		$obj->plaintext = (!empty($plainText)) ? $plainText : '==Plain Text==' ;
		return $obj;
	}
	
	protected function getHtmlFromEditor($content)
	{
		$htmleditor = '';
		foreach ($content as $row) {
			$editor = new HtmlRow();
			$row->widthval = 600;
			$editor->assignContent($row);
			$htmleditor.= '<tr>' . $editor->render() . '</tr>';
		}
		$html = '<center><table style="width: 600px;" width="600px" cellspacing="0" cellpadding="0" >' . $htmleditor . '</table></center>';
		return $html;
	}

	protected function saveImagesInFolder($content)
	{
		$htmlcontent = $this->getHtmlFromEditor(json_decode($content));
		$dir = $this->footersfolder->dir . 'global/';
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$html = new DOMDocument();
		@$html->loadHTML($htmlcontent);
		$images = $html->getElementsByTagName('img');
		
		if ($images->length !== 0) {
			$find = array();
			$replace = array();
			
			foreach ($images as $image) {
				$src = $image->getAttribute('src');
				if (preg_match('/asset\/show/', $src)) {
					
					$url = explode('/', $src);
					$key = (count($url)-1);
					
					$idAsset = $url[$key];
					
					$asset = Asset::findFirst(array(
						"conditions" => "idAsset = ?1",
						"bind" => array(1 => $idAsset)
					));
					
					if (!$asset) {
						throw new Exception('Error, asset not found!');
					}
					
					$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
					$footerImage = $this->saveFooterImage($asset);
					$img = $this->asset->dir . $asset->idAccount . "/images/" . $asset->idAsset . "." .$ext;
					
					if (!copy($img, $dir . $footerImage->idFooterImage . '.' .$ext)) {
						$this->rollbackTransaction();
						throw new Exception("Error while copying image file with name {$footerImage->idFooterImage}.{$ext}");
					}
					
					$find[] = $src;
					$replace[] = $this->url->get('footer/image') . '/' . $this->footer->idFooter . '/' .$footerImage->idFooterImage;
				}
			}
		}
		
		$finaleditor = str_replace($find, $replace, $content);
		
		return $finaleditor;
	}
	
	protected function saveFooterImage(Asset $asset)
	{
		$footerImage = new Footerimage();
		
		$footerImage->idFooter = $this->footer->idFooter;
		$footerImage->name = $asset->fileName;
		
		if (!$footerImage->save()) {
			foreach ($footerImage->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log("Error when saving Footer Image: {$msg}");
			}
			$this->rollbackTransaction();
			throw new Exception('Error while saving templateimage');
		}
		return $footerImage;
	}

	public function setFooterEditorObj($footer)
	{
		$editor = new stdClass();
		$editor->layout = new stdClass();
		$editor->layout->id = 6;
		$editor->layout->description = "Layout for Footer";
		$editor->layout->name = "layout-footer";
		
		$editorfooter = new stdClass();
		$editorfooter->name = "footer";
		$editorfooter->width = "full-width";
		$editorfooter->widthval = 600;
		$editor->layout->zones = array($editorfooter);
		
		
		$editor->dz = new stdClass();
		$editor->dz->footer = new stdClass();
		$editor->dz->footer->background_color = "#FFFFFF";
		$editor->dz->footer->border_color = "#FFFFFF";
		$editor->dz->footer->border_style = "none";
		$editor->dz->footer->border_width = 0;
		$editor->dz->footer->content = $footer;
		$editor->dz->footer->corner_bottom_left = 0;
		$editor->dz->footer->corner_bottom_right = 0;
		$editor->dz->footer->corner_top_left = 0;
		$editor->dz->footer->corner_top_right = 0;
		$editor->dz->footer->name = "footer";
		$editor->dz->footer->parent = "#edit-area";
		$editor->dz->footer->width = "full-width";
		$editor->dz->footer->widthval = 600;
		
		return json_encode($editor);
	}
	
	public function addFooterInHtml($html)
	{
		if(isset($this->account) && $this->account->footerEditable == 0) {
			$footer = Footer::findFirstByIdFooter($this->account->idFooter);
			$html.= $footer->html;
		}
		
		$search = array("\xe2\x80\x8b", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x9f", "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9b", "á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ");
		$replace = array('', '"', '"', '"', "'", "'", "'", "á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ");
		$response= str_replace($search, $replace, $html);
		
		return $response;
	}
	
	public function cloneContent(Footer $footer)
	{
		$newfooter = new Footer();
		$newfooter->name = substr($footer->name . " (copia)", 0, 79);
		$newfooter->editor = $footer->editor;
		$newfooter->html = $footer->html;
		$newfooter->plainText = $footer->plainText;

		if (!$newfooter->save()) {
			foreach ($newfooter->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log("Error when cloning Footer: {$msg}");
			}
			throw new Exception("Error when cloning Footer");
		}
		
		return $newfooter;
	}
	
	protected function startTransaction()
	{
		Phalcon\DI::getDefault()->get('db')->begin();
	}
	
	protected function commitTransaction()
	{
		Phalcon\DI::getDefault()->get('db')->commit();
	}
	
	protected function rollbackTransaction()
	{
		Phalcon\DI::getDefault()->get('db')->rollback();
	}
}