{% extends "templates/index.volt" %}

{% block content %}
<div class="row-fluid">
	<div class="span9">
		<div class="row-fluid">
			<div class="modal-header">
				<h1>Importacion de Contactos</h1>
			</div>
		</div>
			<div class="row-fluid">
				<table class="table table-hover">
	<thead>
		<tr>
			<th>Email</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
		
	{%for content in batch%}
		
		<tr>
			<td>{{content['email']}}</td>
			<td>{{content['name']}}</td>
			<td>{{content['last_name']}}</td>
			{% if content['status'] == "1" %}
			<td>Importar</td>
			{% else %}
			<td>No Importar</td>
			{% endif %}
		</tr>
	
	{%endfor%}
	</tbody>
</table>

<a href="/emarketing/contacts/importbatch/{{idDbase}}" class="btn btn-inverse">Importar</a>
<a href="/emarketing/dbase/show/{{idDbase}}#/contacts/newbatch" class="btn btn-inverse">Cancelar</a>

			</div>
	</div>
</div>


{% endblock %}


	