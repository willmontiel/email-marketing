<?php
class WebVersionObj extends BaseWrapper
{
	function __construct() {
		$this->urlManager = Phalcon\DI::getDefault()->get('urlManager');
	}
	
	public function setDbase(Dbase $dbase) {
		$this->dbase = $dbase;
	}
	
	public function setUrlDomain(Urldomain $domain) {
		$this->domain = $domain;
	}

	public function createWebVersion(Mail $mail, Mailcontent $mailContent, Contact $contact, $social = false)
	{
		if (trim($mailContent->content) === '') {
			throw new \InvalidArgumentException("Error mail's content is empty");
		}
		else if ($mail->type == 'Editor') {
			$htmlObj = new HtmlObj();
			$htmlObj->assignContent(json_decode($mailContent->content));
			$html = $htmlObj->render();
		}
		else {
			$html =  html_entity_decode($mailContent->content);
		}
		$imageService = new ImageService($this->account, $this->domain, $this->urlManager);
		$linkService = new LinkService($this->account, $mail, $this->urlManager);
		$prepareMail = new PrepareMailContent($linkService, $imageService);
		list($content, $links) = $prepareMail->processContent($html);
		$mailField = new MailField($content, $mailContent->plainText, $mail->subject, $this->dbase->idDbase);
		$cf = $mailField->getCustomFields();

		switch ($cf) {
			case 'No Fields':
				$customFields = false;
				$fields = false;
				break;
			case 'No Custom':
				$fields = true;
				$customFields = false;
				break;
			default:
				$fields = true;
				$customFields = $cf;
				break;
		}
		$contact = get_object_vars($contact);
		if ($fields) {
			$c = $mailField->processCustomFields($contact);
			$html = $c['html'];
		}
		else {
			$html = $content->html;
		}
		$trackingObj = new TrackingUrlObject();
		if($social) {
			$htmlWithTracking = $trackingObj->getSocialTrackingUrl($html, $mail->idMail, $contact['idContact'], $links, $social);
		}
		else {
			$htmlWithTracking = $trackingObj->getTrackingUrl($html, $mail->idMail, $contact['idContact'], $links);
		}
		$htmlFinal = $this->insertSocialMediaMetadata($mail, $htmlWithTracking, $contact['idContact'], $social);
		return $htmlFinal;
	}
	
	public function insertSocialMediaMetadata(Mail $mail, $html, $idContact, $social = FALSE)
	{
		$socialmail = Socialmail::findFirstByIdMail($mail->idMail);
		if($social) {
			Phalcon\DI::getDefault()->get('logger')->log('La red social es: ' . $social);
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
		
		//----Facebook MetaData----//
		
		$fbsocialdesc = json_decode($socialmail->fbdescription);
		$fbtitle = (isset($fbsocialdesc->title)) ? $fbsocialdesc->title : $mail->subject;
		$fbdescription = (isset($fbsocialdesc->description)) ? $fbsocialdesc->description : 'Mira mi correo';
		$fbmetaname = '<meta property="og:site_name" content="Sigma Movil" />';
		$fbmetaurl = '<meta property="og:url" content="' . $url['facebook'] . '" />';
		$fbmetatitle = '<meta property="og:title" content="' . $fbtitle . '" />';
		$fbmetaimage = '<meta property="og:image" content="' . $this->urlManager->getBaseUri(TRUE) . 'images/260.png" />';
		$fbmetadescritpion = '<meta property="og:description" content="' . $fbdescription . '" />';
		$fbmetatype = '<meta property="og:type" content="website" />';
		$fbmetaapp = '<meta property="fb:app_id" content="' . Phalcon\DI::getDefault()->get('fbapp')->iduser . '" />';
		
		$fbMeta = $fbmetaname . $fbmetaurl . $fbmetatitle . $fbmetaimage . $fbmetadescritpion . $fbmetatype . $fbmetaapp;
		
		//----Twitter MetaData----//
		
		$twsocialdesc = json_decode($socialmail->twdescription);
		$twtitle = $mail->subject;
		$twdescription = (isset($twsocialdesc->message)) ? $twsocialdesc->message : 'Mira mi correo';
		$twmetacard = '<meta name="twitter:card" content="summary">';
		$twmetaurl = '<meta name="twitter:url" content="' . $url['twitter'] . '">';
//		$twmetadomain = '<meta name="twitter:domain" content="' . $this->urlManager->getBaseUri(TRUE) . '">';
		$twmetasite = '<meta name="twitter:site" content="@dariosigma">';
		$twmetacreator = '<meta name="twitter:creator" content="@dariosigma">';
		$twmetatitle = '<meta name="twitter:title" content="' . $twtitle . '">';
		$twmetadescription = '<meta name="twitter:description" content="' . $twdescription . '">';
		$twmetaimage = '<meta name="twitter:image:src" content="' . $this->urlManager->getBaseUri(TRUE) . 'images/260.png">';
		
		$twMeta = $twmetacard . $twmetasite . $twmetaurl . $twmetatitle . $twmetadescription . $twmetacreator . $twmetaimage;
		
		//----Add MetaData----//
		$search = array('</head>');
		$replace = array($fbMeta . $twMeta . '</head>');
		
		return str_ireplace($search, $replace, $html);
	}
}
