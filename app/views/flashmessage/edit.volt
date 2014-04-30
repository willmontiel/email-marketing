{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('javascripts/moment/moment.min.js')}}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.min.js')}}
	{{ stylesheet_link('bootstrap/datepicker/css/bootstrap-datetimepicker.min.css') }}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.es.js')}}
	<script type="text/javascript">
		$(function(){
			var val = $('input[name=allAccounts]:checked').val();
			switch (val) {
				case "all":
					$("#selectAccount").hide();
					break;
				case "any":
					$("#selectAccount").show();
					break;
			}
			
			$("input[name=allAccounts]").on('click', function () { 
				$('#accountSelect').val("");
				
				var button = $('input[name=allAccounts]:checked').val();
				
				switch (button) {
					case "all":
						$("#selectAccount").hide();
						break;
					case "any":
						$("#selectAccount").show();
						break;
				}
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
				pickSeconds: false,
				startDate: now
			});
			
			$('#scheduleArea2').datetimepicker({
				language: 'es',
				maskInput: true,
				pickTime: true,
				format: "MM/DD/YYYY H:mm",
				pickSeconds: false,
				startDate: now
			});
		});	
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'flashmessage']) }}
		</div>
	</div>
	
	<div class="row">
		<h4 class="sectiontitle">Editar mensaje flash</h4>
		
		<div class="bs-callout bs-callout-info">
			Edite cualquier configuración de un mensaje administrativo flash, desde de el nombre, el mensaje y hasta las cuentas en donde
			se debe mostrar.
		</div>
	</div>
	
	<div class="row">
		{{flashSession.output()}}
	</div>
	
	<div class="row">
		<div class="col-md-8">
			<form action="{{url('flashmessage/edit')}}/{{message.idFlashMessage}}" method="post" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="name" class="col-sm-3 control-label">*Nombre del mensaje: </label>
					<div class="col-sm-8">
						<input type="text" name="name" value="{{message.name}}" required="required" autofocus="autofocus" class="form-control">
					</div>
				</div>
					
				<div class="form-group">
					<label for="message" class="col-sm-3 control-label">*Mensaje:</label>
					<div class="col-sm-8">
						<textarea name="message" rows="6" required="required" class="form-control">{{message.message}}</textarea>
					</div>
				</div>
				
				<div class="form-group">
					<label for="allAccounts" class="col-sm-3 control-label">*Mostrar en:</label>
					<div class="col-sm-3">
						<input type="radio" name="allAccounts" {% if message.accounts == 'all'%}checked{% endif %} value="all" id="all">
						<label for="all">Todas las cuentas</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					
					<div class="col-sm-5">
						<input type="radio" name="allAccounts" {% if message.accounts !== 'all'%}checked{% endif %} value="any" id="any">
						<label for="any">Determinadas cuentas</label>
						<div id="selectAccount" style="display: {% if message.accounts !== 'all'%}block{% else %}none{% endif %};">
							<br />
							<select multiple="multiple" name="accounts[]" id="accountSelect" class="form-control">
								{% if message.accounts !== 'all'%}
									{% for account in accounts %}
										<option value="{{account.idAccount}}" {% for id in message.accounts|json_decode %}{% if id == account.idAccount%}selected{% endif %}{% endfor %}>{{account.idAccount}} - {{account.companyName}}</option>
									{% endfor %}
								{% else %}
									{% for account in accounts%}
										<option value="{{account.idAccount}}">{{account.idAccount}} - {{account.companyName}}</option>
									{% endfor %}
								{% endif %}
							</select>
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">*Tipo de mensaje:</label>
					<div class="col-sm-8">
						<select name="type" id="type" class="form-control">
							<option value="info" {% if message.type == 'info' %} selected="selected" {% endif %}>Info</option>
							<option value="warning" {% if message.type == 'warning' %} selected="selected" {% endif %}>warning</option>
							<option value="success" {% if message.type == 'success' %} selected="selected" {% endif %}>success</option>
							<option value="danger" {% if message.type == 'error' %} selected="selected" {% endif %}>error</option>
						</select>
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
						<div id="date">
							<input type="text" class="form-control col-sm-12" name="start" value="{{date('m/d/Y H:i',message.start)}}" id="scheduleArea1">
							
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">*Fecha y hora de fin:</label>
					<div class="col-sm-8">
						<div id="date">
							<input type="text" class="form-control col-sm-12" name="end" value="{{date('m/d/Y H:i',message.end)}}" id="scheduleArea2">
						</div>
					</div>
				</div>
					
				<br />
				
				<div class="form-group">
					<div class="col-sm-5">
						<a href="{{ url('flashmessage/index') }}" class="btn btn-sm btn-default extra-padding">Cancelar</a>
						{{ submit_button("Guardar", 'class' : "btn btn-sm btn-guardar extra-padding", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
					</div>
				</div>
				
			</form>
		</div>
	</div>
{% endblock %}
