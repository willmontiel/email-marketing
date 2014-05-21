{# 
	************************************************************
	Partial para paginacion que no esta hecho con EMBER
	Parametros: pagination_url
	Ejemplo:
	partial('partials/pagination_static_partial', ['pagination_url': 'index/index'])
	************************************************************
#}
<div class="text-center">
	<ul class="pagination">
		<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
			<a href="{{ url(pagination_url) }}"><i class="glyphicon glyphicon-fast-backward"></i></a>
		</li>
		<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
			<a href="{{ url(pagination_url) }}?page={{ page.before }}"><i class="glyphicon glyphicon-step-backward"></i></a>
		</li>
		<li>
			<span><b>{{page.total_items}}</b> registros </span><span>Página <b>{{page.current}}</b> de <b>{{page.total_pages}}</b></span>
		</li>
		<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
			<a href="{{ url(pagination_url) }}?page={{page.next}}"><i class="glyphicon glyphicon-step-forward"></i></a>
		</li>
		<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
			<a href="{{ url(pagination_url) }}?page={{page.last}}"><i class="glyphicon glyphicon-fast-forward"></i></a>
		</li>
	</ul>
</div>
