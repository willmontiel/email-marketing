<div class="span5">
	<div class="pagination">
		<ul>
			{% if '{{currentpage}}'|json_decode == 1 %}
				<li class="previous active"><span class="fui-arrow-left" {{ '{{action firstPage this}}' }}><span class="fui-arrow-left"></span></span></li>
				<li class="previous active"><span class="fui-arrow-left" {{ '{{action prevPage this}}' }}></span></li>
			{% else %}
				<li class="previous"><a href="#" class="inactive"><span class="fui-arrow-left"><span class="fui-arrow-left"></span></span></a></li>
				<li class="previous"><a href="#" class="inactive"><span class="fui-arrow-left"></span></a></li>
			{% endif %}		
					
			{% if '{{currentpage}}'|json_decode >= '{{availablepages}}'|json_decode %}
				<li class="next active"><span class="fui-arrow-right" {{ '{{action nextPage this}}' }}></span></li>
				<li class="next active"><span class="fui-arrow-right" {{ '{{action lastPage this}}' }}><span class="fui-arrow-right"></span></span></li>
			{% else %}
				<li class="next"><a href="#" class="inactive"><span class="fui-arrow-right"></span></a></li>
				<li class="next"><a href="#" class="inactive"><span class="fui-arrow-right"><span class="fui-arrow-right"></span></span></a></li>	
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
