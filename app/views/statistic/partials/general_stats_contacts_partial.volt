{#   Estadisticas generales de los envios para contactos y bases de datos   #}
<div class="container-fluid">
	<div class="totalstats">
		<div class="sends">
			<div class="sm-icons-stats-sends center-block"></div>
			<span class="number-send">{{statisticsData.sent|numberf}}</span><br>
			<p>Envíos</p>
		</div>
	</div>
	<div class="totalstats">
		<div class="opens">
			<div class="sm-icons-stats-opens center-block"></div>
			<span class="number">{{statisticsData.uniqueOpens|numberf}}</span><br>
			<span class="percent">{{statisticsData.percentageUniqueOpens}}%</span>
			<p>Aperturas</p>
		</div>
	</div>
	<div class="totalstats">
		<div class="clics">
			<div class="sm-icons-stats-clics center-block"></div>
			<span class="number-send">{{statisticsData.clicks|numberf}}</span><br>
			<p>Clics</p>
		</div>
	</div>
	<div class="totalstats">
		<div class="bounced">
			<div class="sm-icons-stats-bounced center-block"></div>
			<span class="number">{{statisticsData.bounced|numberf}}</span><br>
			<span class="percent">{{statisticsData.percentageBounced}}%</span>
			<p>Rebotes</p>
		</div>
	</div>
	<div class="wrap-other-stats">
		<div class="sm-icons-stats-unsubs unsubs">
			<span class="little-number">{{statisticsData.unsubscribed|numberf}}</span>
			<span class="little-number">{{statisticsData.percentageUnsubscribed}}%</span>
		</div>
		<p class="text-center color-desusc ptop-40">Desuscritos</p>
	</div>
	<div class="wrap-other-stats">
		<div class="sm-icons-stats-spam spam">
			<span class="little-number">{{statisticsData.spam|numberf}}</span>
			<span class="little-number">{{statisticsData.percentageSpam}}%</span>
			<p>Spam</p>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
</div>