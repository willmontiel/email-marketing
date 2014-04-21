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
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'segments']) }}


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
				<tr>
					<td>{{content['email']}}</td>
					<td>{{content['name']}}</td>
					<td>{{content['lastName']}}</td>
					{% if content['status'] == "1" %}
					<td>Crear</td>
					{% else %}
					<td>Repetido</td>
					{% endif %}
				</tr>
			{%endfor%}
		</tbody>
	</table>
</div>
{% if total+currentActiveContacts > account.contactLimit and account.accountingMode == 'Contacto'%}
	<div class="alert alert-block">
		<a class="close" data-dismiss="alert">×</a>
		<h4 class="alert-heading">Advertencia!</h4><br>
		<p>Ha sobrepasado la capacidad máxima para guardar contactos:</p>
			<dl>
				<dd>Capacidad máxima de contactos: <span class="green-label">{{account.contactLimit}}</span></dd>
				<dd>Contactos actuales: <span class="blue-label">{{currentActiveContacts}}</span></dd>
				<dd>Contactos que intenta crear: <span class="orange-label">{{total}}</span></dd>
			</dl>
			<p>
				Se ha excedido en <span class="red-label">{{(currentActiveContacts+total)-account.contactLimit}}</span> contactos, si continúa con el proceso se guardarán los contactos hasta que llegue al limite,
				el resto serán ignorados.
			</p>
			<p>
				Si esta seguro y desea continuar dé click en crear
			</p>
	</div>
{% endif %}
	<button class="btn btn-default btn-sm">Volver a la lista</button>

{#
				<a href="{{ url('contactlist/show/') }}{{idContactlist}}#/contacts/newbatch" class="btn btn-default btn-sm">Cancelar</a>
				<a href="{{ url('contacts/importbatch/') }}{{idContactlist}}" class="btn btn-blue">Guardar</a>
#}
{% endblock %}


	
