{# index.volt de Tools #}
{% extends "templates/index_b3.volt" %}


{% block content %}

<div class="row">
	<!-- menu de opciones -->
	<h4 class="sectiontitle">Herramientas de administración</h4>
	<div class="container-fluid space">
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('account') }}"  class="shortcuts"><span class="sm-button-large-email-new"></span></a>
			</div>
			<a href="{{ url('account') }}" class="btn-actn">Cuentas</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('user') }}"  class="shortcuts"><span class="sm-button-large-email-list"></span></a>
			</div>
			<a href="{{ url('user') }}" class="btn-actn">Usuarios</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('flashmessage') }}"  class="shortcuts"><span class="sm-button-large-plantillas"></span></a>
			</div>
			<a href="{{ url('flashmessage') }}" class="btn-actn">Mensajes Administrativos</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('process') }}"  class="shortcuts"><span class="sm-button-large-admin-prog"></span></a>
			</div>
			<a href="{{ url('process') }}" class="btn-actn">Procesos de Envio</a>
		</div>

	</div>
	<div class="container-fluid">
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('scheduledmail') }}"  class="shortcuts"><span class="sm-button-large-admin-prog"></span></a>
			</div>
			<a href="{{ url('scheduledmail') }}" class="btn-actn">Programación de Correos</a>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="to-do sm-btn-blue">
				<a href="{{ url('socialmedia') }}"  class="shortcuts"><span class="sm-button-large-admin-prog"></span></a>
			</div>
			<a href="{{ url('socialmedia') }}" class="btn-actn">Cuentas de redes sociales</a>
		</div>
	</div>
	<div class="space"></div>
</div>


{% endblock %}