{# Barra de menu de botones pequenos #}
<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-2 col-md-6 col-md-offset-6 col-lg-4 col-lg-offset-8">
		<ul class="list-inline pull-right">
			<li class="small-btn sm-btn-blue {{ activelnk=='account'?'active':'' }}">
				<a href="{{ url('account') }}">
				<span class="sm-button-small-account"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='user'?'active':'' }}">
				<a href="{{ url('user') }}">
				<span class="sm-button-small-user"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='flashmessage'?'active':'' }}">
				<a href="{{ url('flashmessage') }}">
				<span class="sm-button-small-flashmessage"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='process'?'active':'' }}">
				<a href="{{ url('process') }}">
				<span class="sm-button-small-process"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='scheduledmail'?'active':'' }}">
				<a href="{{ url('scheduledmail') }}">
				<span class="sm-button-small-scheduledmail"></span>
				</a>
			</li>
			<li class="small-btn sm-btn-blue {{ activelnk=='socialmedia'?'active':'' }}">
				<a href="{{ url('socialmedia') }}">
				<span class="sm-button-small-socialmedia"></span>
				</a>
			</li>
		</ul>
	</div>
</div>
{# /Barra de menu de botones minimizados #}
