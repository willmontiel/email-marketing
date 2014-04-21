{# formulario para busqueda #}
<div class="row">
	<div class="col-md-5 col-sm-12 form-search">
		<form role="form">
			<div class="form-group">
				<div class="input-group">
					{{' {{view Ember.TextField valueBinding="searchCriteria" onEvent="enter" action="search" type="text" autofocus="autofocus" class="form-control" id="search" placeholder="Correo, @dominio, nombres, apellidos, combinaciones"}}'}}
					<span class="input-group-addon"><i class="glyphicon glyphicon-search" {{ '{{action "search" this}}' }}></i></span>
				</div>
			</div>
		</form>
	</div>
</div>

{#  ############ search viejo + select mostrar viejo

<div class="row">
	<div class="span8">
		<form>
			{{' {{view Ember.TextField valueBinding="searchCriteria" class="span8" onEvent="enter" action="search" type="text" placeholder="Buscar por dirección de correo, nombre, apellido, dominio..." autofocus="autofocus"}} '}}
			<button class="btn btn-default" {{ '{{action "search" this}}' }}><i class="icon-search"></i></button>
			<button class="btn btn-blue" {{ '{{action "reset" this}}' }}><i class="icon-exchange"></i></button>
		</form>
	</div>
	<div class="span4">
		<div class="text-right">
			Mostrar: 
			{{'{{
				view Ember.Select
				content=filters
				optionValuePath="content.value"
				optionLabelPath="content.name"
				value=filter.value
				class="span6"
			}}'}}
		</div>
	</div>
</div>
########### #}