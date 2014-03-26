<div class="span5">
	<div class="pagination pagination-left padded">
		<ul>
			<li {{' {{ bind-attr class="canprev:enabled:disabled"}}'}}><a href="#" {{ '{{action "firstPage" this}}' }}>&lt;&lt;</a></li>
			<li {{' {{ bind-attr class="canprev:enabled:disabled"}}'}}><a href="#" {{ '{{action "prevPage" this}}' }}>&lt;</a></li>
			<li {{' {{ bind-attr class="cannext:enabled:disabled"}}'}}><a href="#" {{ '{{action "nextPage" this}}' }}>&gt;</a></li>
			<li {{' {{ bind-attr class="cannext:enabled:disabled"}}'}}><a href="#" {{ '{{action "lastPage" this}}' }}>&gt;&gt;</a></li>
		</ul>
	</div>
</div>
<div class="span4">
	<br /><br />
	Registros totales: <span class="label label-filling">{{  '{{totalrecords}}' }}</span>&nbsp;
	Página  <span class="label label-filling">{{  '{{currentpage}}' }}</span>
	de <span class="label label-filling">{{  '{{availablepages}}' }}</span>
</div>
