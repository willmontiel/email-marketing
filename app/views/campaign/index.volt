{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="row">
		<h4 class="sectiontitle">Autorespuestas</h4>
		<div class="container-fluid space">
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href="{{url('campaign/list')}}" class="shortcuts"><span class="sm-button-large-email-list"></span></a>
				</div>
				<div class="w-190 center">
					<a href="{{url('campaign/list')}}" class="btn-actn">Listas de autorespuestas</a>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href="" class="shortcuts"><span class="sm-button-large-send-process"></span></a>
				</div>
				<div class="w-190 center">
					<a href="" class="btn-actn">Cumpleaños</a>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href="" class="shortcuts"><span class="sm-button-large-email-new"></span></a>
				</div>
				<div class="w-190 center">
					<a href="" class="btn-actn">Eventos</a>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href="" class="shortcuts"><span class="sm-button-large-contact-list"></span></a>
				</div>
				<div class="w-190 center">
					<a href="" class="btn-actn">Suscrito</a>
				</div>
			</div>

			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href="" class="shortcuts"><span class="sm-button-large-program-envios"></span></a>
				</div>
				<div class="w-190 center">
					<a href="" class="btn-actn">Basada en tiempo</a>
				</div>
			</div>
		</div>
	</div>
	<div class="space"></div>
{% endblock %}