<div class="clearfix"></div>
<div class="space"></div>
<div class="row">
	<h4 class="sectiontitle">Detalles de estadisticas</h4>
	<ul class="nav nav-tabs nav-tabs-left bs-ember-href">
		{{'{{#link-to "drilldown.opens" tagName="li" href=false}}<a {{bind-attr href="view.href"}} onClick="autoScroll();">Aperturas</a>{{/link-to}}'}}
		{{'{{#link-to "drilldown.clicks" tagName="li" href=false}}<a {{bind-attr href="view.href"}} onClick="autoScroll();">Clics</a>{{/link-to}}'}}
		{{'{{#link-to "drilldown.unsubscribed" tagName="li" href=false }}<a {{bind-attr href="view.href"}} onClick="autoScroll();">Des-suscritos</a>{{/link-to}}'}}
		{{'{{#link-to "drilldown.bounced" tagName="li" href=false}}<a {{bind-attr href="view.href"}} onClick="autoScroll();">rebotados</a>{{/link-to}}'}}
		{{'{{#link-to "drilldown.spam" tagName="li" href=false}}<a {{bind-attr href="view.href"}} onClick="autoScroll();">spam</a>{{/link-to}}'}}
	</ul>
</div>
<div class="space"></div>