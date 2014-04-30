{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-sitemap"></i> Crear una nueva cuenta{%endblock%}
{%block sectionsubtitle %}Cree una cuenta asignandole al mismo tiempo un usuario administrador{% endblock %}

{% block content %}

	{# Botones de navegacion #}
	{{ partial('account/partials/small_buttons_nav_partial', ['activelnk': 'account']) }}
	
	<div class="row">
		<h4 class="sectiontitle">Crear un nueva cuenta</h4>
		<div class="bs-callout bs-callout-info">
			Aqui puede crear una nueva cuenta, configurar .
		</div>
		{{ flashSession.output() }}
	</div>
	<div class="row">
		<h4>Datos de la cuenta</h4>
			<div class="box-content padded">
				{{ form('account/new', 'method': 'Post', 'class': 'form-horizontal', 'role':'form') }}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="companyName" class="col-sm-6 control-label">*Nombre de la cuenta:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('companyName', {'class': 'form-control'})}}
								</div>
							</div>
							<div class="form-group">
								<label for="fileSpace" class="col-sm-6 control-label">*Espacio disponible en disco (Mb):</label>
								<div class="col-md-6">
									{{ newFormAccount.render('fileSpace', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label for="contactLimit" class="col-sm-6 control-label">*Limite de contactos</label>
								<div class="col-md-6">
									{{ newFormAccount.render('contactLimit', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label for="messageLimit" class="col-sm-6 control-label">*Limite de mensajes</label>
								<div class="col-md-6">
									{{ newFormAccount.render('messageLimit', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label for="modeUse" class="col-sm-6 control-label">*Modo de uso:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('accountingMode', {'class': 'chzn-select'}) }}<br /> <br />
								</div>
							</div>
							<div class="form-group">
								<label for="modeAccounting" class="col-sm-6 control-label">*Modo de pago:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('subscriptionMode', {'class': 'chzn-select'}) }}<br /> <br />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label">*MTA: <label/>
								<div class="col-md-6">
									{{ newFormAccount.render('virtualMta', {'class': 'form-control'}) }}<br />
								</div>
							</div>
							<div class="form-group">	
								<label class="col-sm-6 control-label">*Url de dominio: <label/>
								<div class="col-md-6">
									{{ newFormAccount.render('idUrlDomain', {'class': 'form-control'} )}}<br /> <br />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label">*Retornar correos rebotados a: <label/>
								<div class="col-md-6">
									{{ newFormAccount.render('idMailClass', {'class': 'form-control'}) }}
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="firstName" class="col-sm-6 control-label">*Nombre:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('firstName', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label for="lastName" class="col-sm-6 control-label">*Apellido:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('lastName', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-sm-6 control-label">*Dirección de correo electronico:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('email', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-6 control-label">*Nombre de usuario:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('username', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">	
								<label for="password" class="col-sm-6 control-label">*Contraseña:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('password', {'class': 'form-control'}) }}
								</div>
							</div>
							<div class="form-group">
								<label for="password2" class="col-sm-6 control-label">*Repita la contraseña:</label>
								<div class="col-md-6">
									{{ newFormAccount.render('password2', {'class': 'form-control'}) }}
								</div>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<a href="{{ url('account') }}" class="btn btn-default">Cancelar</a>
						{{ submit_button("Registrar", 'class' : "btn btn-blue span2", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}