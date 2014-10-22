{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="header">
					<div class="title">Resumen de exportación de contactos</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<table>
					<thead></thead>
					<tbody>
						<tr>
							<td>{{criteria}}</td>
							<td>{{model.name}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
{% endblock %}