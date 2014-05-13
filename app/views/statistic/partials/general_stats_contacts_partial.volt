{#   Estadisticas generales de los envios para contactos y bases de datos   #}

<div class="col-md-2 col-sm-4 col-xs-6 item">
	<div class="sends">
		<div class="sm-icons-stats-sends center-block"></div>
		<span class="number-send">{{statisticsData.sent|numberf}}</span><br>
		<p>Envíos</p>
	</div>
</div>
<div class="col-md-2 col-sm-4 col-xs-6 item">
		<div class="opens anchor">
			<div class="sm-icons-stats-opens center-block"></div>
			<span class="number">{{statisticsData.uniqueOpens|numberf}}</span><br>
			<span class="percent">{{statisticsData.percentageUniqueOpens}}%</span>
			<p>Aperturas</p>
		</div>
</div>
<div class="col-md-2 col-sm-4 col-xs-6 item">
		<div class="clics anchor">
			<div class="sm-icons-stats-clics center-block"></div>
			<span class="number-send">{{statisticsData.clicks|numberf}}</span><br>
			<p>Clics</p>
		</div>
	</div>
</div>
<div class="col-md-2 col-sm-4 col-xs-6 bounced anchor item">
	<div class="bounced anchor">
		<div class="sm-icons-stats-bounced center-block"></div>
		<span class="number">{{statisticsData.bounced|numberf}}</span><br>
		<span class="percent">{{statisticsData.percentageBounced}}%</span>
		<p>Rebotes</p>
	</div>
</div>

<div class="clearfix"></div>
<div class="space"></div>
<hr>
<div class="row wrapper">
	<div class="col-xs-6 col-sm-4 col-md-3">
			<div class="sm-icons-stats-unsubs unsubs anchor">
				<div class="pleft-60">
					<span class="little-number">{{statisticsData.unsubscribed|numberf}}</span>
					<span class="little-number">{{statisticsData.percentageUnsubscribed}}%</span>
					<p class="mbottom-0">Desuscritos</p>
				</div>
			</div>
	</div>
	<div class="col-xs-6 col-sm-4 col-md-3">
			<div class="sm-icons-stats-spam spam anchor">
				<div class="pleft-60">
					<span class="little-number">{{statisticsData.spam|numberf}}</span>
					<span class="little-number">{{statisticsData.percentageSpam}}%</span>
					<p class="mbottom-0">Spam</p>
				</div>
			</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
</div>
