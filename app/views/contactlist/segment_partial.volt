<!-- SEGMENT PARTIAL -->

<script type="text/x-handlebars" data-template-name="segments/index">

	{# Insertar botones de navegacion #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'segments']) }}

	<div class="row">
		<h4 class="sectiontitle">Segmentos</h4>
		<div class="row">
			<div class="col-xs-6 col-sm-5 col-md-4">
				<form role="form">
					{{ '{{view Ember.Select
							contentBinding="dbaseSelect"
							optionValuePath="content.id"
							optionLabelPath="content.name"
							valueBinding="selectedDbase"
							class="form-control"
							prompt="Seleccione una base de datos"
							placeholder="Todas las bases de datos"
						}}'
					}}
				</form>
			</div>
			<div class="col-md-3 pull-right">
				{{'{{#link-to "segments.new" class="btn btn-default btn-sm extra-padding"}}'}} Crear nuevo segmento{{'{{/link-to}}'}}
			</div>
		</div>
		<div class="space"></div>
	</div>
	<table class="table table-striped table-contacts">
		<thead>
		</thead>
		<tbody>
			{{'{{#each model}}'}}

			<tr>
				<td {{' {{bind-attr style="dbase.style"}} '}}>

				</td>
				<td>
					<a href="{{url('segment/show/')}}{{ '{{unbound id}}' }}#/contacts">{{' {{name}} '}}</a>
					<p>{{' {{description}}'}}</p>
				</td>

				<td>
					{{ '{{#link-to "segments.delete" this class="btn btn-default btn-delete btn-sm extra-padding"}} <span class="glyphicon glyphicon-trash"></span> Eliminar{{/link-to}}' }}

					{{ '{{#link-to "segments.edit" this class="btn btn-default btn-sm extra-padding"}} <span class="glyphicon glyphicon-pencil"></span> Editar{{/link-to}}' }}

				</td>
			</tr>
			{{ '{{else}}' }}
			<tr>
				<td>
					<div class="">
						<h4>Aun no tienes segmentos para tus contactos</h4>
						<p>Lorem Ipsum
						</p>
					</div>
				</td>
			</tr>
			{{ '{{/each}}' }}				

		</tbody>
		<tfoot>
		</tfoot>
	</table>

	{{' {{# unless isNew }}' }}
	{{' {{/unless}}'}}
	
	{# ########## inserta paginacion ########## #}
	<div class="row">
	{{ partial("partials/pagination_partial") }}
	</div>

</script>

<script type="text/x-handlebars" data-template-name="segments">
	{{'{{outlet}}'}}

</script>

<script type="text/x-handlebars" data-template-name="segments/new">
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo segmento</h4>
		{{ '{{#if App.errormessage }}' }}
		<div class="row">
			<div class="alert alert-error">
				{{ '{{ App.errormessage }}' }}
			</div>
		</div>
		{{ '{{/if}} '}}
		{{'{{#if errors.segmentname}}'}}
		<div class="row">
			<div class="alert alert-error">
				{{'{{errors.segmentname}}'}}
			</div>
		</div>
		{{'{{/if}}'}}
		<div class="col-sm-12 hidden-md hidden-lg">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Cree un nuevo segmento en una base de datos.</p>
						<p>Seleccione las condiciones que desee para el segmento.</p>
					</div>
				</div>
			</div>
		</div>
		<form  class="form-horizontal" role="form">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label for="name" class="col-sm-4 control-label">*Nombre: </label>
						<div class="col-md-7">
							{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre" class="form-control"}}' }}
						</div>
					</div>
					<div class="form-group">
						<label for="description" class="col-sm-4 control-label">Descripción: </label>
						<div class="col-md-7">
							{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción" class="form-control sm-textarea-description"}}' }}
						</div>
					</div>
					{{ '{{#unless dbaseSelected}}' }}
					<div class="form-group">
						<label  for="dbase" class="col-sm-4 control-label">*Base de datos:</label>
						<div class="col-md-6">
							{{ '{{view Ember.Select
									selectionBinding="dbase" 
									contentBinding="controllers.dbase.content"
									placeholder="Seleccione una base de datos"
									optionValuePath="content.id" 
									optionLabelPath="content.name" 
									prompt="Seleccione una base de datos"
									class="form-control"
								}}' 
							}}
						</div>
					</div>
					{{ '{{else}}' }}
					<div class="form-group">
						<label  for="dbase" class="col-sm-4 control-label">*Base de datos:</label>
						<div class="col-md-6">
							<select class="form-control" disabled="disabled">
								<option>{{' {{dbase.name}} '}}</option>
							</select>
						</div>
						<button class="btn btn-default" {{ '{{action reset this }}' }}><i class="icon-bolt"></i> Reset</button>
					</div>
					{{ '{{/unless}}' }}
				</div>
				<div class="hidden-xs hidden-sm col-md-5">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Cree un nuevo segmento en una base de datos.</p>
								<p>Seleccione las condiciones que desee para el segmento.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{ '{{#if dbaseSelected}}' }}

			<div class="clearfix"></div>
			<div class="row">
				<div class="col-sm-12 col-md-7">
					<div class="form-group">
						<label  for="" class="col-sm-4 control-label">Crear segmento con </label>
						<div class="col-sm-4">
							{{ '{{view Ember.Select
								  contentBinding="App.criteria"
								  optionValuePath="content.id"
								  optionLabelPath="content.criterion"
								  valueBinding="criterion"
								  class="form-control"}}'
							}}
						</div>
						<p class="form-control-static">condiciones a continuación:</p>
					</div>
				</div>
				<div class="clearfix"></div>
				{{'{{#if errors.segment}}'}}
				<div class="alert alert-error">
					{{'{{errors.segment}}'}}
				</div>
				{{'{{/if}}'}}

				{{' {{#each cr in criteria}} '}}
				<div class="col-lg-12">
					<div class="form-group">
						<label  for="" class="col-xs-1 control-label"></label>
						<div class="col-sm-3">
							{{ '{{view Ember.Select
								 contentBinding="cfields"
								 optionValuePath="content.id"
								 optionLabelPath="content.name"
								 valueBinding="cr.cfields"
								 prompt="Seleccione un campo"
								 class="form-control"
								}}'
							}}
						</div>
						<div class="col-xs-2">
							{{ '{{view Ember.Select
								contentBinding="App.relations"
								optionValuePath="content.id"
								optionLabelPath="content.relation"
								valueBinding="cr.relations"
								class="form-control"
								}}'
							}}
						</div>
						<div class="col-sm-3">
							{{ '{{view Ember.TextField valueBinding="cr.value" placeholder="valor" required="required" autofocus="autofocus" class="form-control"}}' }}
						</div>
						<div class="col-md-3">
							<div class="row">
								{{ '{{#unless defaultCriteria}}' }}
									<button class="btn btn-default btn-sm" {{ '{{action aConditionLess cr}}' }}><i class="glyphicon glyphicon-trash"></i></button>
							
								{{ '{{#unless limitCriteria}}' }}
								<button class="btn btn-sm btn-default btn-add extra-padding" {{ '{{action aConditionMore}}' }}><i class="icon-plus"></i> Agregar condición</button>
								{{ '{{/unless}}' }}
									{{ '{{/unless}}' }}
							</div>
						</div>
					</div>
				</div>
				{{' {{/each}} '}}
				<div class="clearfix"></div>
				<div class="form-actions pull-right">
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-sm btn-default" {{ '{{action cancel this }}' }}>Cancelar</button>
						</div>
						<div class="col-xs-6">
							<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action save this }}' }}>Grabar</button>
							{{ '{{/if}}' }}
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</script>


<script type="text/x-handlebars" data-template-name="segments/delete">
	<div class"row">
		<h4 class="sectiontitle">Eliminar un segmento</h4>
		<div class="bs-callout bs-callout-danger">
			<p>Está a punto de borrar un segmento, si lo hace borrará el filtro rápido para separar los contactos, pero no los contactos .Si está seguro que desea continuar haga clic en el botón eliminar, de lo contrario haga en clic en cancelar.</p>
		</div>
		
		<button class="btn btn-default btn-sm" {{ '{{action cancel this}}' }}>Cancelar</button>
		<button class="btn btn-delete btn-sm" {{ '{{action delete this}}' }}>Eliminar</button>
	</div>
</script>


<script type="text/x-handlebars" data-template-name="segments/edit">
	<div class="row">
		<h4 class="sectiontitle">Editar un segmento</h4>
		{{ '{{#if App.errormessage }}' }}
		<div class="row">
			<div class="alert alert-error">
				{{ '{{ App.errormessage }}' }}
			</div>
		</div>
		{{ '{{/if}} '}}	
		{{'{{#if errors.segmentname}}'}}
		<div class="row">
			<div class="alert alert-error">
				{{'{{errors.segmentname}}'}}
			</div>
		</div>
		{{'{{/if}}'}}

		<div class="col-sm-12 hidden-md hidden-lg">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Edite un segmento.</p>
						<p>Cambie las condiciones que desee para el segmento.</p>
					</div>
				</div>
			</div>
		</div>
		<form  class="form-horizontal" role="form">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label for="name" class="col-sm-4 control-label">*Nombre: </label>
						<div class="col-md-7">
							{{'{{view Ember.TextField valueBinding="name" class="form-control"}}'}}
						</div>
					</div>
					<div class="form-group">
						<label for="description" class="col-sm-4 control-label">Descripción: </label>
						<div class="col-md-7">
							{{'{{view Ember.TextArea valueBinding="description" class="form-control"}}'}}
						</div>
					</div>
				</div>
				<div class="hidden-xs hidden-sm col-md-5">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Edite un segmento.</p>
								<p>Cambie las condiciones que desee para el segmento.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-sm-12 col-md-7">
					<div class="form-group">
						<label  for="" class="col-md-4 control-label">Editar segmento con: </label>
						<div class="col-md-4">
							{{ '{{view Ember.Select
								contentBinding="App.criteria"
								optionValuePath="content.id"
								optionLabelPath="content.criterion"
								valueBinding="criterion"
								class="form-control"}}'
							}}
						</div>
						<p class="form-control-static">condiciones a continuacion:</p>
					</div>
				</div>
				<div class="clearfix"></div>
				{{'{{#if errors.segment}}'}}
					<div class="alert alert-error">
						{{'{{errors.segment}}'}}
					</div>
				{{'{{/if}}'}}

				{{' {{#each cr in criteria}} '}}
				<div class="col-lg-12">
					<div class="form-group">
						<label  for="" class="col-xs-1 control-label"></label>
						<div class="col-sm-3">
							{{ '{{view Ember.Select
								contentBinding="cfields"
								optionValuePath="content.id"
								optionLabelPath="content.name"
								valueBinding="cr.cfields"
								prompt="Seleccione un campo"
								placeholder="Seleccione un campo"
								class="form-control"}}'
							}}
						</div>
						<div class="col-xs-2">
							{{ '{{view Ember.Select
								  contentBinding="App.relations"
								  optionValuePath="content.id"
								  optionLabelPath="content.relation"
								  valueBinding="cr.relations"
								  class="form-control"}}'
							}}
						</div>
						<div class="col-sm-3">
							{{ '{{view Ember.TextField valueBinding="cr.value" placeholder="valor" required="required" class="form-control"}}' }}
						</div>
						<div class="col-md-3">
							<div class="row">
								{{ '{{#unless defaultCriteria}}' }}
									<button class="btn btn-default btn-sm" {{ '{{action aConditionLess cr}}' }}><i class="glyphicon glyphicon-trash"></i></button>
								{{ '{{/unless}}' }}

								{{ '{{#unless limitCriteria}}' }}
									<button class="btn btn-default btn-sm btn-add extra-padding" {{ '{{action aConditionMore}}' }}><i class="icon-plus"></i> Agregar condición</button>
								{{ '{{/unless}}' }}
							</div>
						</div>
					</div>
				</div>
				{{' {{/each}} '}}
				<div class="clearfix"></div>
				<div class="form-actions pull-right">
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-default btn-sm" {{ '{{action cancel this}}' }}>Cancelar</button>
						</div>
						<div class="col-xs-6">
							<button class="btn btn-sm btn-guardar" {{ '{{action edit this}}' }}>Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</script>