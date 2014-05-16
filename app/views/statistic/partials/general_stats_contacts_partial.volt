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
		<div class="unsubs">
			<div class="sm-icons-stats-unsubs center-block"></div>
			<span class="number">{{statisticsData.unsubscribed|numberf}}</span><br>
			<span class="percent">{{statisticsData.percentageUnsubscribed}}%</span>
			<p>Desuscritos</p>
		</div>
	</div>
	<div class="wrap-other-stats">
		<div class="spam">
			<div class="sm-icons-stats-spam center-block"></div>
			<span class="number">{{statisticsData.spam|numberf}}</span><br>
			<span class="percent">{{statisticsData.percentageSpam}}%</span>
			<p>Spam</p>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
</div>