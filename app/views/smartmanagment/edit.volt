{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}

	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}
	
	{# Select2 master#}
	{{ stylesheet_link('vendors/select2-master/select2.css') }}
	{{ javascript_include('vendors/select2-master/select2.js')}}

	{# Select2 master#}
	{{ stylesheet_link('js/rules-selector/css/rules-selector.css') }}
	{{ javascript_include('js/rules-selector/rules-manager.js') }}
	{{ javascript_include('js/rules-selector/rule.js')}}
	{{ javascript_include('js/rules-selector/index-rule.js')}}
	{{ javascript_include('js/rules-selector/operator-rule.js')}}
	{{ javascript_include('js/rules-selector/condition-rule.js')}}
	{{ javascript_include('js/rules-selector/points-rule.js')}}
	{{ javascript_include('js/rules-selector/buttons-rule.js')}}
	
	<script type="text/javascript">
		rulesManager = new RulesManager();
		$(function () {
			var obj = [
				{% for rule in rules %}
					{{rule}},
				{% endfor %}
			];
			
			rulesManager.setContainer('#rules');
			rulesManager.setData(obj);
			rulesManager.initialize();
			
			$(".bootstrap-switch").bootstrapSwitch({
				onColor: 'success',
				offColor: 'danger',
				size: 'mini',
				state: {% if smart.status == 1%}true{% else %}false{% endif %}
			});
			
			$(".select2").select2({
				allowClear: true,
				placeholder: "Seleccione la(s) cuenta(s)"
			});
			
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
		
		function saveManagment() {
			var name = $('#name').val();
			rulesManager.serializeRules();
			var rules = rulesManager.getSerializerObject();
			var target = $('input[name=target]:checked').val();
			var accounts = $('#accounts').val();
			var status = $('#status').prop('checked');
			
			$.ajax({
				url: "{{url('smartmanagment/edit')}}/{{smart.idSmartmanagment}}",
				type: "POST",			
				data: {
					name: name,
					rules: rules,
					target: target,
					accounts: accounts,
					status: status
				},
				error: function(msg){
					$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Error', text: msg.statusText, sticky: false, time: 7000});
				},
				success: function(msg){
					$(location).attr('href', "{{url('smartmanagment')}}"); 
				}
			});
		}
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
				<div class="form-horizontal">
					<div class="panel-body" style="margin-top: 20px;">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Nombre de la gestión automática
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<input class="form-control" autofocus="autofocus" placeholder="Nombre de la gestión automática" type="text" name="name" id="name" value="{{smart.name}}" required="required">
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
										<input type="radio" id="all-accounts" name="target" value="all-accounts" {% if accountsSelected.type == 'all-accounts' %}checked="checked"{% endif %}>
										<label for="all-accounts">Todas las cuentas</label>
									</div>
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
										<input type="radio" id="certain-accounts" name="target" value="certain-accounts" {% if accountsSelected.type == 'certain-accounts' %}checked="checked"{% endif %}>
										<label for="certain-accounts">Determinadas cuentas:</label>
									</div>
								</div>
								
								<div class="space"></div>
									
								<div id="accounts-list" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0; display: {% if accountsSelected.type == 'certain-accounts' %}block{% else %}none{% endif %};">
									<select class="select2" multiple name="accounts[]" id="accounts" style="width:100%">
										{% for account in accounts%}
											<option value="{{account.idAccount}}" {% if accountsSelected.target is not null %}{% for t in accountsSelected.target %} {% if account.idAccount == t%} selected="selected" {% endif %} {% endfor %} {% endif %}>
												{{account.companyName}}
											</option>
										{% endfor %}
									</select>
								</div>
							</div>
						</div>
							
						<div class="space"></div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Contenido
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<a href="{{url('smartmanagment/content')}}/{{smart.idSmartmanagment}}">Editar contenido</a>
							</div>
						</div>	
							
						<div class="space"></div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 control-label">
								*Estado
							</label>
							<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
								<input type="checkbox" {% if smart.status == 1%}checked{% endif %} class="bootstrap-switch" id="status" name="status" />
							</div>
						</div>
					</div>
					
					
					<div class="panel-footer">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
								<a href="{{url('smartmanagment')}}" class="btn btn-sm btn-default btn-sm">Cancelar</a>
								<button class="btn btn-sm btn-guardar" onClick="saveManagment();">Guardar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}