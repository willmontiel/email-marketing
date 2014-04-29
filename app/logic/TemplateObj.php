<?php
class TemplateObj
{
	private $account;
	private $mail;
	private $user;
	private $templatesfolder;
	private $template;
	private $asset;
	private $url;
	private $cache;
	private $logger;
	private $global;
	
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
		$this->templatesfolder = Phalcon\DI::getDefault()->get('templatesfolder');
		$this->asset = Phalcon\DI::getDefault()->get('asset');
		$this->url = Phalcon\DI::getDefault()->get('url');
		$this->cache = Phalcon\DI::getDefault()->get('cache');
	}

	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setGlobal($global = false)
	{
		if ($global == 'true') {
			$this->global = true;
		}
		else {
			$this->global = false;
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
	
	public function setMail(Mail $mail = null)
	{
		$this->mail = $mail;
	}
	
	public function convertMailToTemplate($name, $category, Mailcontent $mailContent)
	{
		$this->logger->log('Empezando proceso de creacion de plantilla a partir de un correo');
		
		$this->saveTemplateInDb($name, $category);
		$content = $this->saveTemplateInFolder($mailContent->content);
		$this->updateContentHtmlFromMail($content);
	}
	
	public function createTemplate($name, $category, $editorContent)
	{
		$this->logger->log('Empezando proceso de creacion de plantillas');
		$this->saveTemplateInDb($name, $category);
		$content = $this->saveTemplateInFolder($editorContent);
		$this->updateContentHtml($content);
		
		return $this->template->idTemplate;
	}
	
	public function updateTemplate($name, $category, $editorContent)
	{
		$this->logger->log('Empezando proceso de edición de plantillas');
		$this->startTransaction();
		$content = $this->updateTemplateImageUbication($editorContent);
		$this->updateTemplateInDb($name, $category, $content);
	}
	
	protected function saveTemplateInDb($name, $category)
	{
		$this->startTransaction();
		$template = new Template();
		
		if ($this->global && $this->user->userrole == "ROLE_SUDO") {
			$this->logger->log('Iniciando guardado de plantilla pública');
			$template->idAccount = null;
		}
		else {
			$this->logger->log('Iniciando guardado de plantilla privada');
			$template->idAccount = $this->account->idAccount;
		}
		$template->name = $name;
		$template->category = $category;
		$template->content = null;
		$template->contentHtml = null;
		
		if (!$template->save()) {
			$this->rollbackTransaction();
			foreach ($template->getMessages() as $msg) {
				throw new Exception("we have a error while saving new template... {$msg}");
			}
		}
		
		$this->template = $template;
	}
	
	protected function updateTemplateInDb($name, $category, $content)
	{
		if ($this->global) {
			$this->template->idAccount = null;
		}
		else {
			$this->template->idAccount = $this->account->idAccount;
		}
		$this->template->name = $name;
		$this->template->category = $category;
		
		if ($content != null) {
			$this->template->content = $content->content;
			$this->template->contentHtml = $content->contentHtml;
		}
		
		$preview = $this->cache->get('preview-img64-cache-' . $this->user->idUser);
		if ($preview) {
			$this->template->previewData = $preview;
			$this->cache->delete('preview-img64-cache-' . $this->user->idUser);
		}

		if (!$this->template->save()) {
			foreach ($this->template->getMessages() as $msg) {
				throw new Exception("Error while updating template... {$msg}");
			}
		}
		$this->commitTransaction();
	}
	
	protected function saveTemplateInFolder($editorContent)
	{
		$content = $this->convertToHtml($editorContent);
		
		if ($this->global && $this->user->userrole == "ROLE_SUDO") {
			$dir = $this->templatesfolder->dir . $this->template->idTemplate . '/images/';
		}
		else {
			$dir = $this->asset->dir . $this->account->idAccount . '/templates/' . $this->template->idTemplate . '/images/';
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
				$this->logger->log("Src: {$src}");
				if ($this->validateImageSrc($src)) {
					
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
					$templateImage = $this->saveTemplateImage($asset);
					$img = $this->asset->dir . $asset->idAccount . "/images/" . $asset->idAsset . "." .$ext;
					
					$this->logger->log("Img: {$img}");
					$this->logger->log('Dir: ' . $dir . $templateImage->idTemplateImage . '.' .$ext);
					if (!copy($img, $dir . $templateImage->idTemplateImage . '.' .$ext)) {
						$this->rollbackTransaction();
						throw new Exception("Error while copying image file with name {$templateImage->idTemplateImage}.{$ext}");
					}
					
					$find[] = $src;
					$replace[] = $this->url->get('template/image') . '/' . $this->template->idTemplate . '/' .$templateImage->idTemplateImage;
				}
			}
		}
		
		$newEditorContent = str_replace($find, $replace, $editorContent);
		
		$finalContent = new stdClass();
		$finalContent->content = $newEditorContent;
		$finalContent->contentHtml = $this->convertToHtml($newEditorContent);

		return $finalContent;	
	}
	
	
	private function updateTemplateImageUbication($editorContent)
	{
		if ($this->global && $this->user->userrole == "ROLE_SUDO") {
			if ($this->template->idAccount == null) {
				$newContent =  $this->saveTemplateInFolder($editorContent);
				return $newContent;
			}
			else {
				$newContent = $this->saveTemplateInFolder($editorContent);
				$this->transformImageTemplatePrivateInPublic($newContent->content);
				return $newContent;
			}
		}
		else {
			if ($this->template->idAccount == null) {
				$this->logger->log('is here now');
				$newContent =  $this->saveTemplateInFolder($editorContent);
				$this->transformImageTemplatePublicInPrivate($newContent);
				return $newContent;
			}
			else {
				$this->logger->log('is here');
				return $this->saveTemplateInFolder($editorContent);
			}
		}
	}
	
	private function transformImageTemplatePublicInPrivate($editorContent)
	{
		$destiny = "{$this->asset->dir}{$this->account->idAccount}/templates/{$this->template->idTemplate}/images/";
		
		if (!file_exists($destiny)) {
			mkdir($destiny, 0777, true);
		} 
		
		$content = $this->convertToHtml($editorContent);
		
		$html = new DOMDocument();
		@$html->loadHTML($content);
		$images = $html->getElementsByTagName('img');

		if ($images->length !== 0) {
			foreach ($images as $image) {
				$src = $image->getAttribute('src');
				$this->logger->log("Src: {$src}");
				if ($this->validateImageTemplateSrc($src)) {
					$url = explode('/', $src);
					$key = (count($url)-1);
					
					$idTemplateImg = $url[$key];
					
					$templateImg = Templateimage::findFirst(array(
						"conditions" => "idTemplateImage = ?1",
						"bind" => array(1 => $idTemplateImg)
					));
					
					if (!$templateImg) {
						throw new Exception('Error, asset not found!');
					}
					
					$ext = pathinfo($templateImg->name, PATHINFO_EXTENSION);
						
//					$destiny = "{$this->templatesfolder->dir}{$this->template->idTemplate}/images/{$idTemplateImg}.{$ext}";
					$destiny = "{$this->asset->dir}{$this->account->idAccount}/templates/{$this->template->idTemplate}/images/{$idTemplateImg}.{$ext}";
					if (!file_exists($destiny)) {
						$source = "{$this->templatesfolder->dir}{$this->template->idTemplate}/images/{$idTemplateImg}.{$ext}";
						$this->logger->log("Source: {$source}");
						$this->logger->log("Destiny: {$destiny}");
						if (!copy($source, $destiny)) {
							throw new Exception("Error while copying image file with name {$idTemplateImg}.{$ext}");
						}
					}
				}
			}
		}
	}
	
	private function transformImageTemplatePrivateInPublic($editorContent)
	{
		$destiny = $this->templatesfolder->dir . $this->template->idTemplate . '/images/';
		
		if (!file_exists($destiny)) {
			mkdir($destiny, 0777, true);
		} 
		
		$content = $this->convertToHtml($editorContent);
		
		$html = new DOMDocument();
		@$html->loadHTML($content);
		$images = $html->getElementsByTagName('img');

		if ($images->length !== 0) {
			foreach ($images as $image) {
				$src = $image->getAttribute('src');
				$this->logger->log("Src: {$src}");
				if ($this->validateImageTemplateSrc($src)) {
					$url = explode('/', $src);
					$key = (count($url)-1);
					
					$idTemplateImg = $url[$key];
					
					$templateImg = Templateimage::findFirst(array(
						"conditions" => "idTemplateImage = ?1",
						"bind" => array(1 => $idTemplateImg)
					));
					
					if (!$templateImg) {
						throw new Exception('Error, asset not found!');
					}
					
					$ext = pathinfo($templateImg->name, PATHINFO_EXTENSION);
						
					$destiny = "{$this->templatesfolder->dir}{$this->template->idTemplate}/images/{$idTemplateImg}.{$ext}";
					
					if (!file_exists($destiny)) {
						$source = "{$this->asset->dir}{$this->account->idAccount}/templates/{$this->template->idTemplate}/images/{$idTemplateImg}.{$ext}";
						$this->logger->log("Source: {$source}");
						$this->logger->log("Destiny: {$destiny}");
						if (!copy($source, $destiny)) {
							throw new Exception("Error while copying image file with name {$idTemplateImg}.{$ext}");
						}
					}
				}
			}
		}
	}
	
	private function convertToHtml($editorContent)
	{
		$editorObj = new HtmlObj;
		$editorObj->assignContent(json_decode($editorContent));
		$content = $editorObj->render();
		
		return $content;
	}
	
	
	private function validateImageSrc($src)
	{
		if (!preg_match('/asset\/show/', $src)) {
			$this->logger->log("Es false");
			return false;	
		}
		$this->logger->log("Es true");
		return true;
		
	}
	
	private function validateImageTemplateSrc($src) 
	{
		if (!preg_match('/template\/image/', $src)) {
			$this->logger->log("Es false T");
			return false;	
		}
		$this->logger->log("Es true T");
		return true;
	}
	
	private function saveTemplateImage(Asset $asset)
	{
		$this->logger->log('Guardando imagen de plantilla');
		$templateImage = new Templateimage();
		
		$templateImage->idTemplate = $this->template->idTemplate;
		$templateImage->name = $asset->fileName;
		
		if (!$templateImage->save()) {
			$this->rollbackTransaction();
			throw new Exception('Error while saving templateimage');
		}
		return $templateImage;
	}
	
	private function updateContentHtml($content)
	{
		$this->template->content = $content->content;
		$this->template->contentHtml = htmlspecialchars($content->contentHtml, ENT_QUOTES);
		
		$preview = $this->cache->get('preview-img64-cache-' . $this->user->idUser);
		if (!$preview) {
			$preview = null;
		}
		$this->cache->delete('preview-img64-cache-' . $this->user->idUser);
		
		$this->template->previewData = $preview;
		
		if (!$this->template->save()) {
			$this->rollbackTransaction();
			throw new Exception('Error while updating template');
		}
		$this->commitTransaction();
	}
	
	private function updateContentHtmlFromMail($content)
	{
		$this->template->content = $content->content;
		$this->template->contentHtml = htmlspecialchars($content->contentHtml, ENT_QUOTES);
		
		if ($this->mail == null) {
			$preview = null;
		}
		else {
			$preview = $this->mail->previewData;
		}
		
		$this->template->previewData = $preview;
		
		if (!$this->template->save()) {
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
