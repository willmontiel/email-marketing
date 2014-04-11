{% block header_javascript %}
	{{ super() }}
	{{ stylesheet_link('b3/css/bootstrap.css') }}
	{{ stylesheet_link('b3/css/font-awesome.css') }}
	{{ stylesheet_link('css/prstyles.css') }}
	{{ stylesheet_link('b3/css/sm-email-theme.css') }}
	{{ stylesheet_link('b3/vendors/css/bootstrap-editable.css') }}
	{{ stylesheet_link('b3/vendors/css/jquery.gritter.css') }}

	<!--[if lt IE 9]>
	{{ javascript_include('javascripts/vendor/html5shiv.js') }}
	{{ javascript_include('javascripts/vendor/excanvas.js') }}
	<![endif]-->
	
	{{ javascript_include('b3/js/jquery-1.9.1.js') }}
	{{ javascript_include('b3/js/bootstrap.js') }}
	{{ javascript_include('b3/vendors/js/jquery.sparkline.js') }}
	{{ javascript_include('b3/vendors/js/spark_auto.js') }}
	{{ javascript_include('b3/vendors/js/bootstrap-editable.js') }}
	{{ javascript_include('b3/vendors/js/jquery.gritter.js') }}


	{{ javascript_include('js/jquery-1.9.1.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('bootstrap/js/bootstrap.js') }}

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
		$.ajax({
			url: "{{url('mail/previewhtml')}}",
			type: "POST",			
			data: { html: inf},
			error: function(msg){
				console.log(msg);
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg.statusText, sticky: false, time: 10000});
			},
			success: function(response) {
				$("#preview-modal").empty();
				$('#preview-modal').append('<span class="close-preview icon-remove icon-2x" data-dismiss="modal"></span>')
				$('<iframe frameborder="0" width="100%" height="100%" src="{{url('mail/previewdata')}}"/>').appendTo('#preview-modal');
			
				//var r = response.response;
				//console.log(inf);
				//$( "#content-template" ).empty();
				//$('<iframe frameborder="0" width="100%" height="390px"/>').appendTo('#content-template').contents().find('body').append(r);
			}
		});
		
	}
	</script>
{% endblock %}
{% block content %}
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<form id="html-content-form">
						<label>Cree contenido para el correo con HTML base: </label>
						<textarea rows="50" required id="redactor_content"></textarea>
						<br />
						<input onclick="verHTML(this.form)" href="#preview-modal" data-toggle="modal" type="button" value="Visualizar" class="btn btn-black">
				</form>
			</div>
		</div>
	
	</div>
	{#<div id="preview-modal" class="modal hide fade preview-modal">
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
	</div>#}
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
	<div id="preview-modal" class="modal hide fade preview-modal">
	</div>
{% endblock %}