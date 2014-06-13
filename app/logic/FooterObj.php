<?php
//Phalcon\DI::getDefault()->get('logger')
class FooterObj {

	public function createFooter($content, $name)
	{
		$objeditor = json_decode($content);
		$editor = new HtmlRow();
		foreach ($objeditor as $row) {
			$editor->assignContent($row);
		}
		$html = '<table><tr>' . $editor->render() . '</tr></table>';
		
		$textobj = new PlainText();
		$plainText = $textobj->getPlainText($html);
		
		$footer = new Footer();
		$footer->name = $name;
		$footer->editor = $content;
		$footer->html = $html;
		$footer->plainText = $plainText;
		
		if (!$footer->save()) {
			foreach ($footer->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log($msg);
			}
			throw new Exception("we have a error while saving new footer...");
		}
	}
}