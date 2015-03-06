{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('vendors/redactor/redactor.js')}}
	{{ javascript_include('vendors/redactor/langs/es.js')}}
	{{ stylesheet_link('vendors/redactor/redactor.css') }}
	{{ javascript_include('vendors/redactor/plugins/clips.js') }}
	{{ javascript_include('vendors/redactor/plugins/fontcolor.js') }}
	{{ javascript_include('vendors/redactor/plugins/fontfamily.js') }}
	{{ javascript_include('vendors/redactor/plugins/fontsize.js') }}
	{{ javascript_include('vendors/redactor/plugins/fullscreen.js') }}
	{{ javascript_include('vendors/redactor/plugins/textdirection.js') }}
	{{ javascript_include('js/editor/forms_text_editor.js') }}
	{{ javascript_include('vendors/bootstrap_v2/spectrum/js/spectrum.js') }}
	{{ stylesheet_link('vendors/bootstrap_v2/spectrum/css/spectrum.css') }}

	<script type="text/javascript">
		{% if autoresponder is defined %}
			var idAutoresponder = {{autoresponder.idAutoresponder}};
		{% endif %}
		
		$(document).ready(
			function() {
				$('#redactor_content').redactor({
					imageUpload: '{{ url('asset/upload') }}/',
					imageGetJson: '{{ url ('asset/list') }}/',
					imageUploadErrorCallback: function(json) {
						$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: json.error, sticky: false, time: 10000});
					},
					lang: 'es',
					plugins: ['fontcolor', 'fontfamily', 'fontsize', 'fullscreen', 'clips', 'advanced'],
					fullpage: true
				});
			}
		);
	
	function verHTML() {
		var content = $('#redactor_content').val();
		
		$.ajax({
			url: "{{url('campaign/preview')}}",
			type: "POST",			
			data: { 
				type: 'html',
				content: content
			},
			error: function(msg){
				var txt = JSON.parse(msg.responseText);
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: txt.status, sticky: false, time: 2000});
			},
			success: function() {
				$('#preview-modal-content').empty();
				$('#preview-auto-send-modal').modal('show');
				$('#preview-modal-content').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('campaign/previewframe')}}"/>'));
			}
		});
	}
	
	function sendData() {
		var content = $('#redactor_content').val();
		$.ajax({
			url: "{{url('campaign/contenthtml')}}/" + idAutoresponder,
			type: "POST",			
			data: { content: content},
			error: function(msg){
				var obj = $.parseJSON(msg.responseText);
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.msg, sticky: false, time: 10000});
			},
			success: function(msg) {
				$(location).attr('href', "{{url('campaign/')}}" + msg.status + "/" + idAutoresponder);
			}
		});
	}
	
	function cancelProcess() {
		$.ajax({
			url: "{{url('campaign/getcancel')}}/" + idAutoresponder,
			type: "GET",			
			success: function(msg) {
				$(location).attr('href', "{{url('campaign/')}}" + msg.status + "/" + idAutoresponder);
			}
		});
	}
	</script>
{% endblock %}
{% block content %}
	<div class="container-fluid">
		<div class="space"></div>
		<div class="row">
			<div class="col-sm-6">
				<div class="text-left">
					<button onclick="verHTML();" class="btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#preview-modal">Visualizar</button>
				</div>
			</div>	
			<div class="col-sm-6">
				<div class="text-right">
					<button onclick="cancelProcess()" class="btn btn-sm btn-default extra-padding">Cancelar</button>
					<button onclick="sendData();" class="btn btn-sm btn-primary extra-padding">Guardar y regresar</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<br />
				<label>Cree contenido para el correo con HTML base: </label>
				<textarea rows="25" required id="redactor_content">{% if content is defined%} {{content}} {% elseif footer is defined and footer.editable == 1 %} {{footer.html}} {% endif %}</textarea>
				{% if footer is defined and footer.editable == 0 %}
					<div class="footer-html">
						{{footer.html}}
					</div>
				{% endif %}
			</div>
		</div>
		<div class="space"></div>
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
				<li>
					<a href="#" class="redactor_clip_link">Fecha de nacimiento</a>

					<div class="redactor_clip" style="display: none;">
						%%FECHA_DE_NACIMIENTO%%​
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
						<a href="%%UNSUBSCRIBE%%">Para des-suscribirse haga clic aqui</a>

					</div>
				</li>
				<li>
					<a href="#" class="redactor_clip_link">Enlace de version web</a>

					<div class="redactor_clip" style="display: none;">
						<a href="%%WEBVERSION%%">Version web</a>

					</div>
				</li>
			</ul>
		</section>
		<footer>
			<a href="#" class="redactor_modal_btn redactor_btn_modal_close">Close</a>
		</footer>
	</div>
	
	<div id="preview-auto-send-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Previsualización</h4>
				</div>
				<div class="modal-body modal-prevew-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
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
				<li>
					<a href="#" class="redactor_clip_link">Fecha de nacimiento</a>

					<div class="redactor_clip" style="display: none;">
						%%FECHA_DE_NACIMIENTO%%​
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
						<a href="%%UNSUBSCRIBE%%">Para des-suscribirse haga clic aqui</a>

					</div>
				</li>
				<li>
					<a href="#" class="redactor_clip_link">Enlace de version web</a>

					<div class="redactor_clip" style="display: none;">
						<a href="%%WEBVERSION%%">Version web</a>

					</div>
				</li>
			</ul>
		</section>
		<footer>
			<a href="#" class="redactor_modal_btn redactor_btn_modal_close">Close</a>
		</footer>
	</div>
	
	
	<div id="forms-update" style="display: none;">
		<section>
			<ul>
			{%if forms is defined %}
				{%for form in forms%}
					<li>
						<a href="#" name="chk_group" class="selected-form-class" value="%%FORM_{{form['idForm']}}%%">{{form['name']}}</a><br />
					</li>
				{%endfor%}
			{%endif%}
			</ul>
		</section>
		<footer>
			<button class="redactor_modal_btn redactor_btn_modal_close">Close</button>
		</footer>
	</div>
{% endblock %}