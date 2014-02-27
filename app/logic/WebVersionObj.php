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

	public function createWebVersion(Mail $mail, Mailcontent $mailContent, Contact $contact)
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
		$htmlWithTracking = $trackingObj->getTrackingUrl($html, $mail->idMail, $contact['idContact'], $links);
		$htmlFinal = $this->insertSocialMediaMetadata($mail, $htmlWithTracking, $contact['idContact']);
		return $htmlFinal;
	}
	
	public function insertSocialMediaMetadata(Mail $mail, $html, $idContact)
	{
		$socialmail = Socialmail::findFirstByIdMail($mail->idMail);
		$socialdesc = json_decode($socialmail->fbdescription);
		$title = (isset($socialdesc->title)) ? $socialdesc->title : $mail->subject;
		$description = (isset($socialdesc->descritpion)) ? $socialdesc->descritpion : 'Mira mi correo';
		$src = $this->urlManager->getBaseUri(true) . 'webversion/show/1-' . $mail->idMail . '-' . $idContact;
		$md5 = md5($src . '-Sigmamovil_Rules');
		$url = $src . '-' . $md5;
//		$metaname = '<meta property="og:site_name" content="Sigma Movil" />';
//		$metaurl = '<meta property="og:url" content="' . $url . '" />';
//		$metatitle = '<meta property="og:title" content="' . $title . '" />';
//		$metaimage = '<meta property="og:image" content="' . $this->urlManager->getBaseUri(TRUE) . 'images/sigma_envelope.png" />';
//		$metadescritpion = '<meta property="og:description" content="' . $description . '" />';
//		$metatype = '<meta property="og:type" content="website" />';
//		$metaapp = '<meta property="fb:app_id" content="' . Phalcon\DI::getDefault()->get('fbapp')->iduser . '" />';
		
		$metaname =         '<meta property="og:site_name" content="YouTube">';
		$metaurl = '    <meta property="og:url" content="http://www.youtube.com/watch?v=9Idt_YiY7kM">';
		$metatitle =    '<meta property="og:title" content="Back to the Future Screen Test, Part 1 - Saturday Night Live">';
		$metaimage =    '<meta property="og:type" content="video">';
		$metadescritpion =     '<meta property="og:image" content="https://i1.ytimg.com/vi/9Idt_YiY7kM/hqdefault.jpg">';
		$metatype =      '<meta property="og:description" content="Subscribe to SaturdayNightLive: http://j.mp/1bjU39d Celebrity Impressions: http://j.mp/1bEY4ok Movie Parodies: http://j.mp/14Mjfxh SEASON 36: http://j.mp/16I...">';
		$metaapp =         '<meta property="og:video" content="http://www.youtube.com/v/9Idt_YiY7kM?version=3&amp;autohide=1">';
		
		$search = array('<html>', '</head>');
		$replace = array('<html lang="es" data-cast-api-enabled="true">', $metaname . $metaurl . $metatitle . $metaimage . $metadescritpion . $metatype . $metaapp . '</head>');
		
		return str_ireplace($search, $replace, $html);
	}
}
