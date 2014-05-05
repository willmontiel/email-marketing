{% extends "templates/signin.volt" %}
{% block content %}
	{{ stylesheet_link('css/prstyles.css') }}
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{url('')}}">Email Sigma</a>
			</div>
		</div>
	</nav>
	
	<div class="container">
		<div class="row">
			<div class="bs-callout bs-callout-success">
				<h4>Contacto <strong>des-suscrito</strong> exitosamente</h4>
				<p>
					El contacto ha sido des-suscrito exitosamente
				</p>
			</div>
		</div>
	</div>
{% endblock %}