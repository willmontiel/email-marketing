{% extends "templates/index.volt" %}

{% block content %}
	<div class="row-fluid">
		<div class="modal-header">
			<h1>Welcome to E-Marketing</h1>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="alert-error"><h4>{{ flashSession.output() }}</h4></div>			
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<a href="dbase"><h3>Ver Bases de Datos</h3></a>
			</div>
			<div class="row-fluid">
				<a href="dbase/new"><h3>Crear Bases de Datos</h3></a>
			</div>
			<div class="row-fluid">
				<a href="account"><h3>Ver Cuentas</h3></a>
			</div>
			<div class="row-fluid">
				<a href="account/new"><h3>Crear Cuenta</h3></a>
			</div>
		</div>
	</div>
{% endblock %}