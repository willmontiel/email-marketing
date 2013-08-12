{% extends "templates/index.volt" %}

{% block content %}
	<div class="row-fluid">
		<div class="modal-header">
			<font face="ArdleysHand" size="20">
				Welcome to Mail Station
			</font>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">			
		</div>
	</div>
	<div class="row-fluid">
		<div class="text-left">
			<div class="span12">
				<div class="row-fluid">
					<a href="contactlist"><h3>Ver Bases de Datos</h3></a>
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
	</div>
{% endblock %}