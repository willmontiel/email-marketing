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
		<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
			<div class="header-background">
				<table class="table table-striped table-bordered">
					<thead></thead>
					<tbody>
						<tr>
							<td><strong>{{criteria}}</strong></td>
							<td>{{model.name}}</td>
						</tr>
						<tr>
							<td><strong>Tipo de contactos</strong></td>
							<td>{{export.contactStatus}}</td>
						</tr>
						<tr>
							<td><strong>Campos personalizados</strong></td>
							<td>{{export.fields}}</td>
						</tr>
						<tr>
							<td><strong>Contactos a procesar(Aprox)</strong></td>
							<td>{{export.contactsToProcess}}</td>
						</tr>
						<tr>
							<td><strong>Contactos procesados(Aprox)</strong></td>
							<td>{{export.contactsProcessed}}</td>
						</tr>
						<tr>
							<td><strong>Estado</strong></td>
							<td>{{export.status}}</td>
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