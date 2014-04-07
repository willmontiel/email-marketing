{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Correos{% endblock %}
{%block sectionsubtitle %}Administre sus correos{% endblock %}
{% block content %}

<!-- aqui inicia mi contenido -->
<div class="row">
	<!-- menu de opciones -->
	<h4>Qué quieres hacer hoy?</h4>
	<div class="row-fluid">
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('mail/setup') }}"  class="shortcuts"><span class="sm-button-large-email-new"></span></a>
			</div>
			<a href="" class="btn-actn">Nuevo correo</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('mail/setup') }}"  class="shortcuts"><span class="sm-button-large-email-list"></span></a>
			</div>
			<a href="" class="btn-actn">Lista de correos</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('template/index') }}"  class="shortcuts"><span class="sm-button-large-plantillas"></span></a>
			</div>
			<a href="" class="btn-actn">Administrar plantillas</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('scheduledmail') }}"  class="shortcuts"><span class="sm-button-large-admin-prog"></span></a>
			</div>
			<a href="" class="btn-actn">Administrar programación</a>
		</div>
	</div>
</div>


{% endblock %}