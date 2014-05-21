{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('javascripts/moment/moment.min.js')}}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.min.js')}}
	{{ stylesheet_link('bootstrap/datepicker/css/bootstrap-datetimepicker.min.css') }}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.es.js')}}
	<script type="text/javascript">
		$(function(){
			if ($('#all').prop('checked')) {
				$("#selectAccount").hide();
			}
			
			if ($('#any').prop('checked')) {
				$("#selectAccount").show();
			}
			
			$("input[name=allAccounts]").on('click', function () {
				$('input[name=certainAccounts]').attr('checked', false);
				$('#accounts').val("");
				$("#selectAccount").hide();
			});
			
			$("input[name=certainAccounts]").on('click', function () {
				$('input[name=allAccounts]').attr('checked', false);
				$("#selectAccount").show();
			});
		});
	</script>
	<script type="text/javascript">
		$(function(){
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), nowTemp.getHours(), nowTemp.getMinutes(), nowTemp.getSeconds(), 0);
		 //HH:mm PP
			$('#scheduleArea1').datetimepicker({
				language: 'es',
				maskInput: true,
				pickTime: true,
				format: "MM/DD/YYYY H:mm",
				//pick12HourFormat: true,
				pickSeconds: false,
				startDate: now
			});
			
			$('#scheduleArea2').datetimepicker({
				language: 'es',
				maskInput: true,
				pickTime: true,
				format: "MM/DD/YYYY H:mm",
				//pick12HourFormat: true,
				pickSeconds: false,
				startDate: now
			});
		});	
	</script>
{% endblock %}
{% block sectiontitle %}<span class="icon-bullhorn"></span> Crear un nuevo mensaje informativo{%endblock%}
{% block sectionsubtitle %}Cree un pequeño mensaje para informar sobre algo a una cuenta determinada o todas las cuentas{% endblock %}
{% block content %}
	{#   Navegacion botones pequeños   #}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'flashmessage']) }}
	
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo mensaje flash</h4>
		
		<div class="bs-callout bs-callout-info">
			Cree un nuevo mensaje administrativo flash, configure las cuentas en donde se debe mostrar, el color con el que debe mostrarse, la fecha de inicio y la fecha final en que debe dejar de mostrarse.
		</div>
	</div>

	{{flashSession.output()}}

	<div class="row">
		<div class="col-md-8">
			<form action="{{url('flashmessage/new')}}" method="post" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="name" class="col-sm-3 control-label">*Nombre del mensaje: </label>
					<div class="col-sm-8">
						{{ MessageForm.render('name')}}
					</div>
				</div>	
					
				<div class="form-group">
					<label for="message" class="col-sm-3 control-label">*Mensaje:</label>
					<div class="col-sm-8">
						{{ MessageForm.render('message') }}
					</div>
				</div>
				
				<div class="form-group">
					<label for="allAccounts" class="col-sm-3 control-label">*Mostrar en:</label>
					<div class="col-sm-3">
						{{ MessageForm.render('allAccounts') }}
						<label for="all">Todas las cuentas</label>
					</div>
					<div class="col-sm-5">
						{#
						<input type="radio" name="allAccounts"  value="all" id="all">
						<label for="all">Todas las cuentas</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						<input type="radio" name="allAccounts" value="any" id="any">
						<label for="any">Determinadas cuentas</label>
						#}
						{{ MessageForm.render('certainAccounts') }}
						<label for="any">Determinadas cuentas</label>
						<div id="selectAccount" style="display: none;">
							{{ MessageForm.render('accounts') }}
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">*Tipo de mensaje:</label>
					<div class="col-sm-8">
						{{ MessageForm.render('type', {'class' : 'form-control'} ) }}
						{#
						<div class="checkbox">
							{{ MessageForm.render('type', {'id': 'info', 'value': 'info'} ) }}
							<label for="info" class="flashmessage-info">Informativo</label>
							
							{{ MessageForm.render('type', {'id': 'warning', 'value': 'warning'}) }}
							<label for="warning" class="flashmessage-block">Advertencia</label>
							
							{{ MessageForm.render('type', {'id': 'success', 'value': 'success'}) }}
							<label for="success" class="flashmessage-success">Confirmación</label>
							
							{{ MessageForm.render('type', {'id': 'error', 'value': 'error'}) }}
							<label for="error" class="flashmessage-error">Riesgo</label>
						</div>
						#}
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">*Fecha y hora de inicio:</label>
					<div class="col-sm-8">
						<div id="scheduleArea1" class="input-append date" class="col-sm-12">
							{{ MessageForm.render('start', {'id': 'begin', 'class' : 'form-control'}) }}
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">*Fecha y hora de fin:</label>
					<div class="col-sm-8">
						<div id="scheduleArea2" class="input-append date" class="col-sm-12">
							{{ MessageForm.render('end', {'id': 'end', 'class' : 'form-control'}) }}
						</div>
					</div>
				</div>
				<br />
				<div class="form-group wrapper">
					<div class="col-sm-6 col-md-offset-3">
						<a href="{{ url('flashmessage/index') }}" class="btn btn-sm btn-default extra-padding">Cancelar</a>
						{{ submit_button("Guardar", 'class' : "btn btn-sm btn-default extra-padding btn-guardar", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
					</div>
				</div>
			</form>
		</div>
	</div>
{% endblock %}
