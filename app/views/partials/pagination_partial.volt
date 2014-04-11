	<div class="col-sm-12 text-right">
		<ul class="pagination">
			<li {{' {{ bind-attr class="canprev:enabled:disabled"}}'}}>
				<a href="#" {{ '{{action "firstPage" this}}' }}><i class="glyphicon glyphicon-fast-backward"></i></a>
			</li>
			<li {{' {{ bind-attr class="canprev:enabled:disabled"}}'}}>
				<a href="#" {{ '{{action "prevPage" this}}' }}><i class="glyphicon glyphicon-step-backward"></i></a>
			</li>
			<li>
				<span>Página {{  '{{currentpage}}' }}/{{  '{{availablepages}}' }}</span>
			</li>
			<li {{' {{ bind-attr class="cannext:enabled:disabled"}}'}}>
				<a href="#" {{ '{{action "nextPage" this}}' }}><i class="glyphicon glyphicon-step-forward"></i></a>
			</li>
			<li {{' {{ bind-attr class="cannext:enabled:disabled"}}'}}>
				<a href="#" {{ '{{action "lastPage" this}}' }}><i class="glyphicon glyphicon-fast-forward"></i></a>
			</li>
		</ul>
		<p>
			Son en total {{  '{{totalrecords}}' }} registros. Página  <span class="label label-filling">{{  '{{currentpage}}' }}</span>
			de <span class="label label-filling">{{  '{{availablepages}}' }}</span>
		</p>
	</div>
