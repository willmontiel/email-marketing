<div class="primary-sidebar primary-sidebar-editor">
	<ul class="nav nav-collapse collapse nav-collapse-primary">
	{% for item in elements.get() %} 
		<li class="{{item.class}}">
			<span class="glow"></span>
			<a href="{{ url(item.url) }}">
				<i class="{{item.icon}} icon-2x"></i>
			</a>
		</li>
	{% endfor %}
	</ul>
</div>