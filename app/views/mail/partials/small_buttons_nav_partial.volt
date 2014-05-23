{# Barra de menu de botones pequenos #}
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-2 col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8">
		<ul class="list-inline pull-right mtop-5">
			<li class="small-btn sm-btn-blue {{ activelnk=='compose'?'active':'' }}">
				<a href="{{ url('mail/compose') }}" data-toggle="tooltip" data-placement="bottom" title="Nuevo correo!">
				<span class="sm-button-small-email-new"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='list'?'active':'' }}">
				<a href="{{ url('mail/list') }}" data-toggle="tooltip" data-placement="bottom" title="Lista de correos!">
				<span class="sm-button-small-email-list"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='template'?'active':'' }}">
				<a href="{{ url('template/index') }}" data-toggle="tooltip" data-placement="bottom" title="Plantillas!">
				<span class="sm-button-small-template"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='scheduledmail'?'active':'' }}">
				<a href="{{ url('scheduledmail') }}" data-toggle="tooltip" data-placement="bottom" title="Programación de envíos!">
				<span class="sm-button-small-schedule"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue disabled {{ activelnk=='statisticsmail'?'active':'' }}">
				<a href="#" data-toggle="tooltip" data-placement="bottom" title="Estadísticas">
				<span class="sm-button-small-stats"></span>
				</a>
			</li>
		</ul>
	</div>
</div>
{# /Barra de menu de botones minimizados #}
