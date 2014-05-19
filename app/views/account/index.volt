{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h4 class="sectiontitle">Cuentas</h4>
			<div class="bs-callout bs-callout-info">
				Aquí puede ver, crear o editar las cuentas de la apliación, como también administrar los usuarios
				de dichas cuentas.
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			{{ flashSession.output() }}
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-right">
			<a href="{{ url('account/new') }}" class="btn btn-default btn-sm extra-padding"><span class="glyphicon glyphicon-plus"></span> Crear nueva cuenta</a>
		</div>
	</div>
	<br />
	<div class="row">
		<table class="table table-striped">
			<thead>
				<tr>
					<td>Listado de cuentas</td>
					<td>Espacio en disco (Mb)</td>
					<td>Límite de contactos</td>
					<td>Límite de mensajes</td>
					<td>MTA</td>
					<td>Fecha de registro</td>
					<td>Última actualización</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
		 {%for item in page.items%}
				<tr>
					<td>
						<h5><strong><a href="{{ url('account/show/') }}{{item.idAccount}}">{{item.companyName}}</a></strong></h5>
						{{item.accountingMode}}<br/>
						{{item.subscriptionMode}}
					</td>
					<td>{{item.fileSpace}}</td>
					<td>{{item.contactLimit}}</td>
					<td>{{item.messageLimit}}</td>
					<td>{{item.virtualMta}}</td>
					<td>{{date('d/m/Y', item.createdon)}}</td>
					<td>{{date('d/m/Y', item.updatedon)}}</td>
					<td>
						<a href="{{ url('account/edit') }}/{{item.idAccount}}" class="btn btn-sm btn-default extra-padding" ><span class="glyphicon glyphicon-pencil"></span> Editar</a>
					</td>
				</tr>
		 {%endfor%}
			</tbody>
		</table>
	</div>

	<div class="col-sm-12 text-center">
		{#   Paginacion sin ember   #}
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'account/index']) }}
	</div>
{% endblock %}