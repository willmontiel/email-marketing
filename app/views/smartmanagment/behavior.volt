{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# HighCharts & HighMaps #}
	{{ javascript_include('vendors/highcharts/highcharts.js')}}
	{{ javascript_include('vendors/highcharts/modules/exporting.js')}}
{% endblock %}	
{% block content %}
	<div class="space"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="title">Comportamiento en la plataforma</div>
		</div>
	</div>
	
	<div class="space"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header header-background">
			
			</div>
		</div>
	</div>
{% endblock %}	