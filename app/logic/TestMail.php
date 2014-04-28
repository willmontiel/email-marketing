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
	
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function setMailContent(Mailcontent $mailContent)
	{
		$this->mailContent = $mailContent;
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
		$this->replaceUrlImages();
	}
	
	protected function createBody()
	{
		if ($this->mail->type == 'Editor') {
			$editorObj = new HtmlObj();
			$editorObj->assignContent(json_decode($this->mailContent->content));
			$content = $editorObj->render();
		}
		else {
			$content = $this->mailContent->content;
		}
		
		if ($this->message != null || !empty($this->message)) {
			$replace = '<body>
							<center>
								<table border="0" cellpadding="0" cellspacing="0" width="600px" style="border-collapse:collapse;background-color:#444444;border-top:0;border-bottom:0">
									<tbody>
										<tr>
											<td align="center" valign="top" style="border-collapse:collapse">
												<span style="padding-bottom:9px;color:#eeeeee;font-family:Helvetica;font-size:12px;line-height:150%">"' . $this->message . '" — ' . $this->mail->fromName . '</span>
											</td>
										</tr>
									</tbody>
								</table>
							</center>';
		
			$this->body = str_replace('<body>', $replace, $content);
		}
		else {
			$this->body = $content;
		}
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
	
	protected function replaceUrlImages()
	{
		$imageService = new ImageService($this->account, $this->domain, $this->urlManager);
		$linkService = new LinkService($this->account, $this->mail);
		$prepareMail = new PrepareMailContent($linkService, $imageService, false);
		list($this->body, $links) = $prepareMail->processContent($this->body);
		
		$htmlObj = new HtmlObj();
		$this->body = utf8_decode($htmlObj->replacespecialchars($this->body));
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
