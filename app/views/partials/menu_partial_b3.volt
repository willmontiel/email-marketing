<!-- Menu vertical con el nuevo tema -->

<ul id="main-navigation" class="nav nav-pills nav-stacked" role="navigation">
{% for item in elements.get() %} 
	<li class="{{item.class}}">
		<a href="{{ url(item.url) }}">
			<span class="mark"></span>
			<i class="{{item.icon}} icon-2x"></i>
			<span>{{item.title}}</span>
		</a>
	</li>
{% endfor %}
</ul>
