{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de su cuenta{% endblock %}

{% block content %}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar blue">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Administre los usuarios de la cuenta
						</div>
						<div class="news-text">
							Aqui puede editar información de los usuarios de la cuenta, puede dar permisos y quitarlos. Tambien puede
							crear nuevos usuarios como tambien eliminarlos.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid text-right">
		<a href="{{url('user/new')}}" class="btn btn-default"><i class="icon-plus"></i> Crear usuario</a>
		<a href="{{ url('') }}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
	</div>
	<br />
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<table class="table table-normal">
					<thead>
						<tr>
							<td></td>
							<td>Email</td>
							<td>Tipo de usuario</td>
							<td>Fecha de creación</td>
							<td>Última actualización</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
				{%for item in page.items%}
						<tr>
							<td>
								<div class="box-section news with-icons">
									<div class="avatar blue">
										<i class="icon-user icon-2x"></i>
									</div>
									<div class="news-content">
										<div class="news-title">
											{{item.username}}
										</div>
										<div class="news-text">
											{{item.firstName}}
											{{item.lastName}}
										</div>
									</div>
								</div>
							</td>
							<td>{{item.email}}</td>
							<td>{{item.userrole}}</td>
							<td>{{ date('d/m/Y',item.createdon)}}</td>
							<td>{{ date('d/m/Y',item.updatedon)}}</td>
							<td>
								<div class="pull-right">
									<div class="btn-group">
										<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="{{url('user/edit/')}}{{item.idUser}}"><i class="icon-pencil"></i> Editar</a></li>
											<li><a href="{{url('user/delete/')}}{{item.idUser}}"><i class="icon-trash"></i> Eliminar</a></li>
										</ul>
									</div>
								</div>
							</td>
						</tr>
				{% endfor %}
					</tbody>
				</table>
			</div>
			<div class="box-footer padded">
				<div class="row-fluid">
					<div class="span5">
						<div class="pagination">
							<ul>
								{% if page.current == 1 %}
									<li class="previous"><a href="#" class="inactive"><<</a></li>
									<li class="previous"><a href="#" class="inactive"><</a></li>
								{% else %}
									<li class="previous active"><a href="{{ url('user/index') }}"><<</a></li>
									<li class="previous active"><a href="{{ url('user/index') }}?page={{ page.before }}"><</a></li>
								{% endif %}

								{% if page.current >= page.total_pages %}
									<li class="next"><a href="#" class="inactive">></a></li>
									<li class="next"><a href="#" class="inactive">>></a></li>
								{% else %}
									<li class="next active"><a href="{{ url('user/index') }}?page={{page.next}}">></a></li>
									<li class="next active"><a href="{{ url('user/index') }}?page={{page.last}}">>></a></li>		
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
	<div class="row-fluid text-right">
		<a href="{{url('user/new')}}" class="btn btn-default"><i class="icon-plus"></i> Crear usuario</a>
		<a href="{{ url('') }}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
	</div>
{% endblock %}