<?php
class TemplateObj
{
	private $account;
	private $user;
	private $templatesfolder;
	private $asset;
	private $url;
	private $cache;
	private $logger;
	
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->templatesfolder = Phalcon\DI::getDefault()->get('templatesfolder');
		$this->asset = Phalcon\DI::getDefault()->get('asset');
		$this->url = Phalcon\DI::getDefault()->get('url');
		$this->cache = Phalcon\DI::getDefault()->get('cache');
	}

	public function setAccount(Account $account = null)
	{
		if ($account == null) {
			$this->account = new stdClass();
			$this->account->idAccount = null;
		}
		else {
			$this->account = $account;
		}
	}
	
	public function setUser(User $user)
	{
		$this->user = $user;
	}
	
	public function setTemplate(Template $template)
	{
		$this->template = $template;
	}
	
	public function createTemplate($name, $category, $editorContent)
	{
		$this->logger->log('Empezando proceso de creacion de plantillas');
		
		$template = $this->saveTemplateInDb($name, $category);
		$content = $this->saveTemplateInFolder($template->idTemplate, $editorContent);
		$this->updateContentHtml($template, $content->content, $content->contentHtml);
	}
	
	public function updateTemplate($name, $category, $editorContent)
	{
		$this->logger->log('Empezando proceso de edición de plantillas');
		$content = $this->saveTemplateInFolder($this->template->idTemplate, $editorContent);
		$this->updateTemplateInDb($name, $category, $content->content, $content->contentHtml);
	}
	
	protected function saveTemplateInDb($name, $category)
	{
		$this->logger->log('Iniciando guardado de la plantilla');
		
		$this->startTransaction();
		$template = new Template();
		$template->idAccount = $this->account->idAccount;
		$template->name = $name;
		$template->category = $category;
		$template->content = null;
		$template->contentHtml = null;
		
		if (!$template->save()) {
			$this->rollbackTransaction();
			throw new Exception('we have a error while saving new template...');
		}
		return $template;
	}
	
	protected function updateTemplateInDb($name, $category, $content, $html)
	{
		$this->template->idAccount = $this->account->idAccount;
		$this->template->name = $name;
		$this->template->category = $category;
		$this->template->content = $content;
		$this->template->contentHtml = $html;

		$preview = $this->cache->get('preview-img64-cache-' . $this->user->idUser);
//			$this->logger->log('Preview: ' . $preview);
		if ($preview) {
			$this->template->previewData = $preview;
			$this->cache->delete('preview-img64-cache-' . $this->user->idUser);
		}

		if (!$this->template->save()) {
			foreach ($this->template->getMessages() as $msg) {
				$this->logger->log('Error: ' . $msg);
				throw new Exception('Error while updating template');
			}
		}
	}
	
	protected function saveTemplateInFolder($idTemplate, $editorContent)
	{
		$content = $this->convertToHtml($editorContent);
		
		if($this->account->idAccount != null){
			$dir = $this->asset->dir . $this->account->idAccount . '/templates/' . $idTemplate . '/images/';
			$this->logger->log('Plantilla local');
		}
		else {
			$dir = $this->templatesfolder->dir . $idTemplate . '/images/';
			$this->logger->log('Plantilla global');
		}
		
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		} 

		$html = new DOMDocument();
		@$html->loadHTML($content);
		$images = $html->getElementsByTagName('img');

		if ($images->length !== 0) {
			$find = array();
			$replace = array();
			
			foreach ($images as $image) {
				$src = $image->getAttribute('src');
				if ($this->validateImageSrc($src)) {
					$idAsset = filter_var($src, FILTER_SANITIZE_NUMBER_INT);

					$this->logger->log('idAsset: ' . $idAsset);

					$asset = Asset::findFirst(array(
						"conditions" => "idAsset = ?1",
						"bind" => array(1 => $idAsset)
					));

					if (!$asset) {
						$this->rollbackTransaction();
						throw new Exception('Error, asset not found!');
					}

					$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
					$templateImage = $this->saveTemplateImage($idTemplate, $asset->fileName);
					$img = $this->asset->dir . $asset->idAccount . "/images/" . $asset->idAsset . "." .$ext;

					if (!copy($img, $dir . $templateImage->idTemplateImage . '.' .$ext)) {
						throw new Exception('Error while copying image files');
					}

					$find[] = $src;
					$replace[] = $this->url->get('template/image') . '/' . $idTemplate . '/' .$templateImage->idTemplateImage;
				}
			}
//			$this->logger->log('Find: ' . print_r($find, true));
//			$this->logger->log('Replace: ' . print_r($replace, true));
			$editorContent = str_replace($find, $replace, $editorContent);
		}
//		$this->logger->log('Urls transformados');
		$contentHtml = $this->convertToHtml($editorContent);
		
		$templateContent = new stdClass();
		$templateContent->content = $editorContent;
		$templateContent->contentHtml = $contentHtml;
		
		return $templateContent;
	}
	
	private function convertToHtml($editorContent)
	{
		$editorObj = new HtmlObj;
		$editorObj->assignContent(json_decode($editorContent));
		$content = $editorObj->render();
		
		return $content;
	}
	
	
	private function validateImageSrc($src){
		
		if (!preg_match('/asset\/show/', $src)) {
			return false;	
		}
		return true;
		
	}
	
	private function saveTemplateImage($idTemplate, $name)
	{
		$this->logger->log('Guardando imagen de plantilla');
		$templateImage = new Templateimage();
		
		$templateImage->idTemplate = $idTemplate;
		$templateImage->name = $name;
		
		if (!$templateImage->save()) {
			$this->rollbackTransaction();
			throw new Exception('Error while saving templateimage');
		}
		return $templateImage;
	}
	
	private function updateContentHtml($template, $newContent, $newContentHtml)
	{
		$template->content = $newContent;
		$template->contentHtml = htmlspecialchars($newContentHtml, ENT_QUOTES);
		
		$preview = $this->cache->get('preview-img64-cache-' . $this->user->idUser);
		$this->logger->log('Preview: ' . $preview);
		if (!$preview) {
			$preview = null;
		}
		$this->cache->delete('preview-img64-cache-' . $this->user->idUser);
		
		$template->previewData = $preview;
		
		if (!$template->save()) {
			$this->rollbackTransaction();
			throw new Exception('Error while updating template');
		}
		$this->commitTransaction();
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
