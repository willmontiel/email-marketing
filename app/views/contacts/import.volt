{% extends "templates/index.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}


<script type="text/javascript">
	var MyDbaseUrl = 'emarketing/api/import/{{idContactlist}}';

	var myImportModel = {
		email: DS.attr( 'string' ),
		name: DS.attr( 'string' ),
		lastname: DS.attr( 'string' )
		{%for field in customfields%}
			,
			{{field.name|lower }}: DS.attr('string')
		{%endfor%}
	};
</script>

{{ javascript_include('js/app_import.js') }}

<script>
	{%for item in row%}
		App.{{item}}_options = [
		{%for field in customfields %}	
				"{{field.name}}",
		{%endfor%}
		];
	{%endfor%}
</script>

{% endblock %}

{% block content %}
<div id="emberAppImportContainer">
	<script type="text/x-handlebars">
		<div class="row-fluid">
			<div class="span5">
				<table class="contact-info">
					<tbody>
						{%for item in row%}
						<tr>		
							<td>{{item}}</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.'~item~'_options" valueBinding="'~item~'" }}'}}
							</td>
						</tr>
						{%endfor%}
					</tbody>
				</table>
				<div class="span3">
					Delimitador:
					<select>
						<option value="coma" selected>,</option>
						<option value="puntocoma">;</option>
						<option value="slash"></option>
					</select>
				</div>
			</div>
		</div>
	</script>
</div>
{% endblock %}
