{# Barra de menu de botones minimizados #}
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-2 col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8">
		<br/>
		<ul class="list-inline pull-right">
			<li class="small-btn sm-btn-blue {{ activelnk=='list'?'active':'' }}">
				{% if activelnk != 'list' %}
				<a href="{{ url('contactlist') }}#/lists">
				{% endif %}
					<span class="sm-button-small-contact-list"></span></a>
				{% if activelnk != 'list' %}
				</a>
				{% endif %}
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='segments'?'active':'' }}">
				{% if activelnk != 'segments' %}
				<a href="{{ url('contactlist') }}#/segments">
				{% endif %}
					<span class="sm-button-small-contact-list"></span></a>
				{% if activelnk != 'segments' %}
				</a>
				{% endif %}
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='blockedemails'?'active':'' }}">
				{% if activelnk != 'blockedemails' %}
				<a href="{{ url('contactlist') }}#/blockedemails">
				{% endif %}
					<span class="sm-button-small-contact-list"></span></a>
				{% if activelnk != 'blockedemails' %}
				</a>
				{% endif %}
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='search'?'active':'' }}">
				{% if activelnk != 'search' %}
				<a href="{{ url('contacts/search') }}#/contacts">
				{% endif %}
					<span class="sm-button-small-contact-list"></span></a>
				{% if activelnk != 'search' %}
				</a>
				{% endif %}
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='dbase'?'active':'' }}">
				{% if activelnk != 'dbase' %}
				<a href="{{ url('dbase') }}">
				{% endif %}
					<span class="sm-button-small-contact-list"></span></a>
				{% if activelnk != 'dbase' %}
				</a>
				{% endif %}
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='import'?'active':'' }}">
				{% if activelnk != 'import' %}
				<a href="{{ url('process/import') }}">
				{% endif %}
					<span class="sm-button-small-contact-list"></span></a>
				{% if activelnk != 'import' %}
				</a>
				{% endif %}
			</li>
		</ul>
	</div>
</div>
{# /Barra de menu de botones minimizados #}
