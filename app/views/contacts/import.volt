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
	App.email_options = [
			{%for item in row%}
				,	
				"{{item}}"
			{%endfor%}
		];
	App.name_options = [
			{%for item in row%}
				,	
				"{{item}}"
			{%endfor%}
		];
	App.lastname_options = [
			{%for item in row%}
				,	
				"{{item}}"
			{%endfor%}
		];	
	{%for field in customfields %}	
		App.{{field.name|lower}}_options = [
			{%for item in row%}
				,	
				"{{item}}"
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
						<tr>		
							<td>Email</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.email_options" valueBinding="email" id="email"}}'}}
							</td>
						</tr>
						<tr>		
							<td>Nombre</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.name_options" valueBinding="name" id="name"}}'}}
							</td>
						</tr>
						<tr>		
							<td>Apellido</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.lastname_options" valueBinding="lastname" id="lastname"}}'}}
							</td>
						</tr>
						{%for field in customfields %}
						<tr>		
							<td>{{field.name}}</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.'~field.name|lower~'_options" valueBinding="'~field.name|lower~'" id="'~field.name|lower~'"}}'}}
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
						<option value="slash">/</option>
					</select>
				</div>
			</div>
			<div class="span5">
				<p>Email: {{' {{email}} '}}</p>
				<p>Nombre: {{' {{name}} '}}</p>
				<p>Apellido: {{' {{lastname}} '}}</p>
				{%for field in customfields %}
						<p>{{field.name}}
							<td>
								{{'{{ '~field.name|lower~'}}'}}
							</p>
						{%endfor%}
			</div>
		</div>
	</script>
</div>
{% endblock %}
