{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-facebook-sign icon-2x"></i><i class="icon-twitter-sign icon-2x"></i>Administración de redes sociales{% endblock %}
{%block sectionsubtitle %}Cree o elimine sus cuentas de redes sociales{% endblock %}

{% block content %}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar green">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Administre sus cuentas de redes sociales
						</div>
						<div class="news-text">
							Aqui puede crear o eliminar sus redes sociales.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box table-container-social-media-list">
				<div class="box-header">
					<div class="title">
						Cuentas
					</div>
					<div class="pull-right social-media-new-account">
						<a href="{{fbloginUrl}}">Nuevo Facebook</a>
						<a href="{{twloginUrl}}">Nuevo Twitter</a>
					</div>
				</div>
				<div class="box-content">
					<table class="table table-bordered">
						<thead>
						</thead>
						<tbody>
							 {%for account in accounts%}
								<tr>
									<td class="social-account-icon">
										{%if account.type is 'Facebook'%}
											<img src="{{url('images/facebook-icon-30.png')}}">
										{%else%}
											<img src="{{url('images/twitter-icon-30.png')}}">
										{%endif%}
									</td>
									<td class="social-account-icon">
										{%if account.category is 'Profile'%}
											<i class="icon-user icon-2x"></i><br><span>Perfil</span>
										{%else%}
											<i class="icon-group icon-2x"></i><br><span>Fan Page</span>
										{%endif%}
									</td>
									<td class="social-account-name-column">
										{%if account.status is 'Deactivated'%}
											<div class="social-account-name socil-account-deactivated">{{account.name}} <span>(Desactivado)</span></div>
										{%else%}
											<div class="social-account-name">{{account.name}}</div>
										{%endif%}
									</td>
									<td class="btns-social-column">
										<a class="ShowDialog btn btn-default" data-toggle="modal" href="#delete-modal-social" data-id="{{url('socialmedia/delete')}}/{{account.idSocialnetwork}}"><i class="icon-trash"></i> Eliminar</a>
										{%if account.status is 'Deactivated' and account.type is 'Facebook'%}
											<a class="ShowDialog btn btn-default" href="{{fbloginUrl}}" style="width: 58px;"><i class="icon-pencil"></i> Activar</a>
										{%elseif account.status is 'Deactivated' and account.type is 'Twitter'%}
											<a class="ShowDialog btn btn-default" href="{{twloginUrl}}" style="width: 58px;"><i class="icon-pencil"></i> Activar</a>
										{%endif%}
									</td>
								</tr>
							{%endfor%}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="delete-modal-social" class="modal hide fade" aria-hidden="false">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <h6 id="modal-tablesLabel">Eliminar Mensaje</h6>
		</div>
		<div class="modal-body">
			<p>Esta seguro que desea eliminar esta cuenta.</p>
			<p>Recuerde que debe revocar los permisos desde su cuenta de red social.</p>
		</div>
		<div class="modal-footer">
		  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
		  <a href="" id="deleteMsg" class="btn btn-danger" >Eliminar</a>
		</div>
	</div>

	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMsg").attr('href', myURL );
		});
	</script>

{% endblock %}