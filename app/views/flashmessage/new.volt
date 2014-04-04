{% extends "templates/index_b3.volt" %}
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
	<br />
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h2 class="text-center">Nuevo mensaje flash</h2>
			</blockquote>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-12">{{flashSession.output()}}</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<form action="{{url('flashmessage/new')}}" method="post" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="name" class="col-sm-4 control-label">*Nombre del mensaje: </label>
					<div class="col-sm-8">
						{{ MessageForm.render('name')}}
					</div>
				</div>	
					
				<div class="form-group">
					<label for="message" class="col-sm-4 control-label">*Mensaje:</label>
					<div class="col-sm-8">
						{{ MessageForm.render('message') }}
					</div>
				</div>
				
				<div class="form-group">
					<label for="allAccounts" class="col-sm-4 control-label">*Mostrar en:</label>
					<div class="col-sm-8">
						<input type="radio" name="allAccounts"  value="all" id="all">
						<label for="all">Todas las cuentas</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="allAccounts" value="any" id="any">
						<label for="any">Determinadas cuentas</label>
						<div id="selectAccount" style="display: none;">
							<label></label>
							{{ MessageForm.render('accounts') }}
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-4 control-label">*Tipo de mensaje:</label>
					<div class="col-sm-8">
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
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-4 control-label">*Fecha y hora de inicio:</label>
					<div class="col-sm-8">
						<div id="date">
							<div id="scheduleArea1" class="input-append date" style="width: 400px;">
								{{ MessageForm.render('start', {'id': 'begin'}) }}
							</div>
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-4 control-label">*Fecha y hora de fin:</label>
					<div class="col-sm-8">
						<div id="date">
							<div id="scheduleArea2" class="input-append date" style="width: 400px;">
								{{ MessageForm.render('end', {'id': 'end'}) }}
							</div>
						</div>
					</div>
				</div>
			</div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>
					
						<div class="form-actions">
							<a href="{{ url('flashmessage/index') }}" class="btn btn-default">Cancelar</a>
							{{ submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
						</div>
					</form>
		</div>
		<div class="col-md-2"></div>
	</div>
{% endblock %}
