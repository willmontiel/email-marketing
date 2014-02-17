<?php
class ImageService
{
	private $account;
	private $domain;
	private $urlManager;


	public function __construct(Account $account, Urldomain $domain, UrlManagerObject $urlManager) 
	{
		$this->account = $account;
		$this->domain = $domain;
		$this->urlManager = $urlManager;
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
		if (preg_match('/asset/i', $imageSrc)) {
			$idAsset = filter_var($imageSrc, FILTER_SANITIZE_NUMBER_INT);
			return $this->getCompletePrivateImageSrc($idAsset);
		}
		else if (preg_match('/template/i', $imageSrc)) {
//			$idTemplateImage = filter_var($srcImg, FILTER_SANITIZE_NUMBER_INT);
			$ids = explode("/", $imageSrc);
			return $this->getCompletePublicImageSrc($ids[3], $ids[4]);
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
			return $img;
		}
	}
	
	protected function getCompletePublicImageSrc($idTemplate, $idTemplateImage)
	{
		$tpImg = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImage)
		));
		
		if ($tpImg) {
			$ext = pathinfo( $tpImg->name, PATHINFO_EXTENSION);
			$img = $this->domain->imageUrl . '/' . $this->urlManager->getUrlTemplate() . "/" . $idTemplate. "/images/" . $idTemplateImage . "." . $ext;
			return $img;
		}
	}
}