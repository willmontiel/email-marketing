<div class="primary-sidebar">
	<ul class="nav nav-collapse collapse nav-collapse-primary">
	{% for item in elements.get() %} 
		<!-- Item de menu -->
		<li class="{{item.class}}">
			<a href="{{ url(item.url) }}">
				<i class="{{item.icon}} icon-2x"></i>
				<span>{{item.title}}</span>
			</a>
		</li>
	{% endfor %}
	</ul>
</div>