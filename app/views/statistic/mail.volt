{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlstatistic.url ~ '/mail/' ~ idMail }}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		function autoScroll() {
			$('html, body').animate({scrollTop: '615px'}, 'slow');
		}
	</script>
	<script>
		var chartData = [];
		App.mails = [""];
		
		{%for cmail in compareMail %}
			var cmail = new Object();
			cmail.id = {{ cmail.id }};
			cmail.name = '{{ cmail.name }}';
			App.mails.push(cmail);
		{%endfor%}
			
		{%for data in summaryChartData %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			data.url = '{{ data['url'] }}';
			chartData.push(data);
		{%endfor%}
		
		AmCharts.ready(function () {
			chart = createPieChart(chartData);	
			chart.write('summaryChart');
			$("select").select2({
				placeholder: "Seleccione Un Correo"
			});
		});
		
		function compareMails() {
			if(App.mailCompare != undefined && App.mailCompare != null) {
				window.location = "#/compare/opens/" + App.mailCompare;
				App.set('mailSelected', App.mailCompare);
			}
		};
		
		function stopCompare() {
			window.location = "#/drilldown/opens";
			App.set('mailSelected', null);
		};
		
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">
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
											  <span>{{statisticsData.statopens}}%</span>
											</div>
											<div class="news-content">
												<label class="label-openings">
													 {{statisticsData.opens|numberf}}
												</label>
												<div class="news-text">
													{{'{{#if App.mailSelected}}'}}
														{{'{{#linkTo "compare.opens" App.mailSelected href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Aperturas</span>{{/linkTo}}'}}
													{{'{{else}}'}}
														{{'{{#linkTo "drilldown.opens" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Aperturas</span>{{/linkTo}}'}}
													{{ '{{/if}}' }}
												</div>
											</div>
										</div>	
									</div>
								</td>
								<td>
									<div class="box">
										<div class="box-section news with-icons">
											<label class="avatar-clicks"><i class="icon-hand-up icon-3x"></i></label>
											<div class="news-time">
											  <span>{{statisticsData.statclicks}}%</span>
											</div>
											<div class="news-content">
												<label class="label-clicks">{{statisticsData.clicks|numberf}}</label>
												<div class="news-text">
													{{'{{#if App.mailSelected}}'}}
														{{'{{#linkTo "compare.clicks" App.mailSelected href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Clics</span>{{/linkTo}}'}}
													{{'{{else}}'}}
														{{'{{#linkTo "drilldown.clicks" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Clics</span>{{/linkTo}}'}}
													{{ '{{/if}}' }}	
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
											  <span>{{statisticsData.statunsubscribed}}%</span>
											</div>
											<div class="news-content">
												<label class="label-unsubscribed">{{statisticsData.unsubscribed|numberf}}</label>
												<div class="news-text">
													{{'{{#if App.mailSelected}}'}}
														{{'{{#linkTo "compare.unsubscribed" App.mailSelected href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Des-suscritos</span>{{/linkTo}}'}}
													{{'{{else}}'}}
														{{'{{#linkTo "drilldown.unsubscribed" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Des-suscritos</span>{{/linkTo}}'}}
													{{ '{{/if}}' }}	
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
											  <span>{{statisticsData.statbounced}}%</span>
											</div>
											<div class="news-content">
												<label class="label-bounced">{{statisticsData.bounced|numberf}}</label>
												<div class="news-text">
													{{'{{#if App.mailSelected}}'}}
														{{'{{#linkTo "compare.bounced" App.mailSelected href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Rebotes</span>{{/linkTo}}'}}
													{{'{{else}}'}}
														{{'{{#linkTo "drilldown.bounced" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Rebotes</span>{{/linkTo}}'}}
													{{ '{{/if}}' }}
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
					<h3>{{statisticsData.mailName}} <small>{{statisticsData.total|numberf}} correos enviados</small></h3>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
				</div>
				<div class="span6">
					<div id="summaryChart" style="width: 640px; height: 400px;"></div>
				</div>
			</div>
			</div>
			<div class="row-fluid">
				<div class="span2">
					<h4>Comparar con: </h4>
				</div>
				<div class="span3">
					{{ '{{view Ember.Select
						class="select"
						contentBinding="App.mails"
						optionValuePath="content.id"
						optionLabelPath="content.name"
						valueBinding="App.mailCompare"}}'
					}}
				</div>
				<div class="span2">
					<button class="btn btn-blue" onclick="compareMails()">Comparar</button>
				</div>
			{{'{{#if App.mailSelected}}'}}
				<div class="span2">
					<button class="btn btn-black" onclick="stopCompare()">No Comparar</button>
				</div>
			{{ '{{/if}}' }}
			</div>
			<div class="row-fluid">
				<div class="span12">
					{{ "{{outlet}}" }}
				</div>
			</div>
		</script>
		{{ partial("statistic/mailpartial") }}
		{{ partial("statistic/comparemailpartial") }}
		<script type="text/x-handlebars" data-template-name="timeGraph">
		<div class="pull-right scaleChart">
			<div class="pull-left">
				Agrupar por: &nbsp;
			</div>
			<div class="pull-right">
				<label for="scaleHour">
					{{'{{view Ember.RadioButton id="scaleHour" name="scale" selectionBinding="App.scaleSelected" value="hh"}}'}}
					Hora &nbsp;
				</label>
			</div>
			<div class="pull-right">
				<label for="scaleDay">
					{{'{{view Ember.RadioButton id="scaleDay" name="scale" selectionBinding="App.scaleSelected" value="DD"}}'}}
					Dia &nbsp;
				</label>
			</div>
			<div class="pull-right">
				<label for="scaleMonth">
					{{'{{view Ember.RadioButton id="scaleMonth" name="scale" selectionBinding="App.scaleSelected" value="MM" checked="checked"}}'}}
					Mes &nbsp;
				</label>
			</div>
		</div>
		<div id="ChartContainer"></div>
		</script>
	</div>
{% endblock %}
