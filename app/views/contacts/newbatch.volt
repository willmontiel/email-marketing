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
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="box">
					<div class="box-header">
						<span class="title">Resultado importación por lotes de contactos</span>
						<ul class="box-toolbar">
							<li><span class="label label-green">Contactos válidos: {{total}}</span></li>
						</ul>
					</div>
					<div class="box-content">
						<table class="table table-normal">
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
				</div>
				{% if total+currentActiveContacts > account.contactLimit and account.accountingMode == 'Contacto'%}
					<br><br>
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
				<br><br>
				<a href="{{ url('contactlist/show/') }}{{idContactlist}}#/contacts/newbatch" class="btn btn-default">Cancelar</a>
				<a href="{{ url('contacts/importbatch/') }}{{idContactlist}}" class="btn btn-blue">Guardar</a>
			</div>
		</div>
	</div>
</div>
{% endblock %}


	
