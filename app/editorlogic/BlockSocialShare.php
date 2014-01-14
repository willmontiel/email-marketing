<?php
class BlockSocialShare extends HtmlAbstract
{
	public function assignContent($data)
	{
		foreach ($data->contentData as $content) {
			if($content->selected) {
//				Phalcon\DI::getDefault()->get('logger')->log(print_r($content, true));
				$domain = Urldomain::findFirstByIdUrlDomain(1);
				switch ($content->socialName) {
					case 'Facebook':
						$this->children[] = '<a href=" https://facebook.com/sharer/sharer.php?u=http%3A%2F%2Fgoogle.com%2F&display=popup"><img src="' . $domain->imageUrl . '/images/' . $content->imageName .'_share.png"><br></a>';
						break;
					case 'Twitter':
						$this->children[] = '<a href="https://twitter.com/intent/tweet?text=AQUI VA EL TEXTO%3A%20http%3A%2F%2Furl.com%2Frestourl&source=webclient"><img src="' . $domain->imageUrl . '/images/' . $content->imageName .'_share.png"></a>';
						break;
					case 'LinkedIn':
						$this->children[] = '<a href="https://linkedin.com/cws/share?url=http%3A%2F%2Fgoogle.com"><img src="' . $domain->imageUrl . '/images/' . $content->imageName .'_share.png"></a>';
						break;
					case 'Google Plus':
						$this->children[] = '<a href="https://plus.google.com/share?url=http%3A%2F%2Fexample.com"><img src="' . $domain->imageUrl . '/images/' . $content->imageName .'_share.png"></a>';
						break;
					default :
						$this->children[] = '<a href="#"><img src=""><label>' . $content->linktext . '</label></a>';
				}				
			}
		}

	}
	public function renderObjPrefix()
	{
		return '<td><table><tbody><tr>';
	}
	public function renderChildPrefix($i)
	{
		return '<td>';
	}
	public function renderChildPostfix($i)
	{
		return '</td>';
	}
	public function renderObjPostfix()
	{
		return '</tr></tbody></table></td>';
	}
}
