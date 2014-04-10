<?php

class SocialImageCreator
{
	const IMG_TYPE_DEFAULT = 'default';
	
	function __construct()
	{
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
		$this->assetsrv = Phalcon\DI::getDefault()->get('asset');
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function createImageToIdealSize($imageid, $width, $height, $header)
	{
		$image = $this->urlObj->getBaseUri(TRUE) . 'images/' . $header . '_' . self::IMG_TYPE_DEFAULT . '.png';
		if($imageid != self::IMG_TYPE_DEFAULT) {
			$asset = Asset::findFirst(array(
				'conditions' => 'idAsset = ?1',
				'bind' => array(1 => $imageid)
			));
			
			if($asset) {
				$imgObj = new ImageObject();
				$imgObj->createImageFromFile($this->assetsrv->dir . $this->account->idAccount . '/images/' . $asset->idAsset . '.' . pathinfo($asset->fileName, PATHINFO_EXTENSION), $asset->fileName);
				$imgObj->resizeImage($width, $height);

				$dir = $this->assetsrv->dir . $this->account->idAccount . '/sn/' ;

				if (!file_exists($dir)) {
					mkdir($dir, 0777, true);
				}

				$imgname = $header . '_' . $imageid . '.jpg';
				$dir .= $imgname;

				$imgObj->saveImage('jpg', $dir);

				$image = $this->urlObj->getAppUrlAsset(TRUE) . '/' . $this->account->idAccount . '/sn/' . $imgname;
			}
		}
		
		return $image;
	}
}

