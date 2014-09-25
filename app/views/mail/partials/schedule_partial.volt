{{ '{{#unless isScheduleExpanded'}}}}
	<div {{'{{bind-attr class=": scheduleEmpty:bg-warning:"}}'}}>
			<div class="dl-horizontal" {{ '{{action "expandSchedule" this}}' }}>
				{{'{{#if scheduleEmpty }}'}}
					<div class="wrapper">
						<dt>Fecha del envío:</dt>
						<dd><img src="{{url('vendors/bootstrap_v3/images/bg-calendar.png')}}" /></dd>
					</div>
				{{'{{else}}'}}
					<hr>
					<div class="col-md-6">
						<div class="">
							<h4 class="text-center">Fecha del envío:</h4>
							<div class="bg-wrap-calendar center-block">
								<div class="date">
									<h1 class="day-send">{{'{{scheduleDay}}'}}</h1>
									<h6 class="month-send">{{'{{scheduleMonth}}'}}</h6>
									<h6 class="year-send">{{'{{scheduleYear}}'}}</h6>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="">
							<h4 class="text-center">Hora del envío:</h4>
							<div class="bg-wrap-time center-block">
								<span class="time-send">{{'{{scheduleTime}}'}}</span>
							</div>
						</div>
					</div>
				{{'{{/if}}'}}

			</div>
	</div>
{{ '{{else}}' }}
	<div class="wrapper">
		<h4 class="paneltitle">Programación del envío</h4>
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
					Envíar correo: 
				</label>
					
				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 8px;">
						{{ ' {{view Ember.RadioButton name="schedule" id="now" selectionBinding="scheduleRadio" value="now"}}' }}
						<label for="now">Ahora mismo:</label>
					</div>
						
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 8px;">
						{{'{{#if scheduleEmpty }}'}}
							{{ ' {{view Ember.RadioButton name="schedule" id="later" selectionBinding="scheduleRadio" value="later"}}' }}
						{{ '{{else}}' }}
							{{ ' {{view Ember.RadioButton name="schedule" id="later" selectionBinding="scheduleRadio" value="later" checked="checked"}}' }}
						{{ '{{/if}}' }}
						<label for="later">Seleccione la fecha:</label>
				{{'{{#if scheduleEmpty }}'}}
						<div id="programmer" style="display: none; margin-top: 20px; margin-bottom: 20px;">
				{{ '{{else}}' }}
						<div id="programmer" style="display: block; margin-top: 20px; margin-bottom: 20px;">			
				{{ '{{/if}}' }}
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<div class="input-group date date-picker group-datepicker">
									{{'{{view App.DatePicker valueBinding="date" class="form-control" readonly="readonly"}}'}}
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
								</div>	
							</div>
							
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<div class="input-append bootstrap-timepicker">
									{{ '{{view App.TimePicker valueBinding="time" class="time-picker input-small"}}' }}
									<span class="add-on">
										<i class="glyphicon glyphicon-time" style="top: 4px !important;"></i>
									</span>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
				
			<div class="form-group text-right">
				<div class="col-sm-12">
					<button class="btn btn-default btn-sm extra-padding" {{'{{action "discardSchedule" this}}'}}>Descartar cambios</button>
					<button class="btn btn-default btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
				</div>
			</div>
		</form>
	</div>
{{ '{{/unless}}' }}