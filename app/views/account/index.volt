{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-sitemap"></i> Cuentas{%endblock%}
{%block sectionsubtitle %}Administre las cuentas en la aplicación{% endblock %}

{% block content %}
	<div class="row">
		<h4>Administre las cuentas de la aplicación</h4>
		<div class="news-text">
			Aqui puede ver, crear o editar las cuentas de la apliación, como tambien administrar los usuarios
			de dichas cuentas.
		</div>
		<div class="span7">
			{{ flashSession.output() }}
		</div>
		<div class="span5 text-right">
			<a href="{{ url('account/new') }}" class="btn btn-default"><i class="icon-plus"></i> Crear nueva cuenta</a>
			<a href="{{ url('') }}" class="btn btn-default"><i class="icon-reply"></i> Página principal</a>
		</div>
	</div>
	<div class="row">
		<table class="table table-normal">
			<thead>
				<tr>
					<td>Listado de cuentas</td>
					<td>Espacio en disco (Mb)</td>
					<td>Limite de contactos</td>
					<td>Limite de mensajes</td>
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
						<div class="box-section news with-icons">
							<div class="avatar cyan">
								<span><i class="icon-sitemap icon-2x"></i></span>
							</div>
							<div class="news-content">
								<div class="news-title">
									<a href="{{ url('account/show/') }}{{item.idAccount}}">{{item.companyName}}</a>
								</div>
								<div class="news-text">
									{{item.accountingMode}}<br/>
									{{item.subscriptionMode}}
								</div>
							</div>
						 </div>
					</td>
					<td>{{item.fileSpace}}</td>
					<td>{{item.contactLimit}}</td>
					<td>{{item.messageLimit}}</td>
					<td>{{item.virtualMta}}</td>
					<td>{{date('d/m/Y', item.createdon)}}</td>
					<td>{{date('d/m/Y', item.updatedon)}}</td>
					<td>
						<div class="pull-right" style="margin-right: 10px;">
							<div class="btn-group">
								<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="{{ url('account/show') }}/{{item.idAccount}}"><i class="icon-search"></i> Ver detalles</a></li>
									<li><a href="{{ url('account/edit') }}/{{item.idAccount}}"><i class="icon-pencil"></i> Editar</a></li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
		 {%endfor%}
			</tbody>
		</table>
	</div>
	<div class="box-footer padded">
		<div class="pagination pagination-left span5">
			<ul>
				{% if  page.current == 1%}
					<li class="previous"><a href="#" class="inactive"><<</a></li>
					<li class="previous"><a href="#" class="inactive"><</a></li>							
				{% else %}
					<li class="previous active"><a href="{{ url('account/index') }}"><<</a></li>
					<li class="previous active"><a href="{{ url('account/index') }}?page={{page.before}}"><</a></li>							
				{% endif%}
				{% if  page.current >= page.total_pages %}
					<li class="next"><a href="#" class="inactive">></a></li>
					<li class="next"><a href="#" class="inactive">>></a></li>
				{% else %}
					<li class="next active"><a href="{{ url('account/index') }}?page={{page.next}}">></a></li>
					<li class="next active"><a href="{{ url('account/index') }}?page={{page.last}}">>></a></li>
				{% endif %}
			</ul>
		 </div>
		 <div class="span4">
			Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
			Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
		</div>
	<div class="row text-right">
		<a href="{{ url('account/new') }}" class="btn btn-default"><i class="icon-plus"></i> Crear nueva cuenta</a>
		<a href="{{ url('') }}" class="btn btn-default"><i class="icon-reply"></i> Página principal</a>
	</div>
{% endblock %}