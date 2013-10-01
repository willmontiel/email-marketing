<script type="text/x-handlebars" data-template-name="segments/index">
	<div class="action-nav-normal pull-right" style="margin-bottom: 5px;">
		{{'{{#linkTo "segments.new" class="btn btn-default"}}'}}<i class="icon-plus"></i> Crear nuevo segmento{{'{{/linkTo}}'}}
	</div>
	<div class="row-fluid">
        <div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Segmentos
					</div>
				</div>
				<div class="box-content">
				{{'{{#each model}}'}}
					{{' {{# unless isNew }}' }}
					<div class="box-section news with-icons">
						<div class="avatar purple">
							<i class="icon-resize-horizontal icon-2x"></i>
						</div>
						<div class="news-content">
							<div class="pull-right" style="margin-right: 10px;">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li>{{ '{{#linkTo "segments.delete" this}}<i class="icon-trash"></i> Eliminar{{/linkTo}}' }}</li>
										<li>{{ '{{#linkTo "segments.edit" this}}<i class="icon-pencil"></i> Editar{{/linkTo}}' }}</li>
									</ul>
								</div>
							</div>
							<div class="news-title">
							<a href="{{url('segment/show/')}}{{ '{{unbound id}}' }}#/contacts">{{' {{name}} '}}</a>
							</div>
							<div class="news-text">
								{{' {{description}}'}}
							</div>
						</div>
					</div>
					{{' {{/unless}}'}}
				{{ '{{else}}' }}
					No hay segmentos disponibles
				{{ '{{/each}}' }}
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</div>
</script>
<script type="text/x-handlebars" data-template-name="segments">
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
	{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}	
	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="segments/new">
	<div class="box">
		<div class="box-header">
			<div class="title">Agregar un nuevo segmento</div>
		</div>
		<div class="box-content padded">
			<form>
			<div class="row-fluid">
				<div class="span3">
					<label>*Nombre: </label>
					{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre"}}' }}
					
					<label>Descripción: </label>
					{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción"}}' }}
					
					{{ '{{#unless dbaseSelected}}' }}
						<label>*Seleccione base de datos:</label>
							{{ '{{view Ember.Select 
									selectionBinding="dbase" 
									contentBinding="controllers.dbase.content" 
									optionValuePath="content.id" 
									optionLabelPath="content.name" 
									prompt="Seleccione una base de datos"}}' 
							}}
						 <br /><br />
						 	
						<button class="btn btn-default" {{ '{{action cancel this }}' }}>Cancelar</button>
						<button class="btn btn-default" disabled="disabled">Grabar</button>
					{{ '{{else}}' }}
						<br /><br />
						<select class="span12" disabled="disabled">
							<option>{{' {{dbase.name}} '}}</option>
						</select>
						<br /><br />
						<button class="btn btn-default" {{ '{{action cancel this }}' }}>Cancelar</button>
						<button class="btn btn-blue" {{ '{{action save this}}' }}>Grabar</button>
						<br />
					{{ '{{/unless}}' }}
				</div>
				<div class="span9">
					<div class="box-section news with-icons">
						<div class="avatar blue">
							<i class="icon-lightbulb icon-2x"></i>
						</div>
						<div class="news-content">
							<div class="news-title">
								Segmentos
							</div>
							<div class="news-text">
								With 2.2.2 out the door, our attention has shifted almost entirely to the next major update to the project ...
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr></hr>
			<div class="row-fluid">
				{{ '{{#if dbaseSelected}}' }}
				Crear segmento con 
					{{ '{{view Ember.Select
						  contentBinding="App.criteria"
						  optionValuePath="content.id"
						  optionLabelPath="content.criterion"
						  valueBinding="criterion"
						  class="span2"}}'
					}}
					condiciones a continuación:
				<div class="row-fluid">	
					<div class="span12">
						<br />
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
							{{ '{{#unless defaultCriteria}}' }}
							<button class="btn btn-default" {{ '{{action aConditionLess cr}}' }}><i class="icon-trash"></i></button>
							{{ '{{/unless}}' }}
						</div>
						{{' {{/each}} '}}
						<br />
						{{ '{{#unless limitCriteria}}' }}
							<button class="btn btn-default" {{ '{{action aConditionMore}}' }}><i class="icon-plus"></i> Agregar</button>
						{{ '{{/unless}}' }}
						<button class="btn btn-default" {{ '{{action reset this }}' }}><i class="icon-bolt"></i> Reset</button>
					</div>
				</div>
				{{ '{{/if}}' }}
			</div>
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
	<div class="box">
		<div class="box-header">
			<div class="title">
				Editar un segmento
			</div>
		</div>
		<div class="box-content padded">
			<label>*Nombre: </label>
			{{'{{view Ember.TextField valueBinding="name"}}'}}
				
			<label>Descripción: </label>
			{{'{{view Ember.TextField valueBinding="description"}}'}}
			
			<label>Editar Segmento con: </label>
			{{ '{{view Ember.Select
				contentBinding="App.criteria"
				optionValuePath="content.id"
				optionLabelPath="content.criterion"
				valueBinding="criterion"
				class="span2"}}'
			}}
			<br /><br />
			{{ '{{#unless limitCriteria}}' }}
			<button class="btn btn-default" {{ '{{action aConditionMore}}' }}>+</button>
			{{ '{{/unless}}' }}
			<br /><br />
			{{' {{#each cr in criteria}} '}}
				<div class="row-fluid">
					<div class="span3">
						{{ '{{view Ember.Select
							  contentBinding="cfields"
							  optionValuePath="content.id"
							  optionLabelPath="content.name"
							  valueBinding="cr.cfields"
							}}'
						}}
					</div>
					<div class="span3">
						{{ '{{view Ember.Select
							  contentBinding="App.relations"
							  optionValuePath="content.id"
							  optionLabelPath="content.relation"
							  valueBinding="cr.relations"
							}}'
						}}
					</div>
					<div class="span3">
						{{ '{{view Ember.TextField valueBinding="cr.value" placeholder="valor" required="required"}}' }}
					</div>
					{{ '{{#unless defaultCriteria}}' }}
					<button class="btn btn-default" {{ '{{action aConditionLess cr}}' }}>-</button>
					{{ '{{/unless}}' }}
				</div>
			{{' {{/each}} '}}
				<br />
				<button class="btn btn-blue" {{ '{{action edit this}}' }}>Editar</button>
				<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="segments/show">

</script>