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
		function new_sn_account(redirect){
			$.ajax(
				{
				url: "{{url('mail/savetmpdata')}}",
				type: "POST",			
				data: $('#setupform').serialize(),
				success: function(){
					window.location.href = redirect;
				}
			});
		}
		$(function() {
			if($('#accounts_facebook')[0].selectedOptions.length > 0){
				$('.fbdescription').show();
				$('.setup_socials_container').show();
			}	
			if($('#accounts_twitter')[0].selectedOptions.length > 0){
				$('.twdescription').show();
				$('.setup_socials_container').show();
			}
			$('#accounts_facebook').on('change', function() {
				if($(this)[0].selectedOptions.length > 0) {
					$('.fbdescription').show();
				}
				else {
					$('.fbdescription').hide();
				}
			});
	
			$('#accounts_twitter').on('change', function() {
				if($(this)[0].selectedOptions.length > 0) {
					$('.twdescription').show();
				}
				else {
					$('.twdescription').hide();
				}
			});
			
			$('#tweet-char-number').text($('#twpublicationcontent').attr('maxlength'));
			$('#twpublicationcontent').keyup(function() {
				var text = $(this).val();
				$('#tweet-char-number').text($(this).attr('maxlength') - text.length);
			});
		});
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
				<form action="{% if mail is empty %}{% if new == true %}{{url('mail/setup')}}/0/{{idTemplate}}/new{% else %}{{url('mail/setup')}}{% endif %}{% else %}{{url('mail/setup')}}/{{mail.idMail}}{% endif %}" method="post" class="fill-up" id="setupform">
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
						
						<label onclick="showsocials()">Publicar en Redes Sociales: </label>
						<div class="setup_socials_container" style="display: none">
							<div class="facebook_account_container">
								<label>Facebook</label>
								<select multiple="multiple" name="facebookaccounts[]" id="accounts_facebook" class="chzn-select">
									{% for fbsocial in fbsocials %}
										{%if value_in_array(fbsocial.idSocialnetwork, fbids) is true%}
											<option value="{{fbsocial.idSocialnetwork}}" selected>{{fbsocial.name}}</option>
										{%else%}
											<option value="{{fbsocial.idSocialnetwork}}">{{fbsocial.name}}</option>
										{%endif%}
									{% endfor %}
								</select>
								<div class="fbdescription" style="display: none">
									<label>Titulo de la Publicacion: </label>
									{{ MailForm.render('fbtitlecontent') }}
									<label>Descripcion de la Publicacion: </label>
									{{ MailForm.render('fbdescriptioncontent') }}
									<label>Mensaje de la Publicacion: </label>
									{{ MailForm.render('fbmessagecontent') }}
								</div>
								<div class="add_facebook_account"><a onclick="new_sn_account('{{fbloginUrl}}')">Añadir cuenta de Facebook</a></div>
							</div>
							<div class="twitter_account_container">
								<label>Twitter</label>
								<select multiple="multiple" name="twitteraccounts[]" id="accounts_twitter" class="chzn-select">
									{% for twsocial in twsocials %}
										{%if value_in_array(twsocial.idSocialnetwork, twids) is true%}
											<option value="{{twsocial.idSocialnetwork}}" selected>{{twsocial.name}}</option>
										{%else%}
											<option value="{{twsocial.idSocialnetwork}}">{{twsocial.name}}</option>
										{%endif%}
									{% endfor %}
								</select>
								<div class="twdescription" style="display: none">
									<label>Mensaje del Tweet: </label>
									{{ MailForm.render('twpublicationcontent') }}
									<div class="number-of-tweet-characters">
									<span id="tweet-char-number" class="label label-blue">1</span>
									</div>
								</div>
								<div class="add_twitter_account"><a onclick="new_sn_account('{{twloginUrl}}')">Añadir cuenta de Twitter</a></div>
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