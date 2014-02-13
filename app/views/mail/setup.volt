{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function showsocials(){
			var container = $('.setup_socials_container');
			if (container.css('display') === 'none') {
				container.show();
			}
			else {
				container.hide();
			}
		}
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-envelope"></i>Correos{% endblock %}
{% block sectionsubtitle %}Envíe un correo a multiples contactos{% endblock %}
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
							Información de correo
						</div>
						<div class="news-text">
							<p>
								Aqui podrá ingresar la información básica del correo, como un nombre para el correo,
								el asunto, la direccion de correo desde donde se envía, etc.
							
								Una vez haya terminado de ingresar los datos, haga click en el botón siguiente para continuar 
								con el proceso.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			{% if mail is empty %}
				<div id="breadcrumbs">
					<div class="breadcrumb-button blue">
						<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</div>
					<div class="breadcrumb-button">
						<span class="breadcrumb-label"><i class="icon-edit"></i> Editar/Crear contenido</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</div>
					<div class="breadcrumb-button">
						<span class="breadcrumb-label"><i class="icon-group"></i> Seleccionar destinatarios</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</div>
					<div class="breadcrumb-button">
						<span class="breadcrumb-label"><i class="icon-calendar"></i> Programar envío</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</div>
				</div>
			{% else %}
				{{partial('partials/wizard_partial')}}
			{% endif %}
			
		</div>
	</div>
	<br />
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<div class="box offset3 span6">
			<div class="box-header">
				<div class="title">
					Nuevo correo
				</div>
			</div>
			<div class="box-content">
				<form action="{% if mail is empty %}{{url('mail/setup')}}{% else %}{{url('mail/setup')}}/{{mail.idMail}}{% endif %}" method="post" class="fill-up">
					<div class="padded">
						<label>*Nombre: </label>
						{{ MailForm.render('name') }}
						
						<label>*Asunto: </label>
						{{ MailForm.render('subject') }}
										
						<label>*Enviar desde este nombre: </label>
						{{ MailForm.render('fromName') }}
						
						<label>*Enviar desde este correo: </label>
						{{ MailForm.render('fromEmail') }}
						
						<label>Responder a este correo: </label>
						{{ MailForm.render('replyTo') }}
						
						<label onclick="showsocials()">Publicar y Compartir: </label>
						<div class="setup_socials_container" style="display: none">
							<label>Facebook</label>
							<div class="facebook_account_container">
								<select multiple="multiple" name="facebookaccounts[]" id="accounts_facebook" class="chzn-select">
									{% for fbsocial in fbsocials %}
										<option value="{{fbsocial.idSocialnetwork}}">{{fbsocial.name}}</option>
									{% endfor %}
								</select>
								<div class="add_facebook_account"><a href="{{fbloginUrl}}">Añadir cuenta</a></div>

								<label>Descripcion de Publicacion: </label>
								<textarea rows="4" cols="20" name="fbpublicationcontent" id="fbpublicationcontent" >Este correo fue enviado desde Sigma Movil a sus contactos seleccionados</textarea>
							</div>
							<div class="twitter_account_container">
								<select multiple="multiple" name="twitteraccounts[]" id="accounts_twitter" class="chzn-select">
									{% for twsocial in twsocials %}
										<option value="{{twsocial.idSocialnetwork}}">{{twsocial.name}}</option>
									{% endfor %}
								</select>
								<div class="add_twitter_account"><a href="{{twloginUrl}}">Añadir cuenta</a></div>

								<label>Descripcion de Tweet: </label>
								<textarea rows="4" cols="20" name="twpublicationcontent" id="twpublicationcontent" >Este correo fue enviado desde Sigma Movil a sus contactos seleccionados</textarea>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<a href="{{url('mail/index')}}" class="btn btn-default"><i class="icon-remove-sign"></i> Cancelar</a>
						<button class="btn btn-blue" name="direction" value="next">Siguiente <i class="icon-circle-arrow-right"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}