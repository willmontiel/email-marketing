<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<h4 class="sectiontitle">Aperturas</h4>
	<div class="wrapper">	
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="bar-drilldown"}}'}}
	</div>
	<hr>
	<div class="stats">
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.opens|numberf}}</span><br>
			<span class="number">{{statisticsData.statopens}}%</span>
		</div>
	</div>
	<div class="clearfix"></div>
	
	{% if type is defined %}
	{% else %}
		{{ partial("statistic/partials/opens_table_stats_partial") }}
	{% endif %}
	

</script>

<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	<h4 class="sectiontitle">Clics</h4>
	<div class="wrapper">
		{{'{{view App.TimeGraphView idChart="clickBarChartContainer" typeChart="bar-drilldown"}}'}}
	</div>
	<hr>
	<div class="stats">	
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.totalclicks}}</span><br>
			Total de clics únicos
		</div>
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.clicks_CTR}} de {{statisticsData.total - statisticsData.bounced}}</span><br>
			<span class="number">({{statisticsData.percent_clicks_CTR}}%)</span>
			Tasa de clics
		</div>
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.clicks_CTR}} de {{statisticsData.opens}}</span><br>
			<span class="number">({{statisticsData.percent_clicks_CTO}}%)</span>
			Click To Open Rate
		</div>
	</div>
	<div class="clearfix"></div>
	
	{% if type is defined %}
	{% else %}
		{{ partial("statistic/partials/clics_table_stats_partial") }}
	{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	<h4 class="sectiontitle">Desuscritos</h4>
	<div class="wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer"}}'}}
	</div>
	<hr>
	
	<div class="stats">
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.unsubscribed|numberf}}</span><br>
			<span class="number">{{statisticsData.statunsubscribed}}%</span>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	<div class="text-right">
		<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
	</div>
	<div class="clearfix"></div>
	
	{% if type is defined %}
	{% else %}
		{{ partial("statistic/partials/unsubscribed_table_stats_partial") }}
	{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/spam">
	<h4 class="sectiontitle">Spam</h4>
	<div class="wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer"}}'}}
	</div>
	<hr>
	
	<div class="stats">
		<div class="col-md-4 wrapper">
			<span class="number">{{statisticsData.spam|numberf}}</span><br>
			<span class="number">{{statisticsData.statspam}}%</span>
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="space"></div>
	<div class="text-right">
		<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
	</div>
	<div class="clearfix"></div>
	
	{% if type is defined %}
	{% else %}
		{{ partial("statistic/partials/spam_table_stats_partial") }}
	{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/bounced">
	<h4 class="sectiontitle">Rebotes</h4>
	<div class="col wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="pie-basic"}}'}}
	</div>
	<hr>	

	<div class="stats">
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.softbounced|numberf}}</span><br>
			<span class="number">{{statisticsData.statsoftbounced}}%</span>
			Suaves
		</div>
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.hardbounced|numberf}}</span><br>
			<span class="number">{{statisticsData.stathardbounced}}%</span>
			Duros
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	<div class="text-right">
		<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
	</div>
	<div class="clearfix"></div>
	
	{% if type is defined %}
	{% else %}
		{{ partial("statistic/partials/bounced_table_stats_partial") }}
	{% endif %}
</script>

