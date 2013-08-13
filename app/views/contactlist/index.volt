{% extends "templates/index.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/emberlist_partial") }}
{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span12">
			<h1>Listas</h1>
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12">
			<p>Vea información detallada sobre sus listas de contactos</p>
		</div>
	</div>
	<br>
	<script type="text/x-handlebars" data-template-name="Applist">     
	<div class="row-fluid">
		<div class="span12">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="span3">
							Nombre
						</th>
						<th class="span4">
							Descripción
						</th>
						<th class="span2">
							Contactos
						</th>
						<th class="span2">
							Estado
						</th>
						<th class="span1">
							Acciones
						</th>
					</tr>
				</thead>
				</tbody>
					{{'{{#each controller}}'}}
					<tr>
						<td>{{ '{{name}}' }}</td>
						<td>{{ '{{description}}' }}</td>
						<td></td>
						<td></td>
						<td>
							<dl>
								<dd>Ver</dd>
								<dd>Editar</dd>
							</dl>
						</td>
					</tr>
					{{'{{/each}}'}}
				</tbody>
			</table>
		</div>
	</div>
	</script>
{%endblock%}