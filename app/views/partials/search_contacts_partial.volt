<form>
	{{' {{view Ember.TextField valueBinding="searchCriteria" class="span8" onEvent="enter" action="search" type="text" placeholder="Buscar por dirección de correo, nombre, apellido, dominio..." autofocus="autofocus"}} '}}
	<button class="btn btn-default" {{ '{{action "search" this}}' }}><i class="icon-search"></i></button>
	<button class="btn btn-blue" {{ '{{action "reset" this}}' }}><i class="icon-exchange"></i></button>
</form>