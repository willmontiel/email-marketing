<div class="span5">
	<div class="pagination">
		<ul>
			{% if '{{currentpage}}' == 1 %}
				<li class="previous"><span class="fui-arrow-left" {{ '{{action firstPage this}}' }}><span class="fui-arrow-left"></span></span></li>
				<li class="previous"><span class="fui-arrow-left" {{ '{{action prevPage this}}' }}></span></li>
			{% else %}
				<li class="previous active"><span class="fui-arrow-left" {{ '{{action firstPage this}}' }}><span class="fui-arrow-left"></span></span></li>
				<li class="previous active"><span class="fui-arrow-left" {{ '{{action prevPage this}}' }}></span></li>
			{% endif %}		
					
			{% if '{{currentpage}}' >= '{{availablepages}}'%}
				<li class="next"><span class="fui-arrow-right" {{ '{{action nextPage this}}' }}></span></li>
				<li class="next"><span class="fui-arrow-right" {{ '{{action lastPage this}}' }}><span class="fui-arrow-right"></span></span></li>
			{% else %}
				<li class="next active"><span class="fui-arrow-right" {{ '{{action nextPage this}}' }}></span></li>
				<li class="next active"><span class="fui-arrow-right" {{ '{{action lastPage this}}' }}><span class="fui-arrow-right"></span></span></li>	
			{% endif %}
		</ul>
	 </div>
</div>
<div class="span3">
	<br><br>
	Registros totales: <span class="label label-filling">{{  '{{totalrecords}}' }}</span>&nbsp;
	Página  <span class="label label-filling">{{  '{{currentpage}}' }}</span>
	de <span class="label label-filling">{{  '{{availablepages}}' }}</span>
</div>
