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
								<br />
								<span class="label label-filling">{{ '{{dbase.name }}' }}</span>
							</div>
						</div>
					</div>
					{{' {{/unless}}'}}
				{{ '{{else}}' }}
					<div class="box-section news with-icons">
						<div class="avatar green">
							<i class="icon-lightbulb icon-2x"></i>
						</div>
						<div class="news-content">
							<div class="news-title">
								No hay segmentos
							</div>
							<div class="news-text">
								No tiene segmentos de contactos creados, para crear uno haga click en el siguiente enlace
								<br /><br />
								{{'{{#linkTo "segments.new" class="btn btn-default"}}'}}<i class="icon-plus"></i> Crear nuevo segmento{{'{{/linkTo}}'}}
							</div>
						</div>
					</div>
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
	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="segments/new">
	<div class="box">
		<div class="box-header">
			<div class="title">Agregar un nuevo segmento</div>
		</div>
		<div class="box-content padded">
			{{ '{{#if App.errormessage }}' }}
				<div class="row-fluid">
					<div class="alert alert-error">
						<h4>Error!</h4>
						{{ '{{ App.errormessage }}' }}
					</div>
				</div>
			{{ '{{/if}} '}}
			{{'{{#if errors.segmentname}}'}}
				<div class="row-fluid">
					<div class="alert alert-error">
						<h4>Error!</h4>
						{{'{{errors.segmentname}}'}}
					</div>
				</div>
			{{'{{/if}}'}}
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
									prompt="Seleccione una base de datos"
									class="span11"}}' 
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
					<div class="box">
						<div class="box-section news with-icons">
							<div class="avatar green">
								<i class="icon-lightbulb icon-2x"></i>
							</div>
							<div class="news-content">
								<div class="news-title">
									Segmentos
								</div>
								<div class="news-text">
									<p>
										Aqui podrá crear segmentos con los contactos de una base de datos determinada.
									</p>
									<p>
										Un segmento es un fragmento, o en este caso una lista, hecha con los contactos que siguen determinadas condiciones, como 
										por ejemplo: "Todos los contactos en los que el nombre empiece con jaime", este ejemplo
										creara una lista con todos los contactos que siguen esa condición
									</p>
									<p>
										Para empezar a crear un segmento, deberá ingresar un nombre y una descripción,
										y deberá seleccionar una base de datos de contactos en la cual se aplicarán las condiciones definidas.
									</p>
									<p>
										El siguiente paso es seleccionar si se deben completar todas las condiciones que defina o cualquiera de ellas,
										esto quiere decir que si selecciona todas las condiciones, un contacto solo pertenecerá
										al segmento sí y solo sí cumple con todas las condiciones, o en caso de que seleccione cualquiera de ellas
										el contacto pertenecerá al segmento sí cumple al menos una de ellas.
									</p>
								</div>
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
						{{'{{#if errors.segment}}'}}
							<div class="alert alert-error">
								<h4>Error! </h4>
								{{'{{errors.segment}}'}}
							</div>
						{{'{{/if}}'}}
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
							<button class="btn btn-default" {{ '{{action aConditionMore}}' }}><i class="icon-plus"></i> Agregar condición</button>
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
			{{ '{{#if App.errormessage }}' }}
				<div class="row-fluid">
					<div class="alert alert-error">
						<h4>Error!</h4>
						{{ '{{ App.errormessage }}' }}
					</div>
				</div>
			{{ '{{/if}} '}}	
			{{'{{#if errors.segmentname}}'}}
				<div class="row-fluid">
					<div class="alert alert-error">
						<h4>Error!</h4>
						{{'{{errors.segmentname}}'}}
					</div>
				</div>
			{{'{{/if}}'}}
			<div class="row-fluid">
				<div class="span3">
					<label>*Nombre: </label>
					{{'{{view Ember.TextField valueBinding="name"}}'}}
				
					<label>Descripción: </label>
					{{'{{view Ember.TextArea valueBinding="description"}}'}}
					<br /><br />
					<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
					<button class="btn btn-blue" {{ '{{action edit this}}' }}>Editar</button>
				</div>
				<div class="span9">
				</div>
			</div>
			<hr></hr>
			<div class="row-fluid">
				Editar Segmento con:
				{{ '{{view Ember.Select
					contentBinding="App.criteria"
					optionValuePath="content.id"
					optionLabelPath="content.criterion"
					valueBinding="criterion"
					class="span2"}}'
				}}
				<br /><br />
				{{'{{#if errors.segment}}'}}
					<div class="alert alert-error">
						<h4>Error!</h4>
						{{'{{errors.segment}}'}}
					</div>
				{{'{{/if}}'}}
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
								  valueBinding="cr.relations"
								}}'
							}}
						</div>
						<div class="span3">
							{{ '{{view Ember.TextField valueBinding="cr.value" placeholder="valor" required="required"}}' }}
						</div>
						{{ '{{#unless defaultCriteria}}' }}
						<button class="btn btn-default" {{ '{{action aConditionLess cr}}' }}><i class="icon-trash"></i></button>
						{{ '{{/unless}}' }}
					</div>
				{{' {{/each}} '}}
					<br />
					{{ '{{#unless limitCriteria}}' }}
						<button class="btn btn-default" {{ '{{action aConditionMore}}' }}><i class="icon-plus"></i> Agregar condición</button>
					{{ '{{/unless}}' }}
				</div>
			</div>
		
			
			
	</div>
</script>

<script type="text/x-handlebars" data-template-name="segments/show">

</script>