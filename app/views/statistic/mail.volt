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
			<div class="news span6">
				<div class="titleMail">
					<h2>{{statisticsData.mailName}}</h2>
				</div>
				<div class="dataMailContacts">
					<div class="infoStat">
						<table class="table-condensed">
							<tr>
								<td>
									<table class="table-condensed">
										<tr>
											<td class="border-radious-blue-left">
												<i class="icon-envelope" style="font-size: 20px;"></i>
											</td>
											<td class="border-radious-blue-center" colspan="2">

											</td>
											<td class="border-radious-blue-right">
												<b>{{statisticsData.total}}</b>
											</td>
											<td>
												<h4 class="totalColor">Totales</h4>
											</td>
										</tr>
										<tr><td colspan="5"><td></tr>
										<tr>
											<td class="border-radious-green-left">
												<i class="icon-search"></i>
											</td>
											<td class="border-radious-green-center">
												{{statisticsData.opens}}
											</td>
											<td class="border-radious-green-center">
												|
											</td>
											<td class="border-radious-green-right">
												<b>{{statisticsData.statopens}}%</b>
											</td>
											<td><h4 class="openColor subtitleColor">
												{{'{{#if App.mailSelected}}'}}
													{{'{{#linkTo "compare.opens" App.mailSelected tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Aperturas</a>{{/linkTo}}'}}
												{{'{{else}}'}}
													{{'{{#linkTo "drilldown.opens" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Aperturas</a>{{/linkTo}}'}}
												{{ '{{/if}}' }}
											</h4></td>
										</tr>
										<tr><td colspan="5"><td></tr>
										<tr>
											<td class="border-radious-cyan-left">
												<i class="icon-hand-up"></i>
											</td>
											<td class="border-radious-cyan-center ">
												{{statisticsData.clicks}}
											</td>
											<td class="border-radious-cyan-center">
												|
											</td>
											<td class="border-radious-cyan-right">
												<b>{{statisticsData.statclicks}}%</b>
											</td>
											<td><h4 class="clicksColor subtitleColor">
												{{'{{#if App.mailSelected}}'}}
													{{'{{#linkTo "compare.clicks" App.mailSelected tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Clics</a>{{/linkTo}}'}}
												{{'{{else}}'}}
													{{'{{#linkTo "drilldown.clicks" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Clics</a>{{/linkTo}}'}}
												{{ '{{/if}}' }}	
											</h4></td>
										</tr>
									</table>
								</td>
								<td>
									<table class="table-condensed">
										<tr>
											<td class="border-radious-gray-left">
												<i class="icon-minus-sign"></i>
											</td>
											<td class="border-radious-gray-center ">
												{{statisticsData.unsubscribed}}
											</td>
											<td class="border-radious-gray-center">
												|
											</td>
											<td class="border-radious-gray-right">
												<b>{{statisticsData.statunsubscribed}}%</b>
											</td>
											<td><h4 class="unsubscribedColor subtitleColor">
												{{'{{#if App.mailSelected}}'}}
													{{'{{#linkTo "compare.unsubscribed" App.mailSelected tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Des-suscritos</a>{{/linkTo}}'}}
												{{'{{else}}'}}
													{{'{{#linkTo "drilldown.unsubscribed" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Des-suscritos</a>{{/linkTo}}'}}
												{{ '{{/if}}' }}	
											</h4></td>
										</tr>
										<tr><td colspan="5"><td></tr>
										<tr>
											<td class="border-radious-scarlet-left">
												<i class="icon-warning-sign"></i>
											</td>
											<td class="border-radious-scarlet-center ">
												{{statisticsData.bounced}}
											</td>
											<td class="border-radious-scarlet-center">
												|
											</td>
											<td class="border-radious-scarlet-right">
												<b>{{statisticsData.statbounced}}%</b>
											</td>
											<td><h4 class="bouncedColor subtitleColor">
												{{'{{#if App.mailSelected}}'}}
													{{'{{#linkTo "compare.bounced" App.mailSelected tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Rebotes</a>{{/linkTo}}'}}
												{{'{{else}}'}}
													{{'{{#linkTo "drilldown.bounced" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Rebotes</a>{{/linkTo}}'}}
												{{ '{{/if}}' }}
											</h4></td>
										</tr>
										<tr><td colspan="5"><td></tr>
										<tr>
											<td class="border-radious-red-left">
												<i class="icon-remove"></i>
											</td>
											<td class="border-radious-red-center ">
												{{statisticsData.spam}}
											</td>
											<td class="border-radious-red-center">
												|
											</td>
											<td class="border-radious-red-right">
												<b>{{statisticsData.statspam}}%</b>
											</td>
											<td><h4 class="spamColor subtitleColor">
												{{'{{#if App.mailSelected}}'}}
													{{'{{#linkTo "compare.spam" App.mailSelected tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Spam</a>{{/linkTo}}'}}
												{{'{{else}}'}}
													{{'{{#linkTo "drilldown.spam" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Spam</a>{{/linkTo}}'}}
												{{ '{{/if}}' }}
											</h4></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center;">
									<h3>Comparar</h3>
									<div class="span6">
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
										<div class="span3">
										<button class="btn btn-black" onclick="stopCompare()">No Comparar</button>
										</div>
									{{ '{{/if}}' }}
									</label>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
	
			<div class="span5">
				<div id="summaryChart" style="width: 640px; height: 400px;">
				</div>
			</div>
			<div class="span12">
				{{ "{{outlet}}" }}
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
