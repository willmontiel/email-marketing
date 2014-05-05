{% extends "templates/signin.volt" %}

{% block content %}
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{url('')}}">Email Sigma</a>
			</div>
		</div>
	</nav>
	
	<div class="jumbotron">
		<h1>Error 404<br/><small>Enlace no disponible</small></h1>
		<p>Revise su información. Si ha llegado aquí por un enlace dentro de la aplicación, por favor informe a nuestro <a href="mailto:soporte@sigmamovil.com">equipo de soporte</a>
		</p>
		<p>Haga <a href="{{url('')}}">clic aquí para regresar a la página principal</a></p>
	</div>
{% endblock %}