<div class="row-fluid">
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
				value=currentFilter.value
				class="span6"
			}}'}}
		</div>
	</div>
</div>