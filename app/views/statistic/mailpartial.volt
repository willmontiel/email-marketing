<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<h4 class="sectiontitle">Aperturas</h4>
	<div class="col-md-offset-1 wrapper">	
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="Bar" textChart="Aperturas de Correo"}}'}}
	</div>
	<hr>
	<div class="stats">
		<div class="col-sm-4 wrapper">
			<span class="number">{{statisticsData.opens|numberf}}</span><br>
			<span class="number">{{statisticsData.statopens}}%</span>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	<h4 class="sectiontitle">Detalle de aperturas</h4>
	<div class="pull-right">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/opens" class="btn btn-sm btn-default extra-padding">Descargar reporte</a>
	</div>

	{#   parcial tabla aperturas estadisticas   #}
	{{ partial("statistic/partials/opens_table_stats_partial") }}

</script>

<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	<h4 class="sectiontitle">Clics</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="clickBarChartContainer" typeChart="Bar" textChart="Clics en"}}'}}
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
	<div class="space"></div>
	<h4 class="sectiontitle">Detalle de clics</h4>
	<div class="pull-right">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/clicks" class="btn btn-sm btn-default extra-padding">Descargar reporte</a>
	</div>

	{#   parcial tabla clics estadisticas   #}
	{{ partial("statistic/partials/clics_table_stats_partial") }}

</script>

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	<h4 class="sectiontitle">Desuscritos</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Des-suscritos de Correo"}}'}}
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
	
	<h4 class="sectiontitle">Detalle de desuscritos</h4>
	<div class="pull-right">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/unsubscribed" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
	</div>

	{#   parcial tabla dessuscritos estadisticas   #}
	{{ partial("statistic/partials/unsubscribed_table_stats_partial") }}

</script>

<script type="text/x-handlebars" data-template-name="drilldown/spam">
	<h4 class="sectiontitle">Spam</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Reportes de Spam"}}'}}
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
	
	<h4 class="sectiontitle">Detalle de Spam</h4>
	<div class="pull-right">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/spam" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
	</div>

	{#   parcial tabla dessuscritos estadisticas   #}
	{{ partial("statistic/partials/unsubscribed_table_stats_partial") }}

</script>

<script type="text/x-handlebars" data-template-name="drilldown/bounced">
	<h4 class="sectiontitle">Rebotes</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Rebotes"}}'}}
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
	
	<h4 class="sectiontitle">Detalle de rebotes</h4>
	<div class="pull-right">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/bounced" class="btn btn-sm btn-default btn-sm extra-padding">Descargar reporte</a>
	</div>

	{#   parcial tabla rebotados estadisticas   #}
	{{ partial("statistic/partials/bounced_table_stats_partial") }}

</script>

