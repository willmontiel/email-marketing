{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="row">
		<h4 class="sectiontitle">Contabilidad y facturación</h4>
		<div class="bs-callout bs-callout-info">
			Desde aqui podrá ver el consumo de todos los clientes, para realizar labores de contabilidad y facturación
			organizado por mes anterior y mes actual.
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2"></th>
					<th colspan="2">Mes anterior (xxx)</th>
					<th colspan="2">Mes actual (xxx)</th>
				</tr>
				<tr>
					<th class="col-sm-1">id</th>
					<th class="col-sm-3">Cuenta</th>
					<th class="col-sm-2">Contactos</th>
					<th class="col-sm-2">Envíos</th>
					<th class="col-sm-2">Contactos</th>
					<th class="col-sm-2">Envíos</th>
				</tr>
			</thead>
			<tbody>
				{% for account in accounts%}
					<tr>
						<td>{{account['idAccount']}}</td>
						<td>{{account['account']}}</td>
						<td>{{account['lastMonth']}}</td>
						<td>0</td>
						<td>{{account['currentMonth']}}</td>
						<td>0</td>
					</tr>
				{% endfor %}	
			</tbody>
		  </table>
	</div>
{% endblock %}