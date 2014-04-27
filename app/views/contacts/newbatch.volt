{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('js/load_activecontacts.js')}}
{% endblock %}
{% block sectiontitle %}
	<i class="icon-user"></i> Contactos
{% endblock %}
{% block sectionContactLimit %}
	{{ partial("partials/contactlimitinfo_partial") }}
{%endblock%}
{%block sectionsubtitle %}Creacion Rapida de Contactos{% endblock %}
{% block content %}

	{# Insertar botones de navegacion #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}


	<div class="row">
		<h4 class="sectiontitle">Resultado creación rápida de contactos</h4>

		{# Aqui lightbox de operacion exitosa #}

		<ul class="list-inline">
			<li>Contactos válidos:<span class="blue big-number"> {{total}}</span></li>
		</ul>

		<table class="table table-striped table-contacts">
			<thead>
				<tr>
					<td>Email</td>
					<td>Nombre</td>
					<td>Apellido</td>
					<td>Estado</td>
				</tr>
			</thead>
			<tbody>
				{%for content in batch%}
					<tr class="{{content['isValid']?'text-success':'text-danger'}}">
						<td>{{content['email']}}</td>
						<td>{{content['name']}}</td>
						<td>{{content['lastName']}}</td>
						<td>{{content['status']}}</td>
					</tr>
				{%endfor%}
			</tbody>
		</table>
	</div>
	<a href="{{ url('contactlist/show/') }}{{idContactlist}}#/contacts">Regresar a la lista de contactos</a>
{% endblock %}


	
