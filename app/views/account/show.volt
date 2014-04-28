{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-user-md"></i> Informacion de usuarios de cuentas{%endblock%}
{%block sectionsubtitle %}Administre la información de los usuarios de la cuenta{% endblock %}

{% block content %}
	<div class="row">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar green">
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
	<div class="row">
		<div class="span8">
			{{ flashSession.output() }}
		</div>
		<div class="span4">	
			<div class="row-fluid text-right">
				<a href="{{url('account/newuser')}}/{{idAccount}}" class="btn btn-default"><i class="icon-plus"></i> Crear nuevo usuario</a>
				<a href="{{ url('account/index') }}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
			</div>
		</div>
	</div>
	<br />
	<div class="row">
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
												<li><a href="{{url('session/loginlikethisuser/')}}{{item.idUser}}">Ingresar como este usuario</a></li>
												<li><a href="{{url('account/edituser/')}}{{item.idUser}}">Editar</a></li>
												<li><a class="ShowDialog" data-toggle="modal" href="#modal-simple" data-id="{{url('account/deleteuser/')}}{{item.idUser}}">Eliminar</a></li>
											</ul>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					{%endfor%}
					</table>
				</div>
				<div class="box-footer padded">
					<div class="row">
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
	
<div id="modal-simple" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h6 id="modal-tablesLabel">Eliminar Usuario</h6>
	</div>
	<div class="modal-body">
		Esta seguro que desea eliminar este Usuario.
	</div>
	<div class="modal-footer">
	  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	  <a href="" id="deleteUser" class="btn btn-danger" >Eliminar</a>
	</div>
</div>

<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteUser").attr('href', myURL );
	});
</script>
{% endblock %}
