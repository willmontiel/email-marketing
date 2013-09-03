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


{% endblock %}

{% block content %}
<div id="emberAppImportContainer">
	<script type="text/x-handlebars" data-template-name="contacts/index">
		<div class="row-fluid">
			<div class="span5">
			<p {{'{{action partir target="controller"}}'}}> Aqui </p>
			{{' {{#with App.firstline}} '}}
				<table class="contact-info">
					<tbody>
						<tr>		
							<td>Email</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.firstline" valueBinding="email" id="email" class="select"}}'}}
							</td>
						</tr>
						<tr>		
							<td>Nombre</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.firstline" valueBinding="name" id="name"}}'}}
							</td>
						</tr>
						<tr>		
							<td>Apellido</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.firstline" valueBinding="lastname" id="lastname"}}'}}
							</td>
						</tr>
						{%for field in customfields %}
						<tr>		
							<td>{{field.name}}</td>
							<td>
								{{'{{ view Ember.Select contentBinding="App.firstline" valueBinding="'~field.name|lower~'" id="'~field.name|lower~'"}}'}}
							</td>
						</tr>
						{%endfor%}
					</tbody>
				</table>
				{{' {{/with}} '}}
				<div class="span3">
					Delimitador:
					{{' {{view App.delimiterView valueBinding="delimiter" contentBinding="content"}} '}}

				</div>
			</div>
			<div class="span5">
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
