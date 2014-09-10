<?php
class WebVersionObj extends BaseWrapper
{
	private $contact = array();
	const IMG_SN_WIDTH = 450;
	const IMG_SN_HEIGHT = 340;
	const IMG_TYPE_DEFAULT = 'default';
	
	function __construct() {
		$this->urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setDbase(Dbase $dbase) {
		$this->dbase = $dbase;
	}
	
	public function setUrlDomain(Urldomain $domain) {
		$this->domain = $domain;
	}

	public function createWebVersion(Mail $mail, Mailcontent $mailContent, Contact $contact, $social = false)
	{		
		$this->contact[0]['contact'] = get_object_vars($contact);
		
		if (trim($mailContent->content) === '') {
			throw new \InvalidArgumentException("Error mail's content is empty");
		}
		else if ($mail->type == 'Editor') {
			$htmlObj = new HtmlObj();
			$htmlObj->setAccount($this->account);
			$htmlObj->assignContent(json_decode($mailContent->content));
			$html = $htmlObj->replacespecialchars($htmlObj->render());
		}
		else {
			$footerObj = new FooterObj();
			$footerObj->setAccount($this->account);
			$html =  $footerObj->addFooterInHtml(html_entity_decode($mailContent->content));
		}
		$imageService = new ImageService($this->account, $this->domain, $this->urlManager);
		$linkService = new LinkService($this->account, $mail, $this->urlManager);
		$prepareMail = new PrepareMailContent($linkService, $imageService);
		
		list($content, $links) = $prepareMail->processContent($html);
		
		if($this->contact[0]['contact']['idContact'] === 0) {
			$fields = 'No Fields';
		}
		else {
			$mailField = new MailField($content, $mailContent->plainText, $mail->subject, $this->dbase->idDbase);
			$fields = $mailField->searchCustomFields();
		}
		
		$customFields = false;
		switch ($fields) {
			case 'No Fields':
				$fields = false;
				break;

			case 'No Custom':
				$fields = true;
				break;

			case 'Fields':
				$fields = true;
				$customFields = $mailField->getCustomFields();
				break;
		}
		
		$this->searchCustomfields($customFields);
		
		if ($fields) {
			$c = $mailField->processCustomFields($this->contact[0]);
			$html = $c['html'];
		}
		else {
			$html = $content;
		}
		
		$trackingObj = new TrackingUrlObject();
		if($social) {
			$htmlWithTracking = $trackingObj->getSocialTrackingUrl($html, $mail->idMail, $this->contact[0]['contact']['idContact'], $links, $social);
		}
		else {
			$htmlWithTracking = $trackingObj->getTrackingUrl($html, $mail->idMail, $this->contact[0]['contact']['idContact'], $links);
		}
		
		$htmlFinal = $this->insertSocialMediaMetadata($mail, $htmlWithTracking, $this->contact[0]['contact']['idContact'], $social);
		
		$htmlFinal = str_replace('<head>', '<head><meta charset="utf-8">', $htmlFinal);
		
		return utf8_decode($htmlFinal);
//		return $htmlFinal;
	}
	
	public function insertSocialMediaMetadata(Mail $mail, $html, $idContact, $social = FALSE)
	{
		$socialmail = Socialmail::findFirstByIdMail($mail->idMail);
		if($social) {
			$og_url = $this->urlManager->getBaseUri(true) . 'webversion/share/1-' . $mail->idMail . '-' . $idContact;
			$src['facebook'] = ($social == 'linkedin') ? $og_url . '-linkedin' : $og_url . '-facebook';
			$src['twitter'] = $this->urlManager->getBaseUri(true) . 'webversion/share/1-' . $mail->idMail . '-' . $idContact . '-twitter';
		}
		else {
			$src['facebook'] = $src['twitter'] = $this->urlManager->getBaseUri(true) . 'webversion/show/1-' . $mail->idMail . '-' . $idContact;
		}
		foreach ($src as $key => $value) {
			$md5 = md5($value . '-Sigmamovil_Rules');
			$url[$key] = $value . '-' . $md5;
		}
		
		$fbsocialdesc = json_decode($socialmail->fbdescription);
		
		// Ajustar Tamaño de Imagen para Compartir
		$imagefb = $this->getImageForShare($fbsocialdesc, 'fb');
		
		//----Facebook MetaData----//
		
		$fbtitle = (isset($fbsocialdesc->title)) ? $fbsocialdesc->title : $mail->subject;
		$fbdescription = (isset($fbsocialdesc->description)) ? $fbsocialdesc->description : 'Mira mi correo';
		$fbmetaname = '<meta property="og:site_name" content="Sigma Movil" />';
		$fbmetaurl = '<meta property="og:url" content="' . $url['facebook'] . '" />';
		$fbmetatitle = '<meta property="og:title" content="' . $fbtitle . '" />';
		$fbmetaimage = '<meta property="og:image" content="' . $imagefb . '" />';
		$fbmetadescritpion = '<meta property="og:description" content="' . $fbdescription . '" />';
		$fbmetatype = '<meta property="og:type" content="website" />';
		$fbmetaapp = '<meta property="fb:app_id" content="' . Phalcon\DI::getDefault()->get('fbapp')->iduser . '" />';
		
		$fbMeta = $fbmetaname . $fbmetaurl . $fbmetatitle . $fbmetaimage . $fbmetadescritpion . $fbmetatype . $fbmetaapp;
		
		//----Twitter MetaData----//
		
		$imagetw = $this->getImageForShare($fbsocialdesc, 'tw');
		
		$twsocialdesc = json_decode($socialmail->twdescription);
		$twtitle = $mail->subject;
		$twdescription = (isset($twsocialdesc->message)) ? $twsocialdesc->message : 'Mira mi correo';
		$twmetacard = '<meta name="twitter:card" content="summary">';
		$twmetaurl = '<meta name="twitter:url" content="' . $url['twitter'] . '">';
		$twmetasite = '<meta name="twitter:site" content="@SigmaMovil1">';
		$twmetacreator = '<meta name="twitter:creator" content="@SigmaMovil1">';
		$twmetatitle = '<meta name="twitter:title" content="' . $twtitle . '">';
		$twmetadescription = '<meta name="twitter:description" content="' . $twdescription . '">';
		$twmetaimage = '<meta name="twitter:image:src" content="' . $imagetw . '">';
		
		$twMeta = $twmetacard . $twmetasite . $twmetaurl . $twmetatitle . $twmetadescription . $twmetacreator . $twmetaimage;
		
		//----Add MetaData----//
		$search = array('</head>');
		$replace = array($fbMeta . $twMeta . '</head>');
		
		return str_ireplace($search, $replace, $html);
	}
	
	public function getImageForShare($fbcontent, $header)
	{
		$img = (isset($fbcontent)) ? $fbcontent->image : self::IMG_TYPE_DEFAULT;
		
		$socialImg = new SocialImageCreator();
		$socialImg->setAccount($this->account);
		$image = $socialImg->createImageToIdealSize($img, self::IMG_SN_WIDTH, self::IMG_SN_HEIGHT, $header);
		
		return $image;
	}
	
	private function searchCustomfields($fields) 
	{
		if ($fields) {
			$sql = "SELECT e.idEmail, e.email, f.idCustomField, f.name AS field, f.textValue, f.numberValue 
					FROM contact AS c 
						JOIN email AS e ON(c.idEmail = e.idEmail)
						LEFT JOIN (SELECT cf.idCustomField, cf.name, fi.idContact, fi.textValue, fi.numberValue 
								   FROM customfield AS cf 
									   JOIN fieldinstance AS fi ON (cf.idCustomField = fi.idCustomField) 
								   WHERE cf.idCustomField IN ({$fields})) AS f ON(c.idContact = f.idContact)
					WHERE c.idContact = {$this->contact[0]['contact']['idContact']}";
			
			$db = Phalcon\DI::getDefault()->get('db');
			$result = $db->query($sql);
			$contact = $result->fetchAll();

			if (count($contact) > 0) {
				$k = 0;

				foreach ($contact as $m) {
					if ($k == 0) {
						$e = array(
							'email' => $m['email'],
							'idEmail' => $m['idEmail']
						);

						$f = array();
						if ($m['idCustomField'] !== null) {
							if ($m['textValue'] !== null) {
								$f[$m['idCustomField']] = $m['textValue'];
							}
							else if ($m['numberValue'] !== null) {
								$f[$m['idCustomField']] = $m['numberValue'];
							}
						}
						
						$this->contact[0]['email'] = $e;
						$this->contact[0]['fields'] = $f;
					}
					else if ($this->contact[0]['email']['idEmail'] == $m['idEmail']) {
						if ($m['textValue'] !== null) {
							$this->contact[0]['fields'][$m['idCustomField']] = $m['textValue'];
						}
						else if ($m['numberValue'] !== null) {
							$this->contact[0]['fields'][$m['idCustomField']] = $m['numberValue'];
						}
					}
					$k++;
				}
			}
		}

	}
}
