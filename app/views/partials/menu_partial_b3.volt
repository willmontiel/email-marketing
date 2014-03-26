<ul class="nav nav-pills nav-stacked" role="navigation">
{% for item in elements.get() %} 
	<li class="{{item.class}}">
		<span class="glow"></span>
		<a href="{{ url(item.url) }}">
			<i class="{{item.icon}} icon-2x"></i>
			<span>{{item.title}}</span>
		</a>
	</li>
{% endfor %}
</ul>
