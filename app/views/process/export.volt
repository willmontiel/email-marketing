{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="space"></div>
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
						<tr>
							<td>Tipo de contactos</td>
							<td>{{model.contactStatus}}</td>
						</tr>
						<tr>
							<td>Campos personalizados</td>
							<td>{{model.fields}}</td>
						</tr>
						<tr>
							<td>Contactos a procesar(Aprox)</td>
							<td>{{model.contactsToProcess}}</td>
						</tr>
						<tr>
							<td>Contactos procesados(Aprox)</td>
							<td>{{model.contactsProcessed}}</td>
						</tr>
						<tr>
							<td>Estado</td>
							<td>{{model.status}}</td>
						</tr>d>{{model.status}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="header">
					<div class="title">
						<a href="{{url('contacts/getexportfile')}}/{{export.idExportfile}}">
							Haga click aqui para descargar
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}