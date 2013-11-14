<?php
class TemplateObj
{
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		$this->templatesfolder = $di['templatesfolder'];
		$this->asset = $di['asset'];
		$this->url = $di['url'];
	}
	
	public function createTemplate($name, $category, $content, Account $account = null)
	{
		if ($account == null) {
			$idAccount = null;
		}
		else {
			$idAccount = $account->idAccount;
		}
		
		$template = $this->saveTemplateInDb($idAccount, $name, $category);
		$newContent = $this->saveTemplateInFolder($template->idTemplate, $content, $idAccount);
		
		$editorObj = new HtmlObj;
		$editorObj->assignContent(json_decode($newContent));
		$contentHtml = $editorObj->render();
		
		$this->updateContentHtml($template, $newContent, $contentHtml);
		
		return true;
	}
	
	protected function saveTemplateInDb($idAccount, $name, $category)
	{
		$template = new Template();
		$template->idAccount = $idAccount;
		$template->name = $name;
		$template->category = $category;
		$template->content = null;
		$template->contentHtml = null;
		
		if (!$template->save()) {
			throw new InvalidArgumentException('we have a error...');
		}
		return $template;
	}
	
	protected function saveTemplateInFolder($idTemplate, $content, $idAccount)
	{
		$contentJson = json_decode($content);
		
		if($idAccount){
			$dir = $this->asset->dir . $idAccount . '/templates/' . $idTemplate . '/images/';
			
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			
			return $content;
			
		}
		else {
			$dir = $this->templatesfolder->dir . $idTemplate . '/images/';

			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			} 

			$find = array();
			$replace = array();
			foreach ($contentJson->dz as $zone) {
				foreach ($zone->content as $data) {
					$imgTag = new DOMDocument();
					if (is_object($data->contentData )) {
						$imgTag->loadHTML($data->contentData->image);
					}
					else {
						$imgTag->loadHTML($data->contentData);
					}

					$src = $imgTag->getElementsByTagName('img');

					if ($src->length !== 0) {
						$idAsset = filter_var($src->item(0)->getAttribute('src'), FILTER_SANITIZE_NUMBER_INT);

						$asset = Asset::findFirst(array(
							"conditions" => "idAsset = ?1",
							"bind" => array(1 => $idAsset)
						));

						$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
						$templateImage = $this->saveTemplateImage($idTemplate, $asset->fileName);

						$img = $this->asset->dir . $asset->idAccount . "/images/" . $asset->idAsset . "." .$ext;

						if (!copy($img, $dir . $templateImage->idTemplateImage . '.' .$ext)) {
							throw new InvalidArgumentException('Error while copying image files');
						}
						$find[] = $src->item(0)->getAttribute('src');
						$replace[] = $this->url->get('template/image') . '/' . $idTemplate . '/' .$templateImage->idTemplateImage;
					}
				}
			}
			$newContent = str_replace($find, $replace, $content);
			return $newContent;
		}
	}
	
	private function saveTemplateImage($idTemplate, $name)
	{
		$templateImage = new Templateimage();
		
		$templateImage->idTemplate = $idTemplate;
		$templateImage->name = $name;
		
		if (!$templateImage->save()) {
			throw new InvalidArgumentException('Error while saving templateimage');
		}
		return $templateImage;
	}
	
	private function updateContentHtml($template, $newContent, $newContentHtml)
	{
		$template->content = $newContent;
		$template->contentHtml = htmlspecialchars($newContentHtml, ENT_QUOTES);
		
		if (!$template->save()) {
			throw new InvalidArgumentException('Error while updating template');
		}
	}
}
