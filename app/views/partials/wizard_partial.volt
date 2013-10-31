<div id="breadcrumbs">
	{% for w in smart_wizard(mail) %} 
		<div class="{{w['wizard']}}">
			{% if w['url'] !== '#' %}
				<a href="{{url(w['url'])}}/{{mail.idMail}}">
			{% endif %}
				<span class="breadcrumb-label"><i class="{{w['icon']}}"></i> {{w['name']}}</span>
				<span class="breadcrumb-arrow"><span></span></span>
			{% if w['url'] !== '#' %}
				</a>
			{% endif %}
		</div>
	{% endfor %}
</div>