<div class="row header-background">
	<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
		<div class="stat-open-indicator">
			<div class="percent-stats">{{statisticsData.percentageUniqueOpens}}%</div>
		</div>
		<div class="medium-title">{{statisticsData.uniqueOpens|numberf}} Aperturas</div>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div id="domain-opening" class="small-pie-chart"></div>
		<div class="medium-title">Aperturas agrupadas por dominio</div>
	</div>
</div>

<hr> 

<div class="row header-background">
	<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
		<div class="stat-bounced-indicator">
			<div class="percent-stats">{{statisticsData.percentageBounced}}%</div>
		</div>
		<div class="medium-title">{{statisticsData.bounced|numberf}} rebotes</div>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div id="domain-bounced" class="small-pie-chart"></div>
		<div class="medium-title">Rebotes agrupados por dominio</div>
	</div>
</div>

<hr> 

<div class="row header-background">
	<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
		<div class="stat-unsubscribed-indicator">
			<div class="percent-stats">{{statisticsData.percentageUnsubscribed}}%</div>
		</div>
		<div class="medium-title">{{statisticsData.unsubscribed|numberf}} Contactos des-suscritos</div>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div id="domain-dunsubscribed" class="small-pie-chart"></div>
		<div class="medium-title">Contactos des-suscritos agrupados por dominio</div>
	</div>
</div>

<hr> 

<div class="row header-background">
	<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
		<div class="stat-spam-indicator">
			<div class="percent-stats">{{statisticsData.percentageSpam}}%</div>
		</div>
		<div class="medium-title">{{statisticsData.spam|numberf}} reportes de Spam</div>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div id="domain-spam" class="small-pie-chart"></div>
		<div class="medium-title">Reportes de Spam agrupados por dominio</div>
	</div>
</div>