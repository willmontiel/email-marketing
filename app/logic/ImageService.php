<?php
class ImageService
{
	private $account;
	private $domain;
	private $urlManager;
	private $logger;


	public function __construct(Account $account, Urldomain $domain, UrlManagerObject $urlManager) 
	{
		$this->account = $account;
		$this->domain = $domain;
		$this->urlManager = $urlManager;
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	/**
	 * Retorna el nuevo url de la imagen a partir de la URL del HTML
	 * modifica la URL si es necesario
	 * @param string $url
	 * @return array
	 */
	public function transformImageUrl($imageSrc)
	{
		$newImageSrc = $this->validateSrcImg($imageSrc);
		return $newImageSrc;
	}
	
	private function validateSrcImg($imageSrc)
	{
		$this->logger->log("Link: {$imageSrc}");
		if (preg_match('/asset/i', $imageSrc)) {
//			$this->logger->log("Imágenes por asset");
			$idAsset = filter_var($imageSrc, FILTER_SANITIZE_NUMBER_INT);
			return $this->getCompletePrivateImageSrc($idAsset);
		}
		else if (preg_match('/template/i', $imageSrc)) {
//			$this->logger->log("Imágenes por template");
//			$idTemplateImage = filter_var($srcImg, FILTER_SANITIZE_NUMBER_INT);
			$ids = explode("/", $imageSrc);
			return $this->getCompletePublicImageSrc($ids[3], $ids[4]);
		}
		else if(preg_match('/footer/i', $imageSrc)) {
			$ids = explode("/", $imageSrc);
			return $this->getFooterImageSrc($ids[3], $ids[4]);
		}
	}
	
	private function getCompletePrivateImageSrc($idAsset)
	{
		$asset = Asset::findFirst(array(
			"conditions" => "idAsset = ?1",
			"bind" => array(1 => $idAsset)
		));
		
		if ($asset) {
			$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
			$img = $this->domain->imageUrl . '/' . $this->urlManager->getUrlAsset() . "/" . $this->account->idAccount . "/images/" . $asset->idAsset . "." .$ext;
//			$this->logger->log("Link final: {$img}");
			return $img;
		}
	}
	
	protected function getCompletePublicImageSrc($idTemplate, $idTemplateImage)
	{
		$template = Template::findFirst(array(
			'conditions' => 'idTemplate = ?1',
			'bind' => array(1 => $idTemplate)
		));
		
		$tpImg = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImage)
		));
		
		if ($template && $tpImg) {
			$ext = pathinfo( $tpImg->name, PATHINFO_EXTENSION);
			
			if ($template->idAccount == $this->account->idAccount) {
				$img = "{$this->domain->imageUrl}/{$this->urlManager->getUrlAsset()}/{$this->account->idAccount}/templates/{$idTemplate}/images/{$idTemplateImage}.{$ext}";
			}
			else if ($template->idAccount == null) {
				$img = $this->domain->imageUrl . '/' . $this->urlManager->getUrlTemplate() . "/" . $idTemplate. "/images/" . $idTemplateImage . "." . $ext;
			}
//			$this->logger->log("Link final: {$img}");
			return $img;
		}
		
	}
	
	protected function getFooterImageSrc($idFooter, $idFooterImage)
	{
		$footer = Footer::findFirst(array(
			'conditions' => 'idFooter = ?1',
			'bind' => array(1 => $idFooter)
		));
		
		$image = Footerimage::findFirst(array(
			'conditions' => 'idFooterImage = ?1',
			'bind' => array(1 => $idFooterImage)
		));
		
		if ($footer && $image) {
			$ext = pathinfo( $image->name, PATHINFO_EXTENSION);
			
			$img = $this->domain->imageUrl . '/' . $this->urlManager->getUrlFooter() . "/global/" . $image->idFooterImage . "." . $ext;
			
			return $img;
		}
	}
}