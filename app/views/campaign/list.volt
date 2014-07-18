{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}

	<script> 
		$(function (){
			$(".switch-campaign").bootstrapSwitch();
		});
	</script>
{% endblock %}
{% block content %}

{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

	<div class="row">
		<h4  class="sectiontitle">Lista de autorespuestas</h4>
		
		<div class="container-fluid">
		
			{# Primer autorespuesta #}

			<div class="col-md-12 col-sm-12" style="width: 90%; margin: auto; border-top: 1px solid rgb(216, 213, 213);">
				<div class="col-sm-2">
					<div class="image-64-autorespons img-64-n1"></div>
				</div>
				<div class="col-sm-2" style="margin-top:5%;">
					<div style="font-size: 60px;text-align: center;margin-left: 45%;"><span class="glyphicon glyphicon-calendar"></span></div>
				</div>
				<div class="col-sm-4" style="margin-top:3%;text-align: center;">
					<div>
						<div>
							<h4>Nombre de la autorespuesta</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> Lista de pruebas</dd>
								<dd><strong>Asunto:</strong> Mi asunto</dd>
							</dl>
						</div>
						<div style="margin: auto;display: table;">
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-pencil"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-eye-open"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-trash"></span></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="margin-top:3%;text-align: center;">
					<div>
						<div style="margin-bottom:-15px">Enviar</div>
						<div style="font-size: 70px;">20</div>
						<div style="margin-top:-15px">días despues</div></div>
				</div>
				<div class="col-sm-2" style="margin-top:7%;">
					<div><input type="checkbox" class="switch-campaign" checked></div>
				</div>
			</div>


			{# Segunda autorespuesta #}

			<div class="col-md-12 col-sm-12" style="width: 90%; margin: auto; border-top: 1px solid rgb(216, 213, 213);">
				<div class="col-sm-2">
					<div class="image-64-autorespons img-64-n2"></div>
				</div>
				<div class="col-sm-2" style="margin-top:5%;">
					<div style="font-size: 60px;text-align: center;margin-left: 45%;"><span class="glyphicon glyphicon-hand-up"></span></div>
				</div>
				<div class="col-sm-4" style="margin-top:3%;text-align: center;">
					<div>
						<div>
							<h4>Nombre de la autorespuesta numero dos</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> Lista de pruebas</dd>
								<dd><strong>Asunto:</strong> Mi asunto</dd>
							</dl>
						</div>
						<div style="margin: auto;display: table;">
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-pencil"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-eye-open"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-trash"></span></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="margin-top:3%;text-align: center;">
					<div style="font-size: 70px;">20</div>
				</div>
				<div class="col-sm-2" style="margin-top:7%;">
					<div><input type="checkbox" class="switch-campaign" checked></div>
				</div>
			</div>


			{# Tercera autorespuesta #}

			<div class="col-md-12 col-sm-12" style="width: 90%; margin: auto; border-top: 1px solid rgb(216, 213, 213);">
				<div class="col-sm-2">
					<div class="image-64-autorespons img-64-n3"></div>
				</div>
				<div class="col-sm-2" style="margin-top:5%;">
					<div style="font-size: 60px;text-align: center;margin-left: 45%;"><span class="glyphicon glyphicon-gift"></span></div>
				</div>
				<div class="col-sm-4" style="margin-top:3%;text-align: center;">
					<div>
						<div>
							<h4>Nombre de la autorespuesta numero Tres!</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> Otra lista de pruebas</dd>
								<dd><strong>Asunto:</strong> El super asunto asunto</dd>
							</dl>
						</div>
						<div style="margin: auto;display: table;">
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-pencil"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-eye-open"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-trash"></span></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="margin-top:3%;text-align: center;">
					<div style="font-size: 70px;">20</div>
				</div>
				<div class="col-sm-2" style="margin-top:7%;">
					<div><input type="checkbox" class="switch-campaign" checked></div>
				</div>
			</div>

		</div>	
	</div>

{% endblock %}