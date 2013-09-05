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
				<div class="padded">
					<label>
					Seleccione base de datos:
					{{ '{{view Ember.Select contentBinding="App.DBObjectList" selectionBinding="dbase" optionValuePath="content.id" optionLabelPath="content.name" class="span3"}}' }}
					</label>
					<br/>
					<label>					
					Crear segmento con 
						{{ '{{view Ember.Select
								contentBinding="App.criteria"
								optionValuePath="content.id"
								optionLabelPath="content.criterion"
								valueBinding="criterion"
								class="span2"
							}}'
						}}
					condiciones a continuación:
					</label>
				</div>
				<br />
				<div class="row-fluid">
					<div class="span3">
						{{ '{{#if isDbaseSelected}}' }}
							{{ '{{dbase.id}}' }}
						{{ '{{/if}}' }}
					</div>
					<div class="span3">
						{{ '{{view Ember.Select
							contentBinding="App.relations"
							optionValuePath="content.id"
							optionLabelPath="content.relation"
							valueBinding="relation"
							}}'
						}}
					</div>
					<div class="span3">
						{{ '{{view Ember.TextField valueBinding="value" placeholder="valor" required="required" autofocus="autofocus"}}' }}
					</div>
					<div class="span1">
						<button class="btn btn-default" {{ '{{action pressed}}' }}>+</button>
					</div>
				</div>
				<div class="row-fluid">
					{{ '{{#if isPressed}}' }}
						{{ '{{partial "segments/newcondition"}}' }}
					{{ '{{/if}}' }}
				</div>
				<button class="btn btn-default">Crear</button>
			</form>
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="segments/_newcondition">
	<div class="row-fluid">
		<div class="span3">
			<select>
				<option>Email</option>
				<option>Nombre</option>
			</select>
		</div>
		<div class="span3">
			{{ '{{view Ember.Select
				contentBinding="App.relations"
				optionValuePath="content.id"
				optionLabelPath="content.relation"
				valueBinding="relation"
				}}'
			}}
		</div>
		<div class="span3">
			{{ '{{view Ember.TextField valueBinding="value" placeholder="valor" required="required" autofocus="autofocus"}}' }}
		</div>
		<div class="span1">
			<button class="btn btn-default">+</button>
		</div>
		<div class="span1">
			<button class="btn btn-default">-</button>
		</div>
	</div>
</script>
