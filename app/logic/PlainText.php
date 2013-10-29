<?php
class PlainText
{
	public function getPlainText($mailContent)
	{
		$contentHtml = html_entity_decode($mailContent); 
		
		$buscar = array(
			"</p>" ,
			"</h1>",
			"</h2>",
			"</h3>", 
			"</h4>", 
			"</h5>", 
			"</h6>", 
			"</tr>",
			"<strong>",
			"<b>",
			"</strong>",
			"</b>",
			'<a href="#'
		);
		
		$reemplazar = array(
			"<br/><br/></p>", 
			"</h1><br/>============================================================<br/>",
			"</h2><br/>____________________________________________________________<br/>", 
			"</h3><br/>------------------------------------------------------------<br/>", 
			"</h4><br/>",
			"</h5><br/>",
			"</h6><br/>",
			"</tr><br/>",
			"*",
			"*",
			"*",
			"*",
			'(#) <a href="#'
		);
		
		$content = str_replace($buscar, $reemplazar, $contentHtml);
		
		$some = new simple_html_dom();
		
		$html = str_get_html($content);
		return trim($html->plaintext);
	}
}