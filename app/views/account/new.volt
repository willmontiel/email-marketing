{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function footerpreview() {
			$.post("{{url('footer/preview')}}/" + $('#idFooter').val(), function(preview){
				var e = preview.preview;
				$( "#preview-modal-content" ).empty();
				$('#preview-modal-content').append(e);
			});
		}
	</script>
{% endblock %}
	
{% block sectiontitle %}<i class="icon-sitemap"></i> Crear una nueva cuenta{%endblock%}
{%block sectionsubtitle %}Cree una cuenta asignandole al mismo tiempo un usuario administrador{% endblock %}

{% block content %}

	{# Botones de navegacion #}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}

	<div class="row">
		<h4 class="sectiontitle">Crear una nueva cuenta</h4>
		<div class="bs-callout bs-callout-info">
			Aquí puede crear una nueva cuenta, configurarla, etc
		</div>
		{{ flashSession.output() }}
	</div>
	<div class="row">
		<form action = "{{url('account/new')}}" class="form-horizontal" id="registerAccount" method="post" 'role':'form'>
				<div class="row">
					<div class="col-md-6">
						<h4 class="text-center">Datos de la cuenta</h4>
						<div class="form-group">
							<label for="companyName" class="col-sm-5 control-label">*Nombre de la cuenta:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('companyName', {'class': 'form-control'})}}
							</div>
						</div>
						
						<div class="form-group">
							<label for="prefix" class="col-sm-5 control-label">Prefijo:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('prefix', {'class': 'form-control'})}}
							</div>
						</div>
						
						<div class="form-group">
							<label for="fileSpace" class="col-sm-5 control-label">*Espacio disponible en disco (Mb):</label>
							<div class="col-md-6">
								{{ newFormAccount.render('fileSpace', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="contactLimit" class="col-sm-5 control-label">*Límite de contactos</label>
							<div class="col-md-6">
								{{ newFormAccount.render('contactLimit', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="messageLimit" class="col-sm-5 control-label">*Límite de mensajes</label>
							<div class="col-md-6">
								{{ newFormAccount.render('messageLimit', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="modeUse" class="col-sm-5 control-label">*Modo de uso:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('accountingMode', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="modeAccounting" class="col-sm-5 control-label">*Modo de pago:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('subscriptionMode', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">*MTA: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('virtualMta', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-5 control-label">*Url de dominio: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('idUrlDomain', {'class': 'form-control'} )}}
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">*Retornar correos rebotados a: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('idMailClass', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">*Seleccionar footer: </label>
							<div class="col-md-5">
								{{ newFormAccount.render('idFooter', {'class': 'form-control'}) }}
							</div>
							<div class="col-md-1">
								<a href="#preview-footer-modal" data-toggle="modal" class="btn btn-default pull-right" onclick="footerpreview();"><span class="glyphicon glyphicon-search"></span></a>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">*Footer Editable: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('footerEditable') }}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<h4 class="text-center">Datos del administrador de la cuenta</h4>
						<div class="form-group">
							<label for="firstName" class="col-sm-5 control-label">*Nombre:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('firstName', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="lastName" class="col-sm-5 control-label">*Apellido:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('lastName', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-5 control-label">*Dirección de correo electrónico:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('email', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">*Nombre de usuario:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('username', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">	
							<label for="password" class="col-sm-5 control-label">*Contraseña:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('password', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="password2" class="col-sm-5 control-label">*Repita la contraseña:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('password2', {'class': 'form-control'}) }}
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions pull-right">
					<div class="row">
						<div class="col-xs-6">
							<a href="{{ url('account') }}" class="btn btn-default btn-sm extra-padding">Cancelar</a>
						</div>
						<div class="col-xs-6">
							{{ submit_button("Registrar", 'class' : "btn btn-sm btn-default btn-guardar extra-padding", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
						</div>
					</div>
				</div>
			</form>
	</div>
	<div id="preview-footer-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Footer</h4>
				</div>
				<div class="modal-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}