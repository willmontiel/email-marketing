{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ stylesheet_link('vendors/bootstrap-tagsinput/bootstrap-tagsinput.css')}}
	{{ javascript_include('vendors/bootstrap-tagsinput/bootstrap-tagsinput.js')}}
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
{% block content %}
	<div class="row">
		<div class="col-sm-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}
		</div>
	</div>

	<div class="row">
		<h1 class="sectiontitle">Edición de la cuenta</h1>
		<div class="bs-callout bs-callout-info">
			<p>Edite datos de cuentas o reconfigure.</p>
		</div>
	</div>	
	
	<div class="row">
		<div class="col-sm-12">
			{{ flashSession.output() }}
		</div>
	</div>

		<div clas="col-md-6">
			<form action = "{{url('account/edit/')}}{{account.idAccount}}" class="form-horizontal" id="registerAccount" method="post" role="form">
				<div class="form-group">
					<label class="col-md-4 control-label">*Nombre de la cuenta: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('companyName', {'class': 'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-md-4 control-label">Prefijo: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('prefix', {'class': 'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-md-4 control-label">*Espacio disponible en disco(Mb):</label>
					<div class="col-md-6">
						{{ editFormAccount.render('fileSpace', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Límite de contactos:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('contactLimit', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Límite de mensajes:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('messageLimit', {'class': 'form-control'}) }}
					</div>
				</div>
				
				<div class="form-group">
					<label for="remittent" class="col-sm-4 control-label">*Remitente(s):</label>
					<div class="col-md-6">
						{{ editFormAccount.render('sender', {'class': 'form-control', 'data-role' : 'tagsinput'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="remittentAllowed" class="col-sm-4 control-label">¿Permitir al usuario agregar más remitentes?:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('senderAllowed', {'class': 'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-md-4 control-label">*Modo de uso:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('accountingMode', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Modo de pago: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('subscriptionMode', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">					
					<label class="col-md-4 control-label">*MTA: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('virtualMta', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">					
					<label class="col-md-4 control-label">*Url de dominio: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('idUrlDomain', {'class': 'form-control'})}}
					</div>
				</div>
				<div class="form-group">								
					<label class="col-md-4 control-label">*Retornar correos rebotados a: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('idMailClass', {'class': 'form-control'})}}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">*Seleccionar footer: </label>
					<div class="col-md-5">
						{{ editFormAccount.render('idFooter', {'class': 'form-control'}) }}
					</div>
					<div class="col-md-1">
						<a href="#preview-footer-modal" data-toggle="modal" class="btn btn-default pull-right" onclick="footerpreview();"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">*Footer Editable: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('footerEditable') }}
					</div>
				</div>
				<div class="form-group wrapper">
					<div class="col-md-4 col-md-offset-4">
						<a href="{{ url('account') }}" class="btn btn-default btn-sm extra-padding">Cancelar</a>
						{{ submit_button("Grabar", 'class' : "btn btn-sm btn-default btn-guardar extra-padding", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides") }}
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
