{#   Estadisticas generales de los envios   #}
<div class="col-md-2 col-sm-4 col-xs-6 item">
	<div class="sends">
		<div class="sm-icons-stats-sends center-block"></div>
		<span class="number-send">{{statisticsData.total|numberf}}</span><br>
		<p>Envíos</p>
	</div>
</div>
<div class="col-md-2 col-sm-4 col-xs-6 item">
	{{'{{#link-to "drilldown.opens" class="anchor" href=false}}' }}
		<div class="opens anchor">
			<div class="sm-icons-stats-opens center-block"></div>
			<span class="number">{{statisticsData.opens|numberf}}</span><br>
			<span class="percent">{{statisticsData.statopens}}%</span>
			<p>Aperturas</p>
		</div>
	{{ '{{/link-to}}'}}
</div>
<div class="col-md-2 col-sm-4 col-xs-6 item">
	{{'{{#link-to "drilldown.clicks" class="anchor" href=false}}' }}
		<div class="clics anchor">
			<div class="sm-icons-stats-clics center-block"></div>
			<span class="number">{{statisticsData.totalclicks|numberf}}</span><br>
			<span class="percent">{{statisticsData.percent_clicks_CTR}}%</span>
			<p>Clics</p>
		</div>
	{{'{{/link-to}}'}}
	</div>
</div>
<div class="col-md-2 col-sm-4 col-xs-6 bounced anchor item">
	{{'{{#link-to "drilldown.bounced" class="anchor" href=false}}' }}
		<div class="bounced anchor">
			<div class="sm-icons-stats-bounced center-block"></div>
			<span class="number">{{statisticsData.hardbounced|numberf}}</span><br>
			<span class="percent">{{statisticsData.stathardbounced}}%</span>
			<p>Rebotes</p>
		</div>
	{{ '{{/link-to}}'}}
</div>
<div class="clearfix"></div>
<div class="space"></div>
<hr>
<div class="row wrapper">
	<div class="col-xs-6 col-sm-4 col-md-3">
		{{'{{#link-to "drilldown.unsubscribed" class="anchor" href=false}}' }}
			<div class="sm-icons-stats-unsubs unsubs anchor">
				<div class="pleft-60">
					<span class="little-number">{{statisticsData.unsubscribed|numberf}}</span>
					<span class="little-number">{{statisticsData.statunsubscribed}}%</span>
					<p class="mbottom-0">Desuscritos</p>
				</div>
			</div>
		{{ '{{/link-to}}'}}
	</div>
	<div class="col-xs-6 col-sm-4 col-md-3">
		{{'{{#link-to "drilldown.spam" class="anchor" href=false}} '}}
			<div class="sm-icons-stats-spam spam anchor">
				<div class="pleft-60">
					<span class="little-number">{{statisticsData.spam|numberf}}</span>
					<span class="little-number">{{statisticsData.statspam}}%</span>
					<p class="mbottom-0">Spam</p>
				</div>
			</div>
		{{' {{/link-to}}'}}
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
</div>
