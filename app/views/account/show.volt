{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-user-md"></i> Informacion de usuarios de cuentas{%endblock%}
{% block content %}
{{ content() }}
<div class="container-fluid padded">
	<div class="text-right">
		<a href="{{url('account/newuser/')}}{{idAccount}}" class="btn btn-default"><i class="icon-plus"></i> Crear nuevo usuario</a>
		<a href="{{ url('account/index') }}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-content">
					<table class='table table-normal'>
						<thead>
							<tr>
								<td></td>
								<td>Tipo de usuario</td>
								<td>Fecha de registro</td>
								<td>Última actualización</td>
							</tr>
						</thead>
					{%for item in page.items%}
						<tbody>
							<tr>
								<td>
									<div class="box-section news with-icons">
										<div class="avatar blue">
											<span style="color: white;">{{item.idUser}}</span>
										</div>
										<div class="news-content">
											<div class="news-title">
												{{item.username}}
											</div>
											<div class="news-text">
												{{item.firstName}} {{item.lastName}}<br />
												{{item.email}}
											</div>
										</div>
									</div>
								</td>
								<td>{{item.userrole}}</td>
								<td>{{date('Y-m-d', item.createdon)}}</td>
								<td>{{date('Y-m-d', item.updatedon)}}</td>
								<td>
									<div class="pull-right">
										<div class="btn-group">
											<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="{{url('account/edituser/')}}{{item.idUser}}"><i class="icon-pencil"></i> Editar</a></li>
												<li><a href="{{url('account/deleteuser/')}}{{item.idUser}}"><i class="icon-trash"></i> Eliminar</a></li>
											</ul>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					{%endfor%}
					</table>
				</div>
				<div class="box-footer">
					<div class="row-fluid">
						<div class="span5">
							<div class="pagination">
								<ul>
									{% if page.current == 1 %}
										<li class="previous"><a href="#" class="inactive"><<</a></li>
										<li class="previous"><a href="#" class="inactive"><</a></li>
									{% else %}
										<li class="previous active"><a href="{{ url('account/show') }}"><<</a></li>
										<li class="previous active"><a href="{{ url('account/show') }}?page={{ page.before }}"><</a></li>
									{% endif %}

									{% if page.current >= page.total_pages %}
										<li class="next"><a href="#" class="inactive">></a></li>
										<li class="next"><a href="#" class="inactive">>></a></li>
									{% else %}
										<li class="next active"><a href="{{ url('account/show') }}?page={{page.next}}">></a></li>
										<li class="next active"><a href="{{ url('account/show') }}?page={{page.last}}">>></a></li>		
									{% endif %}
								</ul>
							</div>
						</div>
						<div class="span5">
							<br />
							Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
							Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="text-right">
			<a href="{{ url('account/newuser') }}" class="btn btn-default"><i class="icon-plus"></i> Crear nuevo usuario</a>
			<a href="{{ url('account/index') }}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
		</div>
	</div>
</div>
{% endblock %}