{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Correos{% endblock %}
{%block sectionsubtitle %}Administre sus correos{% endblock %}
{% block content %}

<!-- aqui inicia mi contenido -->
<div class="row">
	<!-- menu de opciones -->
	<h4 class="sectiontitle">Qué quieres hacer hoy?</h4>
	<div class="row">
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('mail/compose') }}"  class="shortcuts"><span class="sm-button-large-email-new"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('mail/compose') }}" class="btn-actn">Nuevo correo</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('mail/list') }}"  class="shortcuts"><span class="sm-button-large-email-list"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('mail/list') }}" class="btn-actn">Lista de correos</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('template/index') }}"  class="shortcuts"><span class="sm-button-large-plantillas"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('template/index') }}" class="btn-actn">Plantillas</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('scheduledmail') }}"  class="shortcuts"><span class="sm-button-large-admin-prog"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('scheduledmail') }}" class="btn-actn">Programación de envíos</a>
			</div>
		</div>
	</div>
</div>


{% endblock %}