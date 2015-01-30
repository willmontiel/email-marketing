{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# Swicth master#}
	{{ javascript_include('vendors/bootstrap-switch-master/bootstrap-switch.js')}}
	{{ stylesheet_link('vendors/bootstrap-switch-master/bootstrap-switch.css') }}

	<script type="text/javascript">
		$(function () {
			$(".switch").bootstrapSwitch({
				size: 'mini',
				onColor: 'success',
				offColor: 'danger',
			});
			
			$('.switch').on('switchChange.bootstrapSwitch', function(event, state) {
				var idAccount = $(this).data("id");
				//var status = ( state ? 1 : 0); // true | false
				
				$.ajax({
					url: "{{url('account/changestatus')}}/" + idAccount,
					type: "POST",			
					data: {},
					error: function(msg){
						$.gritter.add({class_name: 'error', title: '<span class="glyphicon glyphicon-warning-sign"></span> Error', text: msg.error, time: 30000});
					},
					success: function(msg){
						$.gritter.add({class_name: 'success', title: '<span class="glyphicon glyphicon-ok-sign"></span> Exitoso', text: msg.success, time: 30000});
					}
				});
			});
		});
	</script>	
{% endblock%}

{% block content %}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}

	<div class="row">
		<h4 class="sectiontitle">Cuentas</h4>
		<div class="bs-callout bs-callout-info">
			Aquí puede ver, crear o editar las cuentas de la apliación, como también administrar los usuarios
			de dichas cuentas.
		</div>
	</div>

	<div class="row">
		{{ flashSession.output() }}
	</div>
	<div class="row">
		<div class="text-right">
			<a href="{{ url('account/new') }}" class="btn btn-default btn-sm extra-padding"><span class="glyphicon glyphicon-plus"></span> Crear nueva cuenta</a>
			<a href="{{ url('account/accounting') }}" class="btn btn-default btn-sm extra-padding"><span class="glyphicon glyphicon-usd"></span> Contabilidad</a>
		</div>
	</div>
	<div class="row">
		{#   Paginacion sin ember   #}
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'account/index']) }}
			
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Espacio(Mb)</th>
					<th>Contactos</th>
					<th>Mensajes</th>
					<th>MTA</th>
					<th>Registrada</th>
					<th>Actualizada</th>
					<th class="orange-sigma star-shadow" style="font-size: 0.9em !important;">
						<span class="glyphicon glyphicon-star"></span> Puntuación
					</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		 {%for item in page.items%}
				<tr>
					<td>
						<h5><strong><a href="{{ url('account/show/') }}{{item.idAccount}}">{{item.idAccount}} - {{item.companyName}}</a></strong></h5>
						{{item.accountingMode}}<br/>
						{{item.subscriptionMode}}
					</td>
					<td>{{item.fileSpace}}</td>
					<td>{{item.contactLimit}}</td>
					<td>{{item.messageLimit}}</td>
					<td>{{item.virtualMta}}</td>
					<td>{{date('d/M/Y', item.createdon)}}</td>
					<td>{{date('d/M/Y', item.updatedon)}}</td>
					<td class="text-right">
						{% if scores[item.idAccount] is defined %}
							{{scores[item.idAccount]}}
						{% else %}
							0
						{% endif %}
						<br />
						<a href="{{url('account/scorehistory')}}/{{item.idAccount}}">Ver historial</a>
					</td>
					<td class="text-right">
						<input type="checkbox" data-id="{{item.idAccount}}" class="switch" {% if item.status == 1%} checked {% endif %}>
						<a href="{{ url('account/edit') }}/{{item.idAccount}}" class="btn btn-sm btn-default" ><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="{{ url('account/show') }}/{{item.idAccount}}" class="btn btn-sm btn-default" ><span class="glyphicon glyphicon-zoom-in"></span></a>
					</td>
				</tr>
		 {%endfor%}
			</tbody>
		</table>

		{#   Paginacion sin ember   #}
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'account/index']) }}
	</div>
{% endblock %}