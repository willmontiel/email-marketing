{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}

	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}
	
	{# Select2 master#}
	{{ stylesheet_link('vendors/select2-master/select2.css') }}
	{{ javascript_include('vendors/select2-master/select2.js')}}
	<script type="text/javascript">
		$(function () {
			$(".bootstrap-switch").bootstrapSwitch({
				onColor: 'success',
				offColor: 'danger',
				size: 'mini'
			});
			
			$(".select2").select2({
				allowClear: true,
				placeholder: "Seleccione la(s) cuenta(s)"
			});
			{#
			if ($('#all').prop('checked')) {
				$("#selectAccount").hide();
			}
			
			if ($('#any').prop('checked')) {
				$("#selectAccount").show();
			}
			#}
			
			$("input[name=target]").change(function () {	 
				var value = $(this).val();
				
				if (value === 'all-accounts') {
					$('#accounts').val("");
					$("#accounts-list").hide();
				}
				else {
					$('#accounts').val("");
					$("#accounts-list").show();
				}
			});
		});
	</script>
	
	{# Select2 master#}
	{{ stylesheet_link('js/rules-selector/css/rules-selector.css') }}
	{{ javascript_include('js/rules-selector/rules-container.js') }}
	{{ javascript_include('js/rules-selector/rule.js')}}
	
	<script type="text/javascript">
		$(function () {
			var rulesContainer = new RulesContainer('#rules');		
			rulesContainer.initialize(null);
		});
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'smartmanagent']) }}
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="title">Nuevo mensaje de gestión inteligente</div>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel" style="box-shadow: 2px 2px 5px 0px #afafaf;">
				<form class="form-horizontal" action="" method="post" role="form">

					<div class="panel-body" style="margin-top: 20px;">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Nombre de la gestión automática
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<input class="form-control" autofocus="autofocus" placeholder="Nombre de la gestión automática" type="text" name="name" required="required">
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Reglas
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<div id="rules"></div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Validar en
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0;">
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="padding-left: 0;">
										<input type="radio" id="all-accounts" name="target" value="all-accounts">
										<label for="all-accounts">Todas las cuentas</label>
									</div>
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
										<input type="radio" id="certain-accounts" name="target" value="certain-accounts">
										<label for="certain-accounts">Determinadas cuentas:</label>
									</div>
								</div>
								
								<div class="space"></div>
									
								<div id="accounts-list" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0; display: none;">
									<select class="select2"  multiple name="accounts[]" id="accounts" style="width:100%">
										{% for account in accounts%}
											<option value="{{account.idAccount}}">{{account.companyName}}</option>
										{% endfor %}
									</select>
								</div>
							</div>
						</div>

						<div class="space"></div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Estado
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<input type="checkbox" class="bootstrap-switch" id="bootstrap-switch" name="status" />
							</div>
						</div>
					</div>
					
					
					<div class="panel-footer">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
								<a href="{{url('smartmanagment')}}" class="btn btn-sm btn-default btn-sm">Cancelar</a>
								<input class="btn btn-sm btn-guardar" value="Guardar" type="submit">	
							</div>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
{% endblock %}