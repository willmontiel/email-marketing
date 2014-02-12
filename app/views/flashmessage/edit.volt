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
{% block sectiontitle %}<i class="icon-bullhorn"></i> Editar un mensaje administrativo{%endblock%}
{% block sectionsubtitle %}Edite un mensaje administrativo{% endblock %}
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
						Editar mensaje administrativo
					</div>
				</div>
				<div class="box-content">
					<form action="{{url('flashmessage/edit')}}/{{message.idFlashMessage}}" method="post">
						<div class="padded">
							<label>*Nombre del mensaje: </label>
							<input type="text" name="name" value="{{message.name}}" required="required" autofocus="autofocus" class="span12">

							<label>*Mensaje: </label>
							<textarea name="message" rows="6" required="required" class="span12">{{message.message}}</textarea>
							
							<label>*Mostrar en: </label>
							<input type="radio" name="allAccounts" class="icheck" {% if message.accounts == 'all'%}checked{% endif %} value="all" id="all">
							<label for="all">Todas las cuentas</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="allAccounts" class="icheck" {% if message.accounts !== 'all'%}checked{% endif %} value="any" id="any">
							<label for="any">Determinadas cuentas</label>
								<div id="selectAccount" style="display: {% if message.accounts !== 'all'%}block{% else %}none{% endif %};">
									<br /><br />
									<select multiple="multiple" name="accounts[]"  id="accountSelect" class="chzn-select">
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
								<br /><br />
								<label>*Tipo de mensaje: </label>
								<ul class="padded separate-sections">
									<li>
										<div class="row-fluid">
											<div class="span6">
												<div>
													<input type="radio" name="typeMessage" class="icheck" {% if message.type == 'info'%}checked{% endif %} id="info" value="info">
													<label for="info" class="flashmessage-info">Informativo</label>
												</div>
												<div>
													<input type="radio" name="typeMessage" class="icheck" {% if message.type == 'block'%}checked{% endif %} id="warning" value="block">
													<label for="warning" class="flashmessage-block">Advertencia</label>
												</div>
											</div>
											<div class="span6">
												<div>
													<input type="radio" name="typeMessage" class="icheck" {% if message.type == 'success'%}checked{% endif %} id="success" value="success">
													<label for="success" class="flashmessage-success">Confirmación</label>
												</div>
												<div>
													<input type="radio" name="typeMessage" class="icheck" {% if message.type == 'error'%}checked{% endif %} id="error" value="error">
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
														<input type="text" class="add-on input-date-picker" name="start" value="{{date('d/m/Y H:i',message.start)}}" id="begin">
													</div>
												</div>
											</div>
											<div class="span6">
												<div id="date">
													<label>*Fecha y hora de fin:</label>
													<div id="scheduleArea2" class="input-append date" style="width: 400px;">
														<input type="text" class="add-on input-date-picker" name="end" value="{{date('d/m/Y H:i',message.end)}}" id="end">
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
