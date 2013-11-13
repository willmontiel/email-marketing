<?php
class TemplateObj
{
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->assetsfolder = $di['asset'];
		$this->templatesfolder = $di['templatesfolder'];
		$this->asset = $di['asset'];
		$this->url = $di['url'];
	}
	
	public function createTemplate($name, $category, $content, Account $account = null)
	{
		$editorObj = new HtmlObj;
		$editorObj->assignContent(json_decode($content));
		$contentHtml = $editorObj->render();
		$this->log->log('Html: ' . $contentHtml);
		
		if ($account == null) {
			$idAccount = null;
		}
		else {
			$idAccount = $account->idAccount;
		}
		
		$template = $this->saveTemplateInDb($idAccount, $name, $category, $content, $contentHtml);
		$newContentHtml = $this->saveTemplateInFolder($template->idTemplate, $contentHtml, $idAccount);
		$this->updateContentHtml($template, $newContentHtml);
	}
	
	protected function saveTemplateInDb($idAccount, $name, $category, $content, $contentHtml)
	{
		$template = new Template();
		$template->idAccount = $idAccount;
		$template->name = $name;
		$template->category = $category;
		$template->content = $content;
		$template->contentHtml = $contentHtml;
		
		if (!$template->save()) {
			throw new InvalidArgumentException('we have a error...');
		}
	
		return $template;
	}
	
	protected function saveTemplateInFolder($idTemplate, $contentHtml, $idAccount)
	{
		if($idAccount){
			$dir = $this->assetsfolder->dir . $idAccount . '/templates/' . $idTemplate . '/images/';
		}
		else {
			$dir = $this->templatesfolder->dir . $idTemplate . '/images/';
		}
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		} 
		
		$srcs = $this->getImageSrc($contentHtml);
		
		$find = array();
		$replace = array();
		foreach ($srcs as $src) {
			$idAsset = filter_var($src->getAttribute('src'), FILTER_SANITIZE_NUMBER_INT);
			$asset = Asset::findFirst(array(
				"conditions" => "idAsset = ?1",
				"bind" => array(1 => $idAsset)
			));
		
			$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
			
			$templateImage = $this->saveTemplateImage($idTemplate, $asset->fileName);
			
			$find[] = $src->getAttribute('src');
			$replace[] = $this->url->get('template/image') . '/' . $idTemplate . '/' .$templateImage->idTemplateImage;
		
			$img = $this->asset->dir . $asset->idAccount . "/images/" . $asset->idAsset . "." .$ext;
			copy($img, $dir . $templateImage->idTemplateImage . '.' .$ext);
		}
		$newContentHtml = $this->replaceSrc($find, $replace, $contentHtml);
		return $newContentHtml;
	}
	
	private function saveTemplateImage($idTemplate, $name)
	{
		$templateImage = new Templateimage();
		
		$templateImage->idTemplate = $idTemplate;
		$templateImage->name = $name;
		
		if (!$templateImage->save()) {
			throw new InvalidArgumentException('Erro while saving templateimage');
		}
		return $templateImage;
	}
		
	private function updateContentHtml($template, $newContentHtml)
	{
		$template->contentHtml = htmlspecialchars($newContentHtml, ENT_QUOTES);
		
		if (!$template->save()) {
			$this->log->log("No guardó");
			throw new InvalidArgumentException('Erro while updating template');
		}
	}
	
	private function getImageSrc($data)
	{
		$imgTag = new DOMDocument();
		$imgTag->loadHTML($data);
		
		$srcs = $imgTag->getElementsByTagName('img');
		return $srcs;
	}
	
	private function replaceSrc($find, $replace, $contentHtml)
	{
		$newContent = str_replace($find, $replace, $contentHtml);
		return $newContent;
	}
}