<?php
class PrepareMailObj
{
	public function __construct(Account $account) 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		
		$this->url = $di['url'];
		$this->account = $account;
	}
	
	public function beginPreparation(Mail $mail)
	{
		$mailContent = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $mail->idMail)
		));
		
		if ($mailContent) {
			if ($mail->type == 'Editor') {
//				$this->log->log("Hay editor");
				$htmlObj = new HtmlObj();
//				$this->log->log("Content editor: " . print_r(json_decode($mailContent->content), true));
				$htmlObj->assignContent(json_decode($mailContent->content));
				$content = $htmlObj->render();
			}
			else {
//				$this->log->log("No Hay editor");
				$content =  html_entity_decode($mailContent->content);
			}
			
//			$this->log->log("Content: " . $content);
			$convertedSrc = $this->convertImageSrc($content);
			
			return $convertedSrc;
		}
	}
	
	protected function convertImageSrc($content)
	{
//		$this->log->log("srcPre : " . $content);
		$imgTag = new DOMDocument();
		@$imgTag->loadHTML($content);

		$srcs = $imgTag->getElementsByTagName('img');
		
		if ($srcs->length !== 0) {
			$find = array();
			$replace = array();
			
			foreach ($srcs as $src) {
				$srcImg = $src->getAttribute('src');
//				$this->log->log("SrcImg: " . $srcImg);
				
				$srcParts = explode("/", $srcImg);
				$srcPrivate = $this->url->get('asset');
				$srcPublic = $this->url->get('template');
				
//				$this->log->log("Partes : " . print_r($srcParts, true));
				
				$srcPre = '/' . $srcParts[1] . '/' . $srcParts[2];
//				$this->log->log("srcPrefi : " . $srcPre);
//				$this->log->log("srcPrivate : " . $srcPrivate);
//				$this->log->log("srcPublic : " . $srcPublic);
				if ($srcPre == $srcPrivate) {
					$find[] = $srcImg;
					$idAsset = filter_var($srcImg, FILTER_SANITIZE_NUMBER_INT);
//					$this->log->log("idAsset : " . $idAsset);
					
					$srcConverted = $this->getCompletePrivateImageSrc($idAsset);
					
					$replace[] = $srcConverted;
//					$this->log->log("srcConverted : " . $srcConverted);
				}
				else if ($srcPre == $srcPublic) {
					$find[] = $srcImg;
//					$idTemplateImage = filter_var($srcImg, FILTER_SANITIZE_NUMBER_INT);
					$ids = explode("/", $srcImg);
//					$this->log->log("idAsset : " . print_r($ids, true));
					
					$srcConverted = $this->getCompletePublicImageSrc($ids[4], $ids[5]);
					
					$replace[] = $srcConverted;
//					$this->log->log("srcConverted : " . $srcConverted);
				}
			}
			
			$newContent = str_replace($find, $replace, $content);
//			$this->log->log("New content : " . $newContent);
			
			return $newContent;
		}
	}
	
	protected function getCompletePrivateImageSrc($idAsset)
	{
		$asset = Asset::findFirst(array(
			"conditions" => "idAsset = ?1",
			"bind" => array(1 => $idAsset)
		));
		
		$ext = pathinfo($asset->fileName, PATHINFO_EXTENSION);
		
		$img = "http://localhost" . $this->url->get('assets') . "/" . $this->account->idAccount . "/images/" . $asset->idAsset . "." .$ext;
	
		return $img;
	}
	
	protected function getCompletePublicImageSrc($idTemplate, $idTemplateImage)
	{
		$tpImg = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImage)
		));
		
		$ext = pathinfo( $tpImg->name, PATHINFO_EXTENSION);
		$img = "http://localhost" . $this->url->get('templates') . "/" . $idTemplate. "/images/" . $idTemplateImage . "." . $ext;
	
		return $img;
	}
}