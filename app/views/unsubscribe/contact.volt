{% extends "templates/signin.volt" %}

{% block content %}
	{{ stylesheet_link('css/prstyles.css') }}

	<div class="navbar navbar-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="{{url('')}}">Mail Station</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="error-box">
					{% if dbase%}
					<div class="message-small">¿Esta seguro que desea desuscribirse?</div>
					<div class="message-small">Este contacto pertenece a la base de datos {{dbase.name}} porque {{dbase.Cdescription}}</div>
					<table>
						<tr>
							<td>Email</td>
							<td>{{email.email}}</td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td>{{contact.name}}</td>
						</tr>
						<tr>
							<td>Apellido</td>
							<td>{{contact.lastName}}</td>
						</tr>
					</table>
					<br />
					<a href="{{url('unsubscribe/success')}}/{{parameters}}" class="btn btn-blue unsubscribe-button"><i class="icon-warning-sign"></i> Desuscribirse</a>
					{% else %}
						<div class="message-small">El contacto {{contact.name}} {{contact.lastName}} ({{email.email}}) ya se encuentra desuscrito</div>
					{%endif%}
				</div>
			</div>
		</div>
	</div>
{% endblock %}