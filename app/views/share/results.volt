{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
{% endblock %}
{% block content %}
	<div class="wrap">
		<div class="col-md-5">
			<h4 class="sectiontitle numbers-contacts">{{mail.name}}</h4>
		</div>
		<div class="col-md-7">
			<div class="col-md-6">
				<p><span class="blue big-number">{{statisticsData.total|numberf}} </span>correos enviados</p>
			</div>
			<div class="col-md-6">
				<br><p class="text-right">Fecha del envío  {{date('Y-m-d', mail.finishedon)}}</p>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="col-md-2 col-sm-4 col-xs-6">
		<div class="box-dashboard-summary summary-opens">
			<div class="title-stats-dashboard-summary">{{statisticsData.opens|numberf}}</div>
			<div class="number-stats-dashboard-summary">{{statisticsData.statopens}}%</div>
			<div class="title-stats-dashboard-summary">Aperturas</div>
		</div>
	</div>

	<div class="col-md-2 col-sm-4 col-xs-6">
		<div class="box-dashboard-summary summary-clicks">
			<div class="title-stats-dashboard-summary">{{statisticsData.clicks|numberf}}</div>
			<div class="number-stats-dashboard-summary">{{statisticsData.percent_clicks_CTR}}%</div>
			<div class="title-stats-dashboard-summary">Clics</div>
		</div>
	</div>
			
	<div class="col-md-2 col-sm-4 col-xs-6">
		<div class="box-dashboard-summary summary-unsubscribed">
			<div class="title-stats-dashboard-summary">{{statisticsData.unsubscribed|numberf}}</div>
			<div class="number-stats-dashboard-summary">{{statisticsData.statunsubscribed}}%</div>
			<div class="title-stats-dashboard-summary">Desuscritos</div>
		</div>
	</div>
			
	<div class="col-md-2 col-sm-4 col-xs-6">
		<div class="box-dashboard-summary summary-bounced">
			<div class="title-stats-dashboard-summary">{{statisticsData.hardbounced|numberf}}</div>
			<div class="number-stats-dashboard-summary">{{statisticsData.stathardbounced}}%</div>
			<div class="title-stats-dashboard-summary">Rebotes</div>
		</div>
	</div>
	
	<div class="col-md-2 col-sm-4 col-xs-6">			
		<div class="box-dashboard-summary summary-spam">
			<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
			<div class="number-stats-dashboard-summary">{{statisticsData.statspam}}%</div>
			<div class="title-stats-dashboard-summary">Spam</div>
		</div>
	</div>
{% endblock %}