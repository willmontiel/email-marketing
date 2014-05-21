<form class="form-horizontal" role="form">
	<div class="form-group">
		<label for="fromName" class="col-sm-3 control-label">Nombre del correo: </label>
		<div class="col-sm-9">
			{{'{{view Ember.TextField valueBinding="name" id="name" required="required" class="form-control" placeholder="Nombre para identificar este correo "}}'}}
		</div>
	</div>
</form>