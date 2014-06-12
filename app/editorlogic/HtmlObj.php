<?php
class HtmlObj extends HtmlAbstract
{	
	protected $preview;
	protected $url;
	protected $idMail;

	public function __construct($preview = false, $url = null, $idMail = null) 
	{
		$this->preview = $preview;
		$this->url = $url;
		$this->idMail = $idMail;
		$this->log = Phalcon\DI::getDefault()->get('logger');
		$this->path = Phalcon\DI::getDefault()->get('url');
	}
	
	public function assignContent($content)
	{		
		$this->layout = $content->layout;
		$this->backgroundColor = isset($content->editorColor) ? $content->editorColor : '#ffffff';
		
		foreach ($content->dz as $key => $values) {
			$HtmlZone = new HtmlZone();
			$HtmlZone->setAccount($this->account);
			$HtmlZone->assignContent($content->dz->$key);
			$this->children[] = $HtmlZone->render();
		}
	}
	
	public function renderObjPrefix()
	{
		$pr = '<html><head>';
		if ($this->preview) {
			$pr .= '<title>Preview</title>
						<script type="text/javascript" src="' . $this->path->get('js/html2canvas.js'). '"></script>
						<script type="text/javascript" src="' . $this->path->get('js/jquery-1.8.3.min.js') .'"></script>';
			$pr .= '<script>function createPreviewImage(img) {
						$.ajax({
							url: "' . $this->url . '/' . $this->idMail .'",
							type: "POST",			
							data: { img: img},
							success: function(){}
						});
					}</script>';
		}
		$pr .= '</head><body>';
		
		return $pr . '<table style="background-color: '. $this->backgroundColor . '; width: 100%;"><tr><td style="padding: 20px;"><center><table style="width: 600px;" width="600px" cellspacing="0" cellpadding="0"><tbody>';
	}
	public function renderChildPrefix($i)
	{
		if(strpos($this->layout->name, 'sidebar')) {
			if($i == 4) {
				return '';
			}
			else if($i == 3) {
				return '<tr><td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tbody><tr>';
			}
		}
		elseif (strpos($this->layout->name, 'columns')) {
			if($i == 4) {
				return '<tr><td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tbody><tr>';
			}
			if($i == 5) {
				return '';
			}
			else if(strpos($this->layout->name, 'three') && $i == 6) {
				return '';
			}
		}
		return '<tr>';
	}
	public function renderChildPostfix($i)
	{
		if(strpos($this->layout->name, 'sidebar')) {
			if($i == 3) {
				return '';
			}
			else if($i == 4) {
				return '</tr></tbody></table></td></tr>';
			}
		}
		elseif (strpos($this->layout->name, 'columns')) {
			if($i == 4) {
				return '';
			}
			else if(strpos($this->layout->name, 'three') && $i == 5) {
				return '';
			}
			else if(strpos($this->layout->name, 'three') && $i == 6) {
				return '</tr></tbody></table></td></tr>';
			}
			else if(strpos($this->layout->name, 'two') && $i == 5) {
				return '</tr></tbody></table></td></tr>';
			}
		}
		return '</tr>';
	}
	public function renderObjPostfix()
	{
		if ($this->preview) {
			$pr = '<script> 
					html2canvas(document.body, { 
						onrendered: function (c) { 
							c.getContext("2d");	
							createPreviewImage(c.toDataURL("image/png"));
						},
						height: 700
					});
				   </script>';
		}
		else {
			$pr = '';
		}
		$pr .= '</body></html>';
		return '</tbody></table></center></td></tr></table>' . $pr;
	}
	
	public function replacespecialchars($html)
	{
		$search = array("\xe2\x80\x8b", "\xe2\x80\x9c", "\xe2\x80\x9d");
		$replace = array('', '"', '"');
		$response= str_replace($search, $replace, $html);
		return $response;
	}
}
