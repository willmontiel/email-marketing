{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# HighCharts & HighMaps #}
	{{ javascript_include('vendors/highcharts/highcharts.js')}}
	{{ javascript_include('vendors/highcharts/modules/exporting.js')}}

	<script type="text/javascript">
		$(function () {
			var d = new Date();
			var year = d.getFullYear();
			
			$.getJSON(MyBaseURL + 'account/gethistory',function(data){ 
				setHighchart(data);
			});
			
			function setHighchart(data) {
				$('#container').highcharts({
					title: {
						text: 'Puntos en el año ' + year,
						x: -20 //center
					},
					xAxis: {
						categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
							'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
					},
					yAxis: {
						title: {
							text: 'Puntos'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						valueSuffix: ''
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
					},
					series: [{
						name: 'Puntos',
						data: [data.Jan, data.Feb, data.Mar, data.Apr, data.May, data.Jun, data.Jul, data.Aug, data.Sep, data.Oct, data.Nov, data.Dec]
					}]
				});
			}
		});
	</script>
{% endblock %}	
{% block content %}
	<div class="space"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div class="header">
				<div class="title">{{account.companyName}}</div>
				<div class="title-info">Comportamiento en la plataforma</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
			<div class="contact-indicator">
				<span class="total-contacts">
					{% if score.score is defined%}
						{{score.score}}
					{% else %}
						0
					{% endif %}
				</span><br />
				<span class="text-contacts" style="padding-right: 14px;">Puntos</span>
			</div>
		</div>
	</div>
	<div class="space"></div>
	<div class="clearfix space-small"></div>
			
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="block block-gray">
				<div id="container"></div>
			</div>
		</div>
	</div>	
	
	<div class="clearfix"></div>
	<div class="small-space"></div>

	{% if page.items|length != 0%}
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 <div class="text-center">
					<ul class="pagination">
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}"><i class="glyphicon glyphicon-fast-backward"></i></a>
						</li>
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}?page={{ page.before }}"><i class="glyphicon glyphicon-step-backward"></i></a>
						</li>
						<li>
							<span><b>{{page.total_items}}</b> registros </span><span>Página <b>{{page.current}}</b> de <b>{{page.total_pages}}</b></span>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}?page={{page.next}}"><i class="glyphicon glyphicon-step-forward"></i></a>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}?page={{page.last}}"><i class="glyphicon glyphicon-fast-forward"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Gestión inteligente</th>
							<th>Correo</th>
							<th class="orange-sigma star-shadow">
								<span class="glyphicon glyphicon-star"></span> Puntuación
							</th>
							<th>Acumulado</th>
						</tr>
					</thead>
					<tbody>
						{% for item in page.items %}
							<tr>
								<td>{{date('d/M/Y H:i', item.createdon)}}</td>
								<td>
									<div class="">
										<div class="title" style="font-size: 1em !important;">
											{% if item.smartmanagment.name is defined AND item.smartmanagment.name is not null%}
												{{item.smartmanagment.name}}
											{% else %}
												Indefinido
											{% endif %}		
										</div>
										<div class="title-info" style="padding-left: 0 !important;">
											{% if item.smartmanagment.description is defined AND item.smartmanagment.description is not null%}
												{{item.smartmanagment.description}}
											{% else %}
												Indefinido
											{% endif %}
										</div>
									</div>
								</td>
								<td>
									<div class="">
										<div class="title" style="font-size: 1em !important;">
											{% if item.mail.name is defined AND item.mail.name is not null%}
												{{item.mail.name}}
											{% else %}
												Indefinido
											{% endif %}		
										</div>
										<div class="title-info" style="padding-left: 0 !important;">
											{% if item.mail.subject is defined AND item.mail.subject is not null%}
												{{item.mail.subject}}
											{% else %}
												Indefinido
											{% endif %}
										</div>
									</div>
								</td>
								<td>
									<div style="display: inline;">{{item.score}}</div>
									<div style="display: inline;float: right;">
										{% if item.score > 0 %}
											<img src="{{url('')}}vendors/bootstrap_v3/images/smile.png" height="30" width="30">
										{% elseif item.score < 0%}
											<img src="{{url('')}}vendors/bootstrap_v3/images/sad.png" height="30" width="30">
										{% endif %}
									</div>
								</td>
								<td>
									{{accumulated[item.idScorehistory]}}
								</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 <div class="text-center">
					<ul class="pagination">
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}"><i class="glyphicon glyphicon-fast-backward"></i></a>
						</li>
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}?page={{ page.before }}"><i class="glyphicon glyphicon-step-backward"></i></a>
						</li>
						<li>
							<span><b>{{page.total_items}}</b> registros </span><span>Página <b>{{page.current}}</b> de <b>{{page.total_pages}}</b></span>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}?page={{page.next}}"><i class="glyphicon glyphicon-step-forward"></i></a>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('smartmanagment/behavior') }}/{{account.idAccount}}?page={{page.last}}"><i class="glyphicon glyphicon-fast-forward"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	{% else %}
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="bs-callout bs-callout-warning">
					<h4>Esta cuenta no tiene ninguna puntuación</h4>
					<p>
						Esta cuenta no tiene un historial de puntuación, esto quiere decir que es nueva, o no ha cumplido ninguna gestión inteligente
					</p>
				</div>
			</div>
		</div>
	{% endif %}
	
	<div class="space"></div>
		<div class="container-fluid">
		<h1 class="sectiontitle">Qué quiere hacer hoy?</h1>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{url('mail/compose')}}"  class="shortcuts"><span class="sm-button-large-email-new"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{url('mail/compose')}}" class="btn-actn">Crear un nuevo email</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{url('contactlist#/lists')}}"  class="shortcuts"><span class="sm-button-large-contact-list"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{url('contactlist#/lists')}}" class="btn-actn">Listas de contactos</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{url('mail/list')}}"  class="shortcuts"><span class="sm-button-large-email-list"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{url('mail/list')}}" class="btn-actn">Listas de correos</a>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	
{% endblock %}	