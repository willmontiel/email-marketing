{% extends "templates/index.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}


<script type="text/javascript">
	var MyDbaseUrl = '{{apiurlbase.url ~ '/import/' ~ idContactlist}}';

	var myImportModel = {
		datas: DS.attr( 'string' ),
		email: DS.attr( 'string' ),	
		name: DS.attr( 'string' ),
		lastname: DS.attr( 'string' ),
		delimiter: DS.attr( 'string' )
		{%for field in customfields%}
			,
			{{field.name|lower }}: DS.attr('string')
		{%endfor%}
	};
</script>

{{ javascript_include('js/app_import.js') }}
<script type="text/javascript">
	
	App.originalF = "{{row[0]}}";
	App.originalS = "{{row[1]}}";
	App.originalT = "{{row[2]}}";
	App.originalFo = "{{row[3]}}";
	App.originalFi = "{{row[3]}}";
	App.optionsOr = " ,{{row[0]}}"
	
	App.options = App.optionsOr.split(",");
	
	App.firstline = App.originalF.split(",");
	
	App.secondline = App.originalS.split(",");
	
	App.thirdline = App.originalT.split(",");
	
	App.fourthline = App.originalFo.split(",");
	
	App.fifthline = App.originalFi.split(",");
</script>


{% endblock %}

{% block content %}
<div id="emberAppImportContainer">
	<script type="text/x-handlebars" data-template-name="contacts/index">
		<div class="row-fluid">
			<div class="span5">
				<table class="contact-info">
					<tbody>
						<tr>		
							<td>Email</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.options" valueBinding="email" id="email" class="select"}}'}}
							</td>
						</tr>
						<tr>		
							<td>Nombre</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.options" valueBinding="name" id="name"}}'}}
							</td>
						</tr>
						<tr>		
							<td>Apellido</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.options" valueBinding="lastname" id="lastname"}}'}}
							</td>
						</tr>
						{%for field in customfields %}
						<tr>		
							<td>{{field.name}}</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.options" valueBinding="'~field.name|lower~'" id="'~field.name|lower~'"}}'}}
							</td>
						</tr>
						{%endfor%}
					</tbody>
				</table>
				<div class="span3">
					Delimitador:
					{{' {{view App.delimiterView valueBinding="delimiter" contentBinding="content"}} '}}
				</div>
			</div>
			<div class="span5">
				<p>Email: {{'{{email}}'}}</p>
				<p>Nombre: {{'{{name}}'}}</p>
				<p>Apellido: {{'{{lastname}}'}}</p>
				{%for field in customfields%}
				<p>{{field.name}}: {{'{{'~field.name|lower~'}}'}}</p>
				{%endfor%}
				
			</div>
		</div>
		<div class="row-fluid">
			<div class="span8">
				<table class="table table-striped">
					<tr>
						{{' {{#each App.firstline}} '}}
							<td>{{' {{this}} '}}</td>
						{{' {{/each}} '}}
					</tr>
					<tr>
						{{' {{#each App.secondline}} '}}
							<td>{{' {{this}} '}}</td>
						{{' {{/each}} '}}
					</tr>
					<tr>
						{{' {{#each App.thirdline}} '}}
							<td>{{' {{this}} '}}</td>
						{{' {{/each}} '}}
					</tr>
					<tr>
						{{' {{#each App.fourthline}} '}}
							<td>{{' {{this}} '}}</td>
						{{' {{/each}} '}}
					</tr>
					<tr>
						{{' {{#each App.fifthline}} '}}
							<td>{{' {{this}} '}}</td>
						{{' {{/each}} '}}
					</tr>
				</table>
			</div>
		</div>
	</script>
	
	<script type="text/x-handlebars" data-template-name="select">
		{{' {{view App.DelimiterView name="delimiter" contentBinding="App.delimiter_opt"}} '}}
	</script>
	
	<script type="text/x-handlebars" data-template-name="contacts">
		{{' {{outlet}} '}}
	</script>

</div>
{% endblock %}
