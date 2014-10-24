{# Barra de menu de botones minimizados #}
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-2 col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8">
		<ul class="list-inline pull-right mtop-5">
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='list'?'active':'' }}">
				<a href="{{ url('contactlist') }}#/lists" data-toggle="tooltip" data-placement="bottom" title="Listas de contactos">
				<span class="sm-button-small-contact-list"></span>
				</a>
			</li>
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='segments'?'active':'' }}">
				<a href="{{ url('contactlist') }}#/segments" data-toggle="tooltip" data-placement="bottom" title="Segmentos">
				<span class="sm-button-small-segment"></span>
				</a>
			</li>
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='blockedemails'?'active':'' }}">
				<a href="{{ url('contactlist') }}#/blockedemails" data-toggle="tooltip" data-placement="bottom" title="Lista de bloqueo">
				<span class="sm-button-small-bloq-list"></span>
				</a>
			</li>
{#
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='search'?'active':'' }}">
				<a href="{{ url('contacts/search') }}#/contacts">
				<span class="sm-button-small-contact-search"></span>
				</a>
			</li>
#}
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='dbase'?'active':'' }}">
				<a href="{{ url('dbase') }}" data-toggle="tooltip" data-placement="bottom" title="Configuración avanzada">
				<span class="sm-button-small-database"></span>
				</a>
			</li>
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='import'?'active':'' }}">
				<a href="{{ url('process/import') }}" data-toggle="tooltip" data-placement="bottom" title="Lista de importación">
				<span class="sm-button-small-import-list"></span>
				</a>
			</li>
			<li class="small-btn-nav sm-btn-blue {{ activelnk=='export'?'active':'' }}">
				<a href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" title="Detalle de exportacón">
				<span class="sm-button-small-import-list"></span>
				</a>
			</li>
		</ul>
	</div>
</div>
{# /Barra de menu de botones minimizados #}
