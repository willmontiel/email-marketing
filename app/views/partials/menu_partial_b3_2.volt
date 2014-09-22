<ul>
{% for item in elements.get() %} 
	<li class="{{item.class}}">
		<a href="{{ url(item.url) }}">
			<div class="item-icon_menu">
				<span class="{{item.icon}}"></span>
			</div>
			<div class="item-title-menu">
				<span>{{item.title}}</span>
			</div>
		</a>
	</li>
{% endfor %}
</ul>
