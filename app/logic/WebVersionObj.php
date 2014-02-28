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
		$d = '  <meta name="description" content="Subscribe to SaturdayNightLive: http://j.mp/1bjU39d Celebrity Impressions: http://j.mp/1bEY4ok Movie Parodies: http://j.mp/14Mjfxh SEASON 36: http://j.mp/16I..."><meta name="keywords" content="jiggawatts, impressions, Jon Hamm, Doc Brown, Michael J. Fox, gigawatts, SNL, Saturday Night Live, 10s, 2010s, 2010, Season 36, Eddie Murphy, Al Pacino, Jay ...">';
		$metaname =         '<meta property="og:site_name" content="YouTube">';
		$metaurl = '    <meta property="og:url" content="http://www.youtube.com/watch?v=9Idt_YiY7kM">';
		$metatitle =    '<meta property="og:title" content="Back to the Future Screen Test, Part 1 - Saturday Night Live">';
		$metaimage =    '<meta property="og:type" content="video">';
		$metadescritpion =     '<meta property="og:image" content="https://i1.ytimg.com/vi/9Idt_YiY7kM/hqdefault.jpg">';
		$metatype =      '<meta property="og:description" content="Subscribe to SaturdayNightLive: http://j.mp/1bjU39d Celebrity Impressions: http://j.mp/1bEY4ok Movie Parodies: http://j.mp/14Mjfxh SEASON 36: http://j.mp/16I...">';
		$metaapp =         '<meta property="og:video" content="http://www.youtube.com/v/9Idt_YiY7kM?version=3&amp;autohide=1">';
		$otherMeta = '<meta name="twitter:card" content="player"><meta name="twitter:site" content="@youtube"><meta name="twitter:url" content="http://www.youtube.com/watch?v=9Idt_YiY7kM"><meta name="twitter:title" content="Back to the Future Screen Test, Part 1 - Saturday Night Live"><meta name="twitter:description" content="Subscribe to SaturdayNightLive: http://j.mp/1bjU39d Celebrity Impressions: http://j.mp/1bEY4ok Movie Parodies: http://j.mp/14Mjfxh SEASON 36: http://j.mp/16I..."><meta name="twitter:image" content="https://i1.ytimg.com/vi/9Idt_YiY7kM/hqdefault.jpg"><meta name="twitter:app:name:iphone" content="YouTube"><meta name="twitter:app:id:iphone" content="544007664"><meta name="twitter:app:name:ipad" content="YouTube"><meta name="twitter:app:id:ipad" content="544007664"><meta name="twitter:app:url:iphone" content="vnd.youtube://watch/9Idt_YiY7kM"><meta name="twitter:app:url:ipad" content="vnd.youtube://watch/9Idt_YiY7kM"><meta name="twitter:app:name:googleplay" content="YouTube"><meta name="twitter:app:id:googleplay" content="com.google.android.youtube"><meta name="twitter:app:url:googleplay" content="http://www.youtube.com/watch?v=9Idt_YiY7kM"><meta name="twitter:player" content="https://www.youtube.com/embed/9Idt_YiY7kM"><meta name="twitter:player:width" content="1920"><meta name="twitter:player:height" content="1080"><meta name=attribution content=SNL/>';
		$search = array('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">','<html>', '</head>');
		$replace = array('<!DOCTYPE html>','<html lang="es" data-cast-api-enabled="true">', $d . $metaname . $metaurl . $metatitle . $metaimage . $metadescritpion . $metatype . $metaapp . $otherMeta . '</head>');
		
		return str_ireplace($search, $replace, $html);
	}
}
