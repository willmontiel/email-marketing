<?php
class TestMail
{
	public $mail;
	public $mailContent;
	public $body;
	public $plainText;
	public $message;
	
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
	
	public function load()
	{
		$this->createBody();
		$this->createPlaintext();
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
	
//	protected function createFeedBack()
//	{
//		
//	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function getPlainText()
	{
		return $this->plainText;
	}
}
