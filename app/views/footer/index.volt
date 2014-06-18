{% extends "templates/index_b3.volt" %}
{% block content %}
		{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'footer']) }}

	<div class="row">
		<h4 class="sectiontitle">Footer</h4>
		<div class="bs-callout bs-callout-info">
			Aquí puede ver, crear o editar los footers que podra asociar a una cuenta.
		</div>
	</div>

	<div class="row">
		{{ flashSession.output() }}
	</div>

	<div class="row">
		<div class="text-right">
			<a href="{{ url('footer/new') }}" class="btn btn-default btn-sm extra-padding"><span class="glyphicon glyphicon-plus"></span> Crear nuevo footer</a>
		</div>
	</div>

	<div class="row">
		<table class="table table-striped">
			{%for item in footers%}
				<tr>
					<td>{{item.name}}</td>
					<td class="text-right">
						<a href="{{ url('footer/duplicate/') }}{{item.idFooter}}" class="btn btn-default btn-sm extra-padding"><i class="glyphicon glyphicon-tags"></i> Duplicar</a>
						<a href="{{ url('footer/edit/') }}{{item.idFooter}}" class="btn btn-default btn-sm extra-padding"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
						<a href="{{ url('footer/delete/') }}{{item.idFooter}}" class="btn btn-default btn-delete btn-sm extra-padding ShowDialog"><i class="glyphicon glyphicon-trash"></i> Eliminar </a>
					</td>
				</tr>
			{%endfor%}
		</table>
	</div>

{% endblock %}