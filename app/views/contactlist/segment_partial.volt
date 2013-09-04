<script type="text/x-handlebars" data-template-name="segments/index">
	<div class="action-nav-normal pull-right" style="margin-bottom: 5px;">
		{{'{{#linkTo "segments.new" class="btn btn-default"}}'}}<i class="icon-plus"></i> Crear nueva segmento{{'{{/linkTo}}'}}
	</div>
	<div class="clearfix"></div>
	<div class="box">
		<div class="box-header">
			<div class="title">
				Segmentos
			</div>
		</div>
		<div class="box-content">
			<table class="table table-normal">
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="box-footer flat"> 
			{{ partial("partials/pagination_partial") }}
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="segments">
	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="segments/new">
	<div class="box">
		<div class="box-header">
			<div class="title">Agregar un nuevo segmento</div>
		</div>
		<div class="box-content padded">
			<form>
				Crear segmento con 
					<select name="criteria" class="span2">
						<option value="all">todas</option>
						<option value="any">cualquiera</option>
					</select>
				de las siguientes condiciones a continuación
				<div class="row-fluid">
					<div class="span3">
						<select>
							<option>Email</option>
							<option>Nombre</option>
						</select>
					</div>
					<div class="span3">
						<select>
							<option>Es</option>
							<option>Contiene</option>
							<option>No contiene</option>
							<option>Empieza con</option>
							<option>Termina en</option>
						</select>
					</div>
					<div class="span3">
						<select>
							<option>Email</option>
							<option>Nombre</option>
						</select>
					</div>
					<div class="span2">
						<button class="btn btn-default">+</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</script>
