<?php
//Phalcon\DI::getDefault()->get('logger')
class FooterObj {

	public function createFooter($content, $name)
	{
		$objeditor = json_decode($content);
		$footercontent = $this->getHtmlAndText($objeditor);
		
		$footer = new Footer();
		$footer->name = $name;
		$footer->editor = $content;
		$footer->html = $footercontent->html;
		$footer->plainText = $footercontent->plaintext;
		
		if (!$footer->save()) {
			foreach ($footer->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log($msg);
			}
			throw new Exception("we have a error while saving new footer...");
		}
	}
	
	public function updateFooter(Footer $footer, $content, $name)
	{
		$objeditor = json_decode($content);
		$footercontent = $this->getHtmlAndText($objeditor);
		$footer->name = $name;
		$footer->editor = $content;
		$footer->html = $footercontent->html;
		$footer->plainText = $footercontent->plaintext;
		if (!$footer->save()) {
			foreach ($footer->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log($msg);
			}
			throw new Exception("we have a error while saving new footer...");
		}
	}
	
	protected function getHtmlAndText($content)
	{
		$htmleditor = '';
		foreach ($content as $row) {
			$editor = new HtmlRow();
			$row->widthval = 600;
			$editor->assignContent($row);
			$htmleditor.= '<tr>' . $editor->render() . '</tr>';
		}
		$html = '<table>' . $htmleditor . '</table>';
		
		$textobj = new PlainText();
		$plainText = $textobj->getPlainText($html);
		
		$obj = new stdClass();
		$obj->html = $html;
		$obj->plaintext = (!empty($plainText)) ? $plainText : '==Plain Text==' ;
		Phalcon\DI::getDefault()->get('logger')->log(print_r($obj, true));
		return $obj;
	}

	public function setFooterEditorObj($footer)
	{
		$editor = new stdClass();
		$editor->layout = new stdClass();
		$editor->layout->id = 6;
		$editor->layout->description = "Layout for Footer";
		$editor->layout->name = "layout-footer";
		
		$editorfooter = new stdClass();
		$editorfooter->name = "footer";
		$editorfooter->width = "full-width";
		$editorfooter->widthval = 600;
		$editor->layout->zones = array($editorfooter);
		
		
		$editor->dz = new stdClass();
		$editor->dz->footer = new stdClass();
		$editor->dz->footer->background_color = "#FFFFFF";
		$editor->dz->footer->border_color = "#FFFFFF";
		$editor->dz->footer->border_style = "none";
		$editor->dz->footer->border_width = 0;
		$editor->dz->footer->content = $footer;
		$editor->dz->footer->corner_bottom_left = 0;
		$editor->dz->footer->corner_bottom_right = 0;
		$editor->dz->footer->corner_top_left = 0;
		$editor->dz->footer->corner_top_right = 0;
		$editor->dz->footer->name = "footer";
		$editor->dz->footer->parent = "#edit-area";
		$editor->dz->footer->width = "full-width";
		$editor->dz->footer->widthval = 600;
		
		return json_encode($editor);
	}
}