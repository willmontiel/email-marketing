<div id="breadcrumbs">
	{% for w in smart_wizard(mail) %} 
		<div class="{{w['wizard']}}">
			<a href="{{url(w['url'])}}/{{mail.idMail}}">
				<span class="breadcrumb-label"><i class="{{w['icon']}}"></i> {{w['name']}}</span>
				<span class="breadcrumb-arrow"><span></span></span>
			</a>
		</div>
	{% endfor %}
</div>