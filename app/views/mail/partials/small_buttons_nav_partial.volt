{# Barra de menu de botones pequenos #}
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-2 col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8">
		<ul class="list-inline pull-right">
			<li class="small-btn sm-btn-blue {{ activelnk=='compose'?'active':'' }}">
				<a href="{{ url('mail/compose') }}">
				<span class="sm-button-small-contact-list"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='list'?'active':'' }}">
				<a href="{{ url('mail/list') }}">
				<span class="sm-button-small-segment"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='template'?'active':'' }}">
				<a href="{{ url('template/index') }}">
				<span class="sm-button-small-bloq-list"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='scheduledmail'?'active':'' }}">
				<a href="{{ url('scheduledmail') }}">
				<span class="sm-button-small-database"></span>
				</a>
			</li>
		</ul>
	</div>
</div>
{# /Barra de menu de botones minimizados #}
