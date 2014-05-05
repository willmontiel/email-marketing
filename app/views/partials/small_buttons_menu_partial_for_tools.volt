{# Barra de menu de botones minimizados #}
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-2 col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8">
		<ul class="list-inline pull-right">
			<li class="small-btn sm-btn-blue {{ activelnk=='account'?'active':'' }}">
				<a href="{{ url('account') }}" data-toggle="tooltip" data-placement="bottom" title="Cuentas">
				<span class="sm-button-small-account"></span>
				</a>
			</li>
			
			<li class="small-btn sm-btn-blue {{ activelnk=='user'?'active':'' }}">
				<a href="{{ url('user') }}" data-toggle="tooltip" data-placement="bottom" title="Usuarios">
				<span class="sm-button-small-user"></span>
				</a>
			</li>
			
			<li class="small-btn sm-btn-blue {{ activelnk=='flashmessage'?'active':'' }}">
				<a href="{{ url('flashmessage') }}" data-toggle="tooltip" data-placement="bottom" title="Mensajes administrativos">
				<span class="sm-button-small-msj"></span>
				</a>
			</li>
			
			<li class="small-btn sm-btn-blue {{ activelnk=='process'?'active':'' }}">
				<a href="{{ url('process') }}" data-toggle="tooltip" data-placement="bottom" title="Envíos">
				<span class="sm-button-small-send"></span>
				</a>
			</li>
			
			<li class="small-btn sm-btn-blue {{ activelnk=='scheduledmail/manage'?'active':'' }}">
				<a href="{{ url('scheduledmail/manage') }}" data-toggle="tooltip" data-placement="bottom" title="Programación de envíos de todas las cuentas">
				<span class="sm-button-small-scheduledmail"></span>
				</a>
			</li>
			
			<li class="small-btn sm-btn-blue {{ activelnk=='socialmedia'?'active':'' }}">
				<a href="{{ url('socialmedia') }}" data-toggle="tooltip" data-placement="bottom" title="Cuentas de redes sociales">
				<span class="sm-button-small-social"></span>
				</a>
			</li>
		</ul>
	</div>
</div>
{# /Barra de menu de botones minimizados #}
