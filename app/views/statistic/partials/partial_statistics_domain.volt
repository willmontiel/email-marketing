<script type="text/javascript">
	//Estadisticas agrupadas por dominio para aperturas
	var domain_opening = [];
	{% for dopen in domainsByOpens%}
		var obj = new Object;
			obj.name = '{{dopen.domain}}';
			obj.y = {{dopen.total}};

			domain_opening.push(obj);
	{% endfor %}
		
	domain_opening = setDefaultObject(domain_opening);
	createCharts('domain-opening', domain_opening, false, false);

	//Estadisticas agrupadas por dominio para rebotes
	var domain_bounced = [];
	{% for dbounced in domainsByBounced%}
		var obj = new Object;
			obj.name = '{{dbounced.domain}}';
			obj.y = {{dbounced.total}};

			domain_bounced.push(obj);
	{% endfor %}
		
	domain_bounced = setDefaultObject(domain_bounced);	
	createCharts('domain-bounced', domain_bounced, false, false);
	
	//Estadisticas agrupadas por dominio para contactos des-suscritos
	var domain_unsubscribed = [];
	{% for dunsubscribed in domainsByUnsubscribed%}
		var obj = new Object;
			obj.name = '{{dunsubscribed.domain}}';
			obj.y = {{dunsubscribed.total}};

			domain_unsubscribed.push(obj);
	{% endfor %}
		
	domain_unsubscribed = setDefaultObject(domain_unsubscribed);	
	createCharts('domain-dunsubscribed', domain_unsubscribed, false, false);
	
	//Estadisticas agrupadas por dominio para reportes de spam
	var domain_spam = [];
	{% for dspam in domainsBySpam%}
		var obj = new Object;
			obj.name = '{{dspam.domain}}';
			obj.y = {{dspam.total}};

			domain_spam.push(obj);
	{% endfor %}
		
	domain_spam = setDefaultObject(domain_spam);	
	createCharts('domain-spam', domain_spam, false, false);
	
	function setDefaultObject(array) {
		var defaultObj = new Object;
		defaultObj.name = 'Sin estadísticas';
		defaultObj.y = 1;
		defaultObj.color = '#BDBDBD';
		
		if (array.length == 0) {
			array.push(defaultObj);
		}
		
		return array;
	}
</script>