{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ stylesheet_link('js/pluggins-editor/dropzone/css/dropzone.css') }}
	{{ javascript_include('js/pluggins-editor/dropzone/dropzone.js')}}
	{{ javascript_include('js/editor/gallery.js') }}
	{{ javascript_include('js/editor/social_media_displayer.js') }}
	<script type="text/javascript">
		var config = {assetsUrl: "{{url('asset/show')}}", imagesUrl: "{{url('images')}}", baseUrl: "{{url()}}"};
		$(function() {
			{%for asset in assets%}
				var media = new Gallery("{{asset['thumb']}}", "{{asset['image']}}", "{{asset['title']}}", {{asset['id']}});
				media.createMedia();
				media.mediaSelected();
			{%endfor%}
		});
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-envelope"></i>Correos{% endblock %}
{% block sectionsubtitle %}Envíe un correo a multiples contactos{% endblock %}
{% block content %}

	{# Insertar botones de navegacion #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

	<div class="row">
		<h4 class="sectiontitle">Información de correo</h4>
		<div class="bs-callout bs-callout-info">
			<p>
				Aqui podrá ingresar la información básica del correo, como un nombre para el correo,
				el asunto, la direccion de correo desde donde se envía, etc.
			
				Una vez haya terminado de ingresar los datos, haga click en el botón siguiente para continuar 
				con el proceso.
			</p>
		</div>
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
		{{ flashSession.output()}}
		<div class="row">
			<h4 class="sectiontitle"> Nuevo correo</h4>
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
									<br />
									<div>
										<div>
											{{ MailForm.render('fbmessagecontent') }}
										</div>
										<div class="clearfix">
											<div style="float: left;margin-right: 15px;width: 154px;height: 154px;background-color: #FAFAFA;">
												<div>
													<div data-toggle="modal" data-backdrop="static" href="#images" class="edit-fb-image-tool icon-pencil icon-2x" style="position: relative;left: 2px;top: 4px;padding: 2px;border-radius: 4px;cursor: pointer;border: 1px solid #E4E4E4;background-color: #F5F5F5;"></div>
												</div>
												{{ MailForm.render('fbimagepublication') }}
												<img id="fb-share-image" src="/emarketing/images/260.png" width="154" height="154">
											</div>
											<div style="float: left;width: 67%;">
												{{ MailForm.render('fbtitlecontent') }}
												{{ MailForm.render('fbdescriptioncontent') }}
											</div>
										</div>
									</div>
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
	<div id="images" class="modal hide fade gallery-modal">
		<div class="modal-header gallery-header">
			<h3>Imagenes</h3>
		</div>

		<ul class="nav nav-tabs nav-tabs-in-modal">
			<li id="tabgallery" class="active">
				<a href="#gallery" data-toggle="tab">Galeria</a>
			</li>
			<li id="tabuploadimage" class="">
				<a href="#uploadimage" data-toggle="tab">Cargar</a>
			</li>
		</ul>

		<div class="modal-body">
			<div class="tab-content imagesbody">
				<div id="gallery" class="tab-pane active">

				</div>

				<div id="uploadimage" class="tab-pane well">
					<h2 class="text-center">Cargar Imagen</h2>
					<form action="{{url('asset/upload')}}" class="dropzone" id="my-dropzone">
						<div class="dz-message"><span>Suelte su Imagen Aqui! <br/><br/>(o Click)</span></div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div id="accept_cancel_image">
				<a href="#" class="btn btn-default" id="accept_change" data-dismiss="modal">Aplicar</a>
				<a href="#" class="btn btn-default" id="cancel_change" data-dismiss="modal">Cancelar</a>
			</div>
		</div>
	</div>
{% endblock %}