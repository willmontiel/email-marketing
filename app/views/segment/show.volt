{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ partial("partials/date_view_partial") }}
		{{ partial("partials/xeditable_view_partial") }}
		{{ partial("partials/xeditable_select_view_partial") }}
		{{ javascript_include('js/search-reference-pagination.js') }}
		{{ javascript_include('js/mixin_config.js') }}
		{{ javascript_include('js/load_activecontacts.js')}}
<script type="text/javascript">
		var MySegmentUrl = '{{urlManager.getApi_v1Url() ~ '/segment/' ~ datasegment.idSegment}}';

		{# Modelo del contacto se movio a un unico partial #}
		{{ partial('partials/contact_model_definition', ['include_list' : false]) }}

	</script>
	{{ javascript_include('js/app_segment_show.js') }}
	{{ javascript_include('js/app_contact.js') }}
	<script type="text/javascript">
		App.contactACL = {
			canCreate: {{acl_Ember('api::createcontactbylist')}},
			canImportBatch: {{acl_Ember('contacts::importbatch')}},
			canImport: {{acl_Ember('contacts::import')}},
			canUpdate: {{acl_Ember('api::updatecontactbylist')}},
			canDelete: {{acl_Ember('api::deletecontactbylist')}}
		};
	</script>
	<script>
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
			{{ ember_customfield_options_xeditable(field) }}
		{%endfor%}
	</script>
	{{ javascript_include('js/editable-ember-view.js')}}
{% endblock %}
{% block content %}
<div id="emberAppContactContainer">
	<script type="text/x-handlebars" data-template-name="contacts">
		{{'{{outlet}}'}}
	</script>
	<script type="text/x-handlebars" data-template-name="contacts/index">
	
		{# Botones de navegacion pequeños #}
		{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'segments']) }}
		{# /Botones de navegacion pequeños  #}

		<div class="row">
			<h4 class="sectiontitle">Contactos del segmento {{datasegment.name}} <small>({{'{{totalrecords}}'}})</small></h4>
				{{ partial("partials/search_contacts_partial") }}

				<div class="clearfix"></div>

				{{ partial("partials/select_contacts_search_partial") }}

			<table class="table table-striped table-contacts">
				<thead></thead>
				<tbody>
					{{'{{#each model}}'}}
						{{ partial("partials/contact_view_partial") }}
					{{ '{{else}}' }}
						<tr>
							<td>
								<div class="bs-callout bs-callout-warning">
									<h4>No se encontraron contactos</h4>
									<p>Puede ser que su búsqueda no arrojó resultados, que no existen contactos que cumplan todas las condiciones de filtro o que no hay contactos en este segmento.</p>
								</div>
							</td>
						</tr>
					{{ '{{/each}}' }}
				</tbody>
				<tfoot></tfoot>
			</table>
			<div class="row"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</script>

{% endblock%}