{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ stylesheet_link('vendors/bootstrap-tagsinput/bootstrap-tagsinput.css')}}
	{{ javascript_include('vendors/bootstrap-tagsinput/bootstrap-tagsinput.js')}}

	{# Swicth master#}
	{{ javascript_include('vendors/bootstrap-switch-master/bootstrap-switch.js')}}
	{{ stylesheet_link('vendors/bootstrap-switch-master/bootstrap-switch.css') }}
	<script type="text/javascript">
		function footerpreview() {
			$.post("{{url('footer/preview')}}/" + $('#idFooter').val(), function(preview){
				var e = preview.preview;
				$( "#preview-modal-content" ).empty();
				$('#preview-modal-content').append(e);
			});
		}
		
		$(function () {
			$(".switch").bootstrapSwitch({
				size: 'mini',
				onColor: 'success',
				offColor: 'danger'
			});
		});
	</script>
{% endblock %}
	
{% block sectiontitle %}<i class="icon-sitemap"></i> Crear una nueva cuenta{%endblock%}
{%block sectionsubtitle %}Cree una cuenta asignandole al mismo tiempo un usuario administrador{% endblock %}

{% block content %}

	{# Botones de navegacion #}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}

	<div class="row">
		<h1 class="sectiontitle">Crear una nueva cuenta</h1>
		<div class="bs-callout bs-callout-info">
			Aquí puede crear una nueva cuenta, configurarla, etc
		</div>
		{{ flashSession.output() }}
	</div>
	<div class="row">
		<form action = "{{url('account/new')}}" class="form-horizontal" id="registerAccount" method="post" role="form">
				<div class="row">
					<div class="col-md-6">
						<h3 class="text-center">Datos de la cuenta</h3>
						<div class="form-group">
							<label for="companyName" class="col-sm-5 control-label">*Nombre de la cuenta: </label>
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
							<label for="fileSpace" class="col-sm-5 control-label">*Espacio disponible en disco (Mb): </label>
							<div class="col-md-6">
								{{ newFormAccount.render('fileSpace', {'class': 'form-control'}) }}
							</div>
						</div>
						<div class="form-group">
							<label for="contactLimit" class="col-sm-5 control-label">*Límite de contactos: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('contactLimit', {'class': 'form-control'}) }}
							</div>
						</div>
						
						<div class="form-group">
							<label for="messageLimit" class="col-sm-5 control-label">*Límite de mensajes: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('messageLimit', {'class': 'form-control'}) }}
							</div>
						</div>
						
						<div class="form-group">
							<label for="sender" class="col-sm-5 control-label">*Remitente(s):</label>
							<div class="col-md-6">
								{{ newFormAccount.render('sender', {'class': 'form-control', 'data-role' : 'tagsinput', 'placeholder' : 'Escriba y luego presione enter...'}) }}
							</div>
						</div>
						
						<div class="form-group">
							<label for="senderAllowed" class="col-sm-5 control-label">¿Permitir al usuario agregar más remitentes?:</label>
							<div class="col-md-6">
								{{ newFormAccount.render('senderAllowed', {'class': 'form-control'}) }}
							</div>
						</div>
						
						{#
						<div class="form-group">
							<label for="remittent" class="col-sm-5 control-label">*Remitente(s)</label>
							<div class="col-md-3">
								{{ newFormAccount.render('remittent', {'class': 'form-control', 'placeholder' : 'Email', 'id' : 'remittent'}) }}
							</div>
							<div class="col-md-3">
								{{ newFormAccount.render('remittentName', {'class': 'form-control', 'placeholder' : 'Nombre', 'id' : 'remittentName'}) }}
							</div>
						</div>
						#}
						
						
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
						
						<div class="form-group">
							<label class="col-sm-5 control-label">*Estado: </label>
							<div class="col-md-6">
								{{ newFormAccount.render('status', {'class': 'switch', 'checked' : 'checked'}) }}
							</div>
						</div>
					</div>
						
					<div class="col-md-6">
						<h3 class="text-center">Datos del administrador de la cuenta</h3>
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
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h1 class="modal-title">Footer</h1>
				</div>
				<div class="modal-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
