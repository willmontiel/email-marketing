<?php
class BlockSocialFollow extends HtmlAbstract
{
	public function assignContent($data)
	{
		foreach ($data->contentData as $content) {
			if($content->selected) {
				$domain = Urldomain::findFirstByIdUrlDomain(1);
				$this->children[] = '<a href=" '. $content->url .'"><img src="' . $domain->imageUrl . '/images/' . $content->imageName .'_follow.png"><br><label>' . $content->linktext . '</label></a>';
			}
		}
	}
	public function renderObjPrefix()
	{
		return '<td><table><tbody><tr>';
	}
	public function renderChildPrefix($i)
	{
		return '<td style="text-align: center;">';
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