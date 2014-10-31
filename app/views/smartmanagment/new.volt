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
	{{ javascript_include('js/rules-selector/section.js')}}
	{{ javascript_include('js/rules-selector/index-rule.js')}}
	{{ javascript_include('js/rules-selector/operator-rule.js')}}
	{{ javascript_include('js/rules-selector/condition-rule.js')}}
	{{ javascript_include('js/rules-selector/points-rule.js')}}
	{{ javascript_include('js/rules-selector/buttons-rule.js')}}
	
	<script type="text/javascript">
		rulesManager = new RulesManager();
		$(function () {
			rulesManager.setContainer('#rules');
			rulesManager.setData();
			rulesManager.initialize();
			
			$(".bootstrap-switch").bootstrapSwitch({
				onColor: 'success',
				offColor: 'danger',
				size: 'mini',
				state: true
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
			try {
				var name = $('#name').val();
				rulesManager.serializeRules();
				var rules = rulesManager.getSerializerObject();
				var target = $('input[name=target]:checked').val();
				var accounts = $('#accounts').val();
				var status = $('#status').prop('checked');

				$.ajax({
					url: "{{url('smartmanagment/new')}}",
					type: "POST",			
					data: {
						name: name,
						rules: rules,
						target: target,
						accounts: accounts,
						status: status
					},
					error: function(msg){
						$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Error', text: msg.statusText, sticky: false, time: 6000});
					},
					success: function(msg){
						$(location).attr('href', "{{url('smartmanagment/content')}}/" + msg.message); 
					}
				});
			}
			catch(e) {
				console.log(e);
				$.gritter.add({class_name: 'gritter-error', title: '<i class="glyphicon glyphicon-warning-sign"></i> Error', text: '' + e, sticky: false, time: 7000});
			}
		}
	</script>
	
	<script type="text/javascript">
		{#
		var array = [];
		var obj = [
			{type: 'index-rule', value: 'spam'},
			{type: 'operator-rule', value: '>'},
			{type: 'condition-rule', value: 4},
			{type: 'points-rule', points: 'true', value: -10}
		];
		
		var obj2 = [
			{type: 'index-rule', value: 'bounced'},
			{type: 'operator-rule', value: '>'},
			{type: 'condition-rule', value: 10},
			{type: 'points-rule', points: 'false', value: 0}
		];
		
		array.push(obj);
		array.push(obj2);
		#}
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
								<input class="form-control" autofocus="autofocus" placeholder="Nombre de la gestión automática" type="text" name="name" id="name" required="required">
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
									<select class="select2" multiple name="accounts[]" id="accounts" style="width:100%">
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
								<input type="checkbox" checked class="bootstrap-switch" id="status" name="status" />
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