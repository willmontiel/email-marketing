<div class="span5">
	<div class="pagination">
		<ul>
			<li class="previous"><span class="fui-arrow-left" {{ '{{action firstPage this}}' }} style="cursor: pointer;"><span class="fui-arrow-left" style="cursor: pointer;"></span></span></li>
			<li class="previous"><span class="fui-arrow-left" {{ '{{action prevPage this}}' }} style="cursor: pointer;"></span></li>							
			<li class="next"><span class="fui-arrow-right" {{ '{{action nextPage this}}' }}></span></li>
			<li class="next"><span class="fui-arrow-right" {{ '{{action lastPage this}}' }}><span class="fui-arrow-right"></span></span></li>
		</ul>
	 </div>
</div>
<div class="span3">
	<br><br>
	Registros totales: <span class="label label-filling">{{  '{{totalrecords}}' }}</span>&nbsp;
	Página  <span class="label label-filling">{{  '{{currentpage}}' }}</span>
	de <span class="label label-filling">{{  '{{availablepages}}' }}</span>
</div>
