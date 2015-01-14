{# index.volt de Tools #}
{% extends "templates/index_b3.volt" %}


{% block content %}

<div class="row">
	<!-- menu de opciones -->
	<h4 class="sectiontitle">Herramientas de administración</h4>
	<div class="container-fluid space">
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('account') }}"  class="shortcuts"><span class="sm-button-large-accounts"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('account') }}" class="btn-actn">Cuentas</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('user') }}"  class="shortcuts"><span class="sm-button-large-users"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('user') }}" class="btn-actn">Usuarios</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('flashmessage') }}"  class="shortcuts"><span class="sm-button-large-msj"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('flashmessage') }}" class="btn-actn">Mensajes Administrativos</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('process') }}"  class="shortcuts"><span class="sm-button-large-send-process"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('process') }}" class="btn-actn">Procesos de Envío y de Importación</a>
			</div>
		</div>

	</div>
	<div class="container-fluid">
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('scheduledmail/manage') }}"  class="shortcuts"><span class="sm-button-large-program-envios"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('scheduledmail/manage') }}" class="btn-actn">Programación de correos de todas las cuentas</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('socialmedia') }}"  class="shortcuts"><span class="sm-button-large-socialnet"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('socialmedia') }}" class="btn-actn">Cuentas de redes sociales</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('apikey') }}"  class="shortcuts"><span class="sm-button-large-socialnet"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('apikey') }}" class="btn-actn">API Keys</a>
			</div>
		</div>
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('footer') }}"  class="shortcuts"><span class="sm-button-large-socialnet"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('footer') }}" class="btn-actn">Footers</a>
			</div>
		</div>
	</div>
	<div class="space"></div>
	
	<div class="container-fluid">
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('pdf') }}"  class="shortcuts"><span class="sm-button-large-program-envios"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('pdf') }}" class="btn-actn">Plantillas para PDF</a>
			</div>
		</div>
			
		<div class="col-xs-6 col-md-3">
			<div class="big-btn-nav sm-btn-blue">
				<a href="{{ url('pdf/createbatch') }}"  class="shortcuts"><span class="sm-button-large-socialnet"></span></a>
			</div>
			<div class="w-190 center">
				<a href="{{ url('pdf/createbatch') }}" class="btn-actn">Crear lote de PDF's</a>
			</div>
		</div>
	</div>
	<div class="space"></div>
</div>


{% endblock %}