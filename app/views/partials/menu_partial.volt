<div class="primary-sidebar">
	<ul class="nav nav-collapse collapse nav-collapse-primary">
	{% for item in elements.getMenu() %} 
		<li class="{{item['class']}}">
			<span class="glow"></span>
			<a href="{{ url(item["url"]) }}">
				<i class="{{item['icon']}} icon-2x"></i>
				<span>{{item['title']}}</span>
			</a>
		</li>
	{% endfor %}
	</ul>
</div>