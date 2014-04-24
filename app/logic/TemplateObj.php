<?php
class TemplateObj
{
	private $account;
	private $mail;
	private $user;
	private $templatesfolder;
	private $asset;
	private $url;
	private $cache;
	private $logger;
	private $finalAccount;
	
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
	
	public function setFinalAccount(Account $account)
	{
		$this->finalAccount = $account;
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
		
		$template = $this->saveTemplateInDb($name, $category);
		$content = $this->saveTemplateInFolder($template->idTemplate, $mailContent->content);
		$this->updateContentHtmlFromMail($template, $content->content, $content->contentHtml);
	}
	
	public function createTemplate($name, $category, $editorContent)
	{
		$this->logger->log('Empezando proceso de creacion de plantillas');
		
		$template = $this->saveTemplateInDb($name, $category);
		$content = $this->saveTemplateInFolder($template->idTemplate, $editorContent);
		$this->updateContentHtml($template, $content->content, $content->contentHtml);
		
		return $template->idTemplate;
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
		
		if($this->account->idAccount != null) {
			$dir = $this->asset->dir . $this->account->idAccount . '/templates/' . $idTemplate . '/images/';
			$type = 'private';
		}
		else {
			$dir = $this->templatesfolder->dir . $idTemplate . '/images/';
			$type = 'public';
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
					$templateImage = $this->transportImageToPrivateTemplate($src, $idTemplate, $dir);
					$find[] = $src;
					$replace[] = $this->url->get('template/image') . '/' . $idTemplate . '/' .$templateImage->idTemplateImage;
				}
				else if ($this->validateImageTemplateSrc($src) && $type == 'public') {
					$this->transportImageToPublicTemplate($src, $idTemplate, $dir);
				}
				$find[] = $src;
				$replace[] = $this->url->get('template/image') . '/' . $idTemplate . '/' .$templateImage->idTemplateImage;
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
	
	private function transportImageToPublicTemplate($src, $idTemplate)
	{
		$ids = explode('/', $src);
		$id = count($ids) - 1;
		$idTemplateImg = $ids[$id];

		$this->logger->log("idT: {$idTemplateImg}");

		$templateImg = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImg)
		));

		if (!$templateImg) {	
			throw new Exception('Error, template image not found!');
		}

		$ext = pathinfo($templateImg->name, PATHINFO_EXTENSION);
		$this->logger->log("Account : {$this->finalAccount->idAccount}");
		$source = "{$this->asset->dir}{$this->finalAccount->idAccount}/templates/{$idTemplate}/images/{$idTemplateImg}.{$ext}";
		$destiny = "{$this->templatesfolder->dir}{$idTemplate}/images/{$idTemplateImg}.{$ext}";
		
		$this->logger->log("Source: {$source}");
		$this->logger->log("Destiny: {$destiny}");
		
		if (!copy($source, $destiny)) {
			throw new Exception('Error while copying image files');
		}
	}
	
	private function transportImageToPrivateTemplate($src, $idTemplate, $dir)
	{
		$idAsset = filter_var($src, FILTER_SANITIZE_NUMBER_INT);
		
		$asset = Asset::findFirst(array(
			"conditions" => "idAsset = ?1",
			"bind" => array(1 => $idAsset)
		));

		if (!$asset) {
			throw new Exception('Error, asset not found!');
		}

		$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
		$templateImage = $this->saveTemplateImage($idTemplate, $asset->fileName);
		$img = $this->asset->dir . $asset->idAccount . "/images/" . $asset->idAsset . "." .$ext;

		$this->logger->log("Img: {$img}");
		$this->logger->log('Dir: ' . $dir . $templateImage->idTemplateImage . '.' .$ext);
		if (!copy($img, $dir . $templateImage->idTemplateImage . '.' .$ext)) {
			throw new Exception('Error while copying image files');
		}
		return $templateImage;
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
	
	private function updateContentHtmlFromMail($template, $newContent, $newContentHtml)
	{
		$template->content = $newContent;
		$template->contentHtml = htmlspecialchars($newContentHtml, ENT_QUOTES);
		
		if ($this->mail == null) {
			$preview = null;
		}
		else {
			$preview = $this->mail->previewData;
		}
		
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
