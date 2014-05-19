<div class="col-sm-12 text-center">
	<ul class="pagination">
		<li {{' {{ bind-attr class="canprev:enabled:disabled"}}'}}>
			<a href="#" {{ '{{action "firstPage" this}}' }}><i class="glyphicon glyphicon-fast-backward"></i></a>
		</li>
		<li {{' {{ bind-attr class="canprev:enabled:disabled"}}'}}>
			<a href="#" {{ '{{action "prevPage" this}}' }}><i class="glyphicon glyphicon-step-backward"></i></a>
		</li>
		<li>
			<span><b>{{  '{{totalrecords}}' }}</b> registros </span><span>Página <b>{{  '{{currentpage}}' }}</b> de <b>{{  '{{availablepages}}' }}</b></span>
		</li>
		<li {{' {{ bind-attr class="cannext:enabled:disabled"}}'}}>
			<a href="#" {{ '{{action "nextPage" this}}' }}><i class="glyphicon glyphicon-step-forward"></i></a>
		</li>
		<li {{' {{ bind-attr class="cannext:enabled:disabled"}}'}}>
			<a href="#" {{ '{{action "lastPage" this}}' }}><i class="glyphicon glyphicon-fast-forward"></i></a>
		</li>
	</ul>
	<br/>
	Mostrar 
	<div class="btn-group dropup">
		<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
		{{'{{recordsperpage}}'}}<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			<li><a href="#" {{ '{{action "setRxP" this 5}}' }}>5</a></li>
			<li><a href="#" {{ '{{action "setRxP" this 20}}' }}>20</a></li>
			<li><a href="#"{{ '{{action "setRxP" this 50}}' }}>50</a></li>
			<li><a href="#"{{ '{{action "setRxP" this 100}}' }}>100</a></li>
			<li><a href="#"{{ '{{action "setRxP" this 200}}' }}>200</a></li>
		</ul>
	</div>
	registros por pagina
</div>
