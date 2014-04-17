{% extends "templates/signin.volt" %}

{% block content %}
<div class="jumbotron">
	<h1>Error 404<br/><small>Usted no tiene permiso para acceder a este recurso</small></h1>
	<p>Revise su información. Si ha llegado aquí por un enlace dentro de la aplicación, por favor informe a nuestro <a href="mailto:soporte@sigmamovil.com">equipo de soporte</a>
	</p>
	<p>Haga <a href="{{url('')}}">clic aquí para regresar a la página principal</a></p>
</div>

{% endblock %}