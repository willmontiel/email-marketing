{# formulario para busqueda #}
<div class="col-lg-5 col-md-12 col-sm-12 form-search">
	<form class="form-horizontal" role="form">
		<div class="form-group">
			<div class="input-group input-group-sm">
				{{' {{view Ember.TextField valueBinding="searchCriteria" onEvent="enter" action="search" type="text" autofocus="autofocus" class="form-control" id="search" placeholder="Correo, @dominio, nombres, apellidos, combinaciones"}}'}}
				<span class="input-group-addon pointer"><span class="glyphicon glyphicon-search" {{ '{{action "search" this}}' }}></span></span>
				<span class="input-group-btn">
					<button class="btn btn-default extra-padding border" type="button" {{ '{{action "reset" this}}' }}>Limpiar</button>
				</span>
			</div>
		</div>
	</form>
</div>
