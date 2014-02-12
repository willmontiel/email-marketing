<?php
class PrepareContentMail
{
	public $account;
	public $domain;
	public $urlManager;
	protected $mail;


	public function __construct(Account $account) 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		
		$this->log = $di['logger'];
		$this->urlManager = $di['urlManager'];
		$this->account = $account;
		$this->domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
	}
	
	public function getContentMail(Mail $mail)
	{
		$this->mail = $mail;
		$mailContent = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $mail->idMail)
		));
		
		if ($mailContent) {
			if (trim($mailContent->content) === '') {
				throw new \InvalidArgumentException("Error mail's content is empty");
			}
			else if ($mail->type == 'Editor') {
				$this->log->log("Hay editor");
				$htmlObj = new HtmlObj();
//				$this->log->log("Content editor: " . print_r(json_decode($mailContent->content), true));
				$htmlObj->assignContent(json_decode($mailContent->content));
				$content = $htmlObj->render();
//				$this->log->log('Json: ' . $content);
			}
			else {
//				$this->log->log("No Hay editor");
				$content =  html_entity_decode($mailContent->content);
			}
//			$this->log->log("Content: " . $content);
			$convertedSrc = $this->convertImageSrc($content);
			$newContent = $this->saveLinksForTracking($convertedSrc, json_decode($mailContent->googleAnalytics));
//			$newContent = $this->searchGoogleAnalyticsUrl($convertedHref);
//			$this->log->log("Content: " . $convertedSrc);
			$mailContentProcessed = new stdClass();
			$mailContentProcessed->html = $newContent;
			$mailContentProcessed->text = $mailContent->plainText;
			
			return $mailContentProcessed;
		}
		else {
			throw new InvalidArgumentException('Error while consulting mailContent');
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
				
//				$srcParts = explode("/", $srcImg);
//
//				$this->log->log("Partes : " . print_r($srcParts, true));

//				$srcPre = $srcParts[1];
				
				if (preg_match('/asset/i', $srcImg)) {
					$find[] = $srcImg;
					$idAsset = filter_var($srcImg, FILTER_SANITIZE_NUMBER_INT);
//					$this->log->log("idAsset : " . $idAsset);
					
					$srcConverted = $this->getCompletePrivateImageSrc($idAsset);
					
					$replace[] = $srcConverted;
//					$this->log->log("srcConverted : " . $srcConverted);
				}
				else if (preg_match('/template/i', $srcImg)) {
					$find[] = $srcImg;
					$idTemplateImage = filter_var($srcImg, FILTER_SANITIZE_NUMBER_INT);
					$ids = explode("/", $srcImg);
//					$this->log->log("idAsset : " . print_r($ids, true));
					
					$srcConverted = $this->getCompletePublicImageSrc($ids[3], $ids[4]);
					
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
		
		$img = $this->domain->imageUrl . '/' . $this->urlManager->getUrlAsset() . "/" . $this->account->idAccount . "/images/" . $asset->idAsset . "." .$ext;
		
		return $img;
	}
	
	protected function getCompletePublicImageSrc($idTemplate, $idTemplateImage)
	{
		$tpImg = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImage)
		));
		
		$ext = pathinfo( $tpImg->name, PATHINFO_EXTENSION);
		$img = $this->domain->imageUrl . '/' . $this->urlManager->getUrlTemplate() . "/" . $idTemplate. "/images/" . $idTemplateImage . "." . $ext;
	
		return $img;
	}
	
	protected function saveLinksForTracking($content, $analytics)
	{
		$this->log->log('Buscando enlaces...: ');
		$imgTag = new DOMDocument();
		@$imgTag->loadHTML($content);

		$hrefs = $imgTag->getElementsByTagName('a');
		$search = array();
		$replace = array();
		
		if ($hrefs->length !== 0) {
			foreach ($hrefs as $href) {
				$l = $href->getAttribute('href');
				
				$parts = parse_url($l);
//				Phalcon\DI::getDefault()->get('logger')->log('Links: ' . $parts['host']);
				
				if (!preg_match('/[^\/]*\.*facebook.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*twitter.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*linkedin.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*plus.google.com.*$/', $parts['host']) && $parts['host'] !== null) {
					if (count($search) == 0) {
						$search[] = $l;
					}
					else {
						if (!in_array($l, $search)) {
							$search[] = $l;
						}
					}
				}
	
				$maillink = Maillink::findFirst(array(
					'conditions' => 'idAccount = ?1 AND link = ?2',
					'bind' => array(1 => $this->account->idAccount, 
									2 => $l)
				));
				
				if (!$maillink) {
					$link = new Maillink();
					
					$link->idAccount = $this->account->idAccount;
					$link->link = $l;
					$link->createdon = time();
					
					if (!$link->save()) {
						foreach ($link->getMessages() as $msg) {
							$this->log->log('Error saving link: ' . $msg);
						}
						throw new InvalidArgumentException('Error while saving Maillink');
					}
					else {
						$mxl = new Mxl();
				
						$mxl->idMail = $this->mail->idMail;
						$mxl->idMailLink = $link->idMailLink;

						if (!$mxl->save()) {
							foreach ($mxl->getMessages() as $msg) {
								$this->log->log('Error saving Mxl: ' . $msg);
							}
							throw new InvalidArgumentException('Error while saving Mxl');
						}
					}
				}
				else {
					$mxl = Mxl::findFirst(array(
						'conditions' => 'idMail = ?1 AND idMailLink = ?2',
						'bind' => array(1 => $this->mail->idMail,
										2 => $maillink->idMailLink)
					));
					
					if (!$mxl) {
						$mxl = new Mxl();
				
						$mxl->idMail = $this->mail->idMail;
						$mxl->idMailLink = $maillink->idMailLink;

						if (!$mxl->save()) {
							foreach ($mxl->getMessages() as $msg) {
								$this->log->log('Error saving Mxl: ' . $msg);
							}
							throw new InvalidArgumentException('Error while saving Mxl');
						}
					}
				}
				$replace[] = $mxl->idMailLink . '_sigma_url_$$$';
			}
			$newContent = str_replace($search, $replace, $content);
			
			return $newContent;
		}
	}
	
	private function searchGoogleAnalyticsUrl($content)
	{
		$ex = 'http://www.sigmamovil.com/servicios/comunicacion/sms-masivo.html?utm_source=SigmaEmail&utm_medium=Email&utm_campaign=micampaign';
		$this->log->log('Buscando google analytics...: ');
		$imgTag = new DOMDocument();
		@$imgTag->loadHTML($content);

		$hrefs = $imgTag->getElementsByTagName('a');
		$search = array();
		$replace = array();
		
		if ($hrefs->length !== 0) {
			foreach ($hrefs as $href) {
				$l = $href->getAttribute('href');
			}
		}
	}
}