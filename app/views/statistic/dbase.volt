{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ partial("partials/statistic_charts") }}
{% endblock %}
{% block sectiontitle %}<i class="icon-signal icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
<div class="row-fluid">
		<div class="span12">
			<table class="table" style="border: 0px !important;" >
				<thead></thead>
				<tbody>
					<tr>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-openings"><i class="icon-folder-open icon-3x"></i></label>
									<div class="news-time">
									  <span>{{stat.percentageUniqueOpens}}%</span>
									</div>
									<div class="news-content">
										<label class="label-openings">{{stat.uniqueOpens|numberf}}</label>
										<div class="news-text">
											Aperturas
										</div>
									</div>
								</div>	
							</div>
						</td>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-clicks"><i class="icon-hand-up icon-3x"></i></label>
									<div class="news-content">
										<label class="label-clicks">{{stat.clicks|numberf}}</label>
										<div class="news-text">
											Clicks
										</div>
									</div>
								</div>	
							</div>
						</td>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-unsubscribed"><i class="icon-minus-sign icon-3x"></i></label>
									<div class="news-time">
									  <span>{{stat.percentageUnsubscribed}}%</span>
									</div>
									<div class="news-content">
										<label class="label-unsubscribed">{{stat.unsubscribed|numberf}}</label>
										<div class="news-text">
											Des-suscritos
										</div>
									</div>
								</div>	
							</div>
						</td>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-bounced"><i class="icon-ban-circle icon-3x"></i></label>
									<div class="news-time">
									  <span>{{stat.percentageBounced}}%</span>
									</div>
									<div class="news-content">
										<label class="label-bounced">{{stat.bounced|numberf}}</label>
										<div class="news-text">
											Rebotes
										</div>
									</div>
								</div>	
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<h3>{{dbase.name}} <small>{{stat.sent}} correos enviados</small></h3>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="box">
				<div id="summaryChart1" style="width: 600px; height: 400px;"></div>
			</div>
		</div>
		<div class="span6">
			<div class="box">
				<div id="summaryChart2" style="width: 600px; height: 400px;"></div>
			</div>
		</div>	
	</div>
{% endblock %}
