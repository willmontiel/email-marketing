{#   Estadisticas generales de los envios   #}
<div class="row">
	<div class="totalstats">
		<div class="sends">
			<div class="sm-icons-stats-sends center-block"></div>
			<span class="number-send">{{statisticsData.total|numberf}}</span><br>
			<p>Envíos</p>
		</div>
	</div>

	<div class="totalstats">
		{#
		{{'{{#link-to "drilldown.opens" class="anchor" href=false}}' }}
		#}
			<div class="opens">
				<div class="sm-icons-stats-opens center-block"></div>
				<span class="number">{{statisticsData.opens|numberf}}</span><br>
				<span class="percent">{{statisticsData.statopens}}%</span>
				<p>Aperturas</p>
			</div>
		{#
		{{ '{{/link-to}}'}}
		#}
	</div>

	<div class="totalstats">
		{#
		{{'{{#link-to "drilldown.clicks" class="anchor" href=false}}' }}
		#}
			<div class="clics">
				<div class="sm-icons-stats-clics center-block"></div>
				<span class="number">{{statisticsData.clicks_CTR}} persona de {{statisticsData.total - statisticsData.bounced}}</span><br>
				<span class="percent">{{statisticsData.percent_clicks_CTR}}%</span>
				<p>Clics</p>
			</div>
		{#
		{{'{{/link-to}}'}}
		#}
	</div>

	<div class="totalstats">
		{#
		{{'{{#link-to "drilldown.bounced" class="anchor" href=false}}' }}
		#}
			<div class="bounced">
				<div class="sm-icons-stats-bounced center-block"></div>
				<span class="number">{{statisticsData.bounced|numberf}}</span><br>
				<span class="percent">{{statisticsData.statbounced}}%</span>
				<p>Rebotes</p>
			</div>
		{#
		{{ '{{/link-to}}'}}
		#}
	</div>
		{#
		{{'{{#link-to "drilldown.unsubscribed" class="anchor" href=false}}' }}
		#}
		<div class="wrap-other-stats">
			<div class="unsubs">
				<div class="sm-icons-stats-unsubs center-block"></div>
				<span class="number">{{statisticsData.unsubscribed|numberf}}</span><br>
				<span class="percent">{{statisticsData.statunsubscribed}}%</span>
				<p>Desuscritos</p>
			</div>
		</div>
		{#
		{{ '{{/link-to}}'}}
		#}
		{#
		{{'{{#link-to "drilldown.spam" class="anchor" href=false}} '}}
		#}
		<div class="wrap-other-stats">
			<div class="spam">
				<div class="sm-icons-stats-spam center-block"></div>
				<span class="number">{{statisticsData.spam|numberf}}</span><br>
				<span class="percent">{{statisticsData.statspam}}%</span>
				<p>Spam</p>
			</div>
		</div>
		{#
		{{' {{/link-to}}'}}
		#}
	<div class="clearfix"></div>
	<div class="space"></div>
</div>