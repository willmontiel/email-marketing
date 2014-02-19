{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('redactor/redactor.js')}}
	{{ javascript_include('redactor/langs/es.js')}}
	{{ stylesheet_link('redactor/redactor.css') }}
	{{ javascript_include('redactor/plugins/clips.js') }}
	{{ javascript_include('redactor/plugins/fontcolor.js') }}
	{{ javascript_include('redactor/plugins/fontfamily.js') }}
	{{ javascript_include('redactor/plugins/fontsize.js') }}
	{{ javascript_include('redactor/plugins/fullscreen.js') }}
	{{ javascript_include('redactor/plugins/textdirection.js') }}

	<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#redactor_content').redactor({
				imageUpload: '{{ url('asset/upload') }}/',
				imageGetJson: '{{ url ('asset/list') }}/',
				imageUploadErrorCallback: function(json) {
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: json.error, sticky: false, time: 10000});
				},
				lang: 'es',
				plugins: ['fontcolor', 'fontfamily', 'fontsize', 'fullscreen', 'clips'],
				fullpage: true
			});
		}
	);
	
	function verHTML(form) {
		var inf = form.content.value;
		$( "#content-template" ).empty();
		$('<iframe frameborder="0" width="100%" height="390px"/>').appendTo('#content-template').contents().find('body').append(inf);
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
							Cree contenido desde codigo fuente HTML
						</div>
						<div class="news-text">
							Esta función le permite crear contendo html desde cero, es recomendada para usuarios
							avanzados
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			{{partial('partials/wizard_partial')}}
		</div>
	</div>
	<br />
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
				</div>
				<div class="box-content">
					<form action = "{{url('mail/html')}}/{{mail.idMail}}" method="post">
						<div class="padded">
							<!---<input type="hidden" name="idMail" value="">-->
							<label>Cree su propio código HTML: </label>
							{{ MailForm.render('content') |slashes}}
						</div>
						<div class="form-actions">
							<button class="btn btn-default" value="prev" name="direction"><i class="icon-circle-arrow-left"></i> Anterior</button>
							<button class="btn btn-blue" value="next" name="direction">Siguiente <i class="icon-circle-arrow-right"></i></button>
							<input onclick="verHTML(this.form)" href="#preview-modal" data-toggle="modal" type="button" value="Visualizar" class="btn btn-black">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="preview-modal" class="modal hide fade preview-modal">
		<div class="modal-header">
			Previsualización de plantilla
		</div>
		<div class="modal-body">
			<div id="content-template" class="align-modal-body">
				
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-black" data-dismiss="modal">x</button>
		</div>
	</div>
	<div id="clipsmodal" style="display: none;">
	
	<section>
		<ul class="redactor_clips_box">
			<li>
				<a href="#" class="redactor_clip_link">Email</a>

				<div class="redactor_clip" style="display: none;">
					%%EMAIL%%
				</div>
			</li>
			<li>
				<a href="#" class="redactor_clip_link">Nombre</a>

				<div class="redactor_clip" style="display: none;">
					%%NOMBRE%%
				</div>
			</li>
			<li>
				<a href="#" class="redactor_clip_link">Apellido</a>

				<div class="redactor_clip" style="display: none;">
					%%APELLIDO%%
				</div>
			</li>
			{%if cfs is defined %}
				{%for cf in cfs%}
					<li>
						<a href="#" class="redactor_clip_link">{{cf['originalName']}}</a>

						<div class="redactor_clip" style="display: none;">
							%%{{cf['linkName']}}%%
						</div>
					</li>
				{%endfor%}
			{%endif%}
			<li>
				<a href="#" class="redactor_clip_link">Enlace de des-suscripcion</a>

				<div class="redactor_clip" style="display: none;">
					<a href="#%%UNSUBSCRIBE%%">Para des-suscribirse haga clic aqui</a>
					
				</div>
			</li>
		</ul>
	</section>
	<footer>
		<a href="#" class="redactor_modal_btn redactor_btn_modal_close">Close</a>
	</footer>
</div>
{% endblock %}