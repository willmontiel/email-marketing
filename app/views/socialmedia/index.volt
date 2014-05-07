{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'socialmedia']) }}
		</div>
	</div>
	
	<div class="row">
		<h4 class="sectiontitle">Cuentas de redes sociales</h4>

		<div class="bs-callout bs-callout-info">
			Administre las redes sociales, configure facebook y twitter, para que cuando envíe una campaña de
			correo, se hagan post sobre éstas redes automáticamente.
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 text-right">
			<a href="{{fbloginUrl}}" class="btn btn-sm btn-default extra-padding">Nuevo Facebook</a>
			<a href="{{twloginUrl}}" class="btn btn-sm btn-default extra-padding">Nuevo Twitter</a>
		</div>
	</div>	
	

	{% if accounts|length != 0%}
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead></thead>
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
										<h4>Perfil</h4>
									{%else%}
										<h4>Fan Page</h4>
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
									<a class="ShowDialog btn btn-sm btn-default btn-delete extra-padding" data-toggle="modal" href="#delete-modal-social" data-id="{{url('socialmedia/delete')}}/{{account.idSocialnetwork}}">Eliminar</a>
									{%if account.status is 'Deactivated' and account.type is 'Facebook'%}
										<a class="ShowDialog btn btn-sm btn-default extra-padding" href="{{fbloginUrl}}">Activar</a>
									{%elseif account.status is 'Deactivated' and account.type is 'Twitter'%}
										<a class="ShowDialog btn btn-sm btn-default extra-padding" href="{{twloginUrl}}">Activar</a>
									{%endif%}
								</td>
							</tr>
						{%endfor%}
					</tbody>
				</table>
			</div>
		</div>
	{% else %}
		<div class="row">
			<div class="bs-callout bs-callout-warning">
				<h4>No hay redes sociales configuradas</h4>
				<p>
					Para configurar una red social (Facebook, Twitter) haga clic en los botones <strong>Nuevo Facebook</strong> o <strong>Nuevo Twitter</strong>
					que se sitúan en la parte superior derecha.
				</p>
			</div>
		</div>
	{% endif %}
		
		
			
	<div class="modal fade" id="delete-modal-social" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Eliminar Mensaje</h4>
				</div>
				<div class="modal-body">
					<p>Está seguro que desea eliminar ésta cuenta?</p>
					<p>Recuerde que debe revocar los permisos desde su cuenta de red social.</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteMsg" class="btn btn-sm btn-default btn-danger extra-padding" >Eliminar</a>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMsg").attr('href', myURL );
		});
	</script>

{% endblock %}