{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.min.js')}}
	{{ stylesheet_link('bootstrap/datepicker/css/bootstrap-datetimepicker.min.css') }}
	<script type="text/javascript">
		$(function(){
			$("input[name=allAccounts]").on('ifChecked', function () { 
				$('#accountSelect').prop('selectedIndex',-1);
				$("#accountSelect").val('').trigger("liszt:updated");

				var val = $('input[name=allAccounts]:checked').val();
				switch (val) {
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
				language: 'en',
				maskInput: true,
				pickTime: true,
				format: "dd/MM/yyyy hh:mm",
				//pick12HourFormat: true,
				pickSeconds: false,
				startDate: now
			});
			
			$('#scheduleArea2').datetimepicker({
				language: 'en',
				maskInput: true,
				pickTime: true,
				format: "dd/MM/yyyy hh:mm",
				//pick12HourFormat: true,
				pickSeconds: false,
				startDate: now
			});
		});	
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bullhorn"></i> Crear un nuevo mensaje informativo{%endblock%}
{% block sectionsubtitle %}Cree un pequeño mensaje para informar sobre algo a una cuenta determinada o todas las cuentas{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span12">
			{{flashSession.output()}}
		</div>
	</div>
	<div class="row-fluid">
		<div class="span5 offset3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Nuevo mensaje flash
					</div>
				</div>
				<div class="box-content">
					{{ form('flashmessage/new', 'method': 'post') }}
						<div class="padded">
							<label>*Nombre del mensaje: </label>
								{{ text_field("name", 'type': "text", 'class': "span12", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Nombre del mensaje" ) }}

								<label>*Mensaje: </label>
								{{ text_area("message", 'type': "text", 'class': "span12", 'required': "required", 'rows': "6", 'placeholder': "Escriba el mensaje aqui" ) }}

								<label>*Mostrar en: </label>
								<input type="radio" name="allAccounts" class="icheck" checked value="all" id="all">
								<label for="all">Todas las cuentas</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="allAccounts" class="icheck" value="any" id="any">
								<label for="any">Determinadas cuentas</label>
								
								<div id="selectAccount" style="display: none;">
									<br /><br />
									<select multiple="multiple" name="accounts[]"  id="accountSelect" class="chzn-select">
										{% for account in accounts%}
											<option value="{{account.idAccount}}">{{account.idAccount}} - {{account.companyName}}</option>
										{% endfor %}
									</select>
								</div>
								<br /><br />
								<label>*Tipo de mensaje: </label>
								<ul class="padded separate-sections">
									<li>
										<div class="row-fluid">
											<div class="span6">
												<div>
													<input type="radio" name="typeMessage" class="icheck" checked id="info" value="info">
													<label for="info" class="flashmessage-info">Informativo</label>
												</div>
												<div>
													<input type="radio" name="typeMessage" class="icheck" id="warning" value="block">
													<label for="warning" class="flashmessage-block">Advertencia</label>
												</div>
											</div>
											<div class="span6">
												<div>
													<input type="radio" name="typeMessage" class="icheck" id="success" value="success">
													<label for="success" class="flashmessage-success">Confirmación</label>
												</div>
												<div>
													<input type="radio" name="typeMessage" class="icheck" id="error" value="error">
													<label for="error" class="flashmessage-error">Riesgo</label>
												</div>
											</div>
										</div>
									</li>
								</ul>
								<ul class="padded separate-sections">
									<li>
										<div class="row-fluid">
											<div class="span6">
												<div id="date">
													<label>*Fecha y hora de inicio:</label>
													<div id="scheduleArea1" class="input-append date" style="width: 400px;">
														<input type="text" class="add-on input-date-picker" name="start" id="begin">
													</div>
												</div>
											</div>
											<div class="span6">
												<div id="date">
													<label>*Fecha y hora de fin:</label>
													<div id="scheduleArea2" class="input-append date" style="width: 400px;">
														<input type="text" class="add-on input-date-picker" name="end" id="end">
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="form-actions">
								<a href="{{ url('flashmessage/index') }}" class="btn btn-default">Cancelar</a>
								{{ submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
