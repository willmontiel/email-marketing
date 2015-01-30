<?php
class TestMail
{
	public $mail;
	public $mailContent;
	public $body;
	public $plainText;
	public $message;
	public $account;
	public $domain;
	public $urlManager;
	public $logger;


	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function setMailContent(Mailcontent $mailContent)
	{
		$this->mailContent = $mailContent;
	}
	
	public function setContent($content)
	{
		$this->content = $content;
	}
	
	public function setPersonalMessage($message = null)
	{
		$this->message = $message;
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setDomain(Urldomain $domain)
	{
		$this->domain = $domain;
	}
	
	public function setUrlManager($urlManager)
	{
		$this->urlManager = $urlManager;
	}
	
	public function load()
	{
		$this->createBody();
		$this->createPlaintext();
		$this->replaceCustomFields();
		$this->replaceUrlImages(false);
	}
	
	public function transformContent()
	{
		$editorObj = new HtmlObj();
		$editorObj->setAccount($this->account);
		$editorObj->assignContent(json_decode($this->content));
		$content =  $editorObj->replacespecialchars($editorObj->render());
		$this->body = utf8_decode($content);
		
		$text = new PlainText();
		$this->plainText = $text->getPlainText($this->body);
		
		$imageService = new ImageService($this->account, $this->domain, $this->urlManager);
		$linkService = new LinkService($this->account, null);
		$prepareMail = new PrepareMailContent($linkService, $imageService, false);
		list($this->body, $links) = $prepareMail->processContent($this->body, false);
	}

	protected function createBody()
	{
		if ($this->mail->type == 'Editor') {
			$editorObj = new HtmlObj();
			$editorObj->setAccount($this->account);
			$editorObj->assignContent(json_decode($this->mailContent->content));
			$content =  $editorObj->replacespecialchars($editorObj->render());
		}
		else {
			$footerObj = new FooterObj();
			$footerObj->setAccount($this->account);
			$content = $footerObj->addFooterInHtml(html_entity_decode($this->mailContent->content));
		}
		
		if (!empty($this->message)) {
			$replace = '<body>
							<center>
								<table border="0" cellpadding="0" cellspacing="0" width="600px" style="border-collapse:collapse;background-color:#444444;border-top:0;border-bottom:0">
									<tbody>
										<tr>
											<td align="center" valign="top" style="border-collapse:collapse">
												<span style="padding-bottom:9px;color:#eeeeee;font-family:Helvetica;font-size:12px;line-height:150%">"' . utf8_decode($this->message) . '" - ' . $this->mail->fromName . '</span>
											</td>
										</tr>
									</tbody>
								</table>
							</center>';
		
			$content = str_replace('<body>', $replace, $content);
		}
		
		$this->body = utf8_decode($content);
	}
	
	protected function createPlaintext()
	{	
		$text = new PlainText();
		$this->plainText = $text->getPlainText($this->body);
	}
	
	protected function replaceCustomFields()
	{
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $this->plainText, $textFields);
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $this->body, $htmlFields);

		$result = array_merge($textFields[0], $htmlFields[0]);	

		$search = array_unique($result);
		$replace = array();
		foreach ($search as $s) {
			$replace[] = strtolower(substr($s, 2, -2));
		}
		
		$this->body = str_replace($search, $replace, $this->body);
		$this->plainText = str_replace($search, $replace, $this->plainText);
	}
	
	protected function replaceUrlImages($link_service = true)
	{
		$imageService = new ImageService($this->account, $this->domain, $this->urlManager);
		$linkService = new LinkService($this->account, $this->mail);
		$prepareMail = new PrepareMailContent($linkService, $imageService, false);
		list($this->body, $links) = $prepareMail->processContent($this->body, $link_service);
	}

	public function getBody()
	{
		return $this->body;
	}
	
	public function getPlainText()
	{
		return $this->plainText;
	}
}
