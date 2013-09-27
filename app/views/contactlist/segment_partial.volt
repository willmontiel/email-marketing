<script type="text/x-handlebars" data-template-name="segments/index">
	<div class="action-nav-normal pull-right" style="margin-bottom: 5px;">
		{{'{{#linkTo "segments.new" class="btn btn-default"}}'}}<i class="icon-plus"></i> Crear nueva segmento{{'{{/linkTo}}'}}
	</div>
	<div class="clearfix"></div>
	<div class="row-fluid">
        <div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Segmentos
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
							   <td colspan="2">Segmento</td>
							   <td>Acciones</td>
						   </tr>
					   </thead>
						<tbody>
						{{'{{#each model}}'}}
							<tr>
								<td>{{' {{name}}'}}</td>
								<td>{{' {{description}}'}}</td>
								<td>
									<div class="pull-right" style="margin-right: 10px;">
										<div class="btn-group">
											<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li>{{ '{{#linkTo "segments.delete" this}}<i class="icon-trash"></i> Eliminar{{/linkTo}}' }}</li>
												<li>{{ '{{#linkTo "segments.edit" this}}<i class="icon-pencil"></i> Editar{{/linkTo}}' }}</li>
											</ul>
										</div>
									</div>
								</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
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
				<label>*Nombre: </label>
					{{ '{{view Ember.TextField valueBinding="name"}}' }}
				<label>Descripción: </label>
					{{ '{{view Ember.TextArea valueBinding="description"}}' }}
				<label>*Seleccione base de datos:</label>
					{{ '{{view Ember.Select 
								selectionBinding="dbase" 
								contentBinding="controllers.dbase.content" 
								optionValuePath="content.id" 
								optionLabelPath="content.name" 
								prompt="Seleccione una base de datos"
								class="span3"}}' 
					}}
				<label>	Crear segmento con </label>
					{{ '{{view Ember.Select
						  contentBinding="App.criteria"
						  optionValuePath="content.id"
						  optionLabelPath="content.criterion"
						  valueBinding="criterion"
						  class="span2"}}'
					}}
					condiciones a continuación:
				
				<br /><br />
				<div class="row-fluid">	
					<div class="span12">
						{{ '{{#unless limitCriteria}}' }}
						<button class="btn btn-default" {{ '{{action aConditionMore}}' }}>+</button>
						{{ '{{/unless}}' }}
						{{' {{#each cr in criteria}} '}}
						<div class="row-fluid">
							<div class="span3">
								{{ '{{view Ember.Select
									  contentBinding="cfields"
									  optionValuePath="content.id"
									  optionLabelPath="content.name"
									  valueBinding="cr.cfields"
									  prompt="Seleccione un campo"
									}}'
								}}
							</div>
							<div class="span3">
								{{ '{{view Ember.Select
									contentBinding="App.relations"
									optionValuePath="content.id"
									optionLabelPath="content.relation"
									valueBinding="cr.relations"}}'
								}}
							</div>
							<div class="span3">
								{{ '{{view Ember.TextField valueBinding="cr.value" placeholder="valor" required="required" autofocus="autofocus"}}' }}
							</div>
							<button class="btn btn-default" {{ '{{action aConditionLess cr}}' }}>-</button>
						</div>
						{{' {{/each}} '}}
					</div>
				</div>
				
				<button class="btn btn-default" {{ '{{action save this }}' }}>Crear</button>
			</form>
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="segments/delete">
	<div class="box">
		<div class="box-header">
			<div class="title">
				Borrar segmento
			</div>
		</div>
		<div class="box-content">
			<div class="padded">
				<p>
					Esta a punto de borrar un segmento, si lo hace borrará el filtro rápido para separar los contactos,
					pero no los contactos .Si esta seguro que desea continuar haga clic en botón eliminar de lo contrario
					haga en clic en cancelar.
				</p>
				<button class="btn btn-red" {{ '{{action delete this}}' }}>Eliminar</button>
				<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="segments/edit">
	{{'{{name}}'}}
	{{'{{description}}'}}
</script>