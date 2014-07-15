{% extends "templates/index_b3.volt" %}
{% block content %}
	<br />
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3 class="text-center">Configuración del sistema</h3>
			</blockquote>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			{{flashSession.output()}}
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="col-xs-6 col-md-3 col-lg-2 big-btn-nav">
				<a href="{{url('system/configure')}}" class="shortcuts" title="Editar archivo de configuración"><span class="sm-button-large-email"></span><br/>Editar archivo de configuración</a>
			</div>
		</div>	
	</div>
{% endblock %}
