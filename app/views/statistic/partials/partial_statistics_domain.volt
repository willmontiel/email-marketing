<script type="text/javascript">
	var domain_opening = [];
	{% for dopen in domainsByOpens%}
		var obj = new Object;
			obj.name = '{{dopen.domain}}';
			obj.y = {{dopen.total}};

			domain_opening.push(obj);
	{% endfor %}
	createCharts('domain-opening', domain_opening, false, false);


	var domain_bounced = [];
	{% for dbounced in domainsByBounced%}
		var obj = new Object;
			obj.name = '{{dbounced.domain}}';
			obj.y = {{dbounced.total}};

			domain_bounced.push(obj);
	{% endfor %}
	createCharts('domain-bounced', domain_bounced, false, false);

	var domain_unsubscribed = [];
	{% for dunsubscribed in domainsByUnsubscribed%}
		var obj = new Object;
			obj.name = '{{dunsubscribed.domain}}';
			obj.y = {{dunsubscribed.total}};

			domain_unsubscribed.push(obj);
	{% endfor %}
	createCharts('domain-dunsubscribed', domain_unsubscribed, false, false);

	var domain_spam = [];
	{% for dspam in domainsBySpam%}
		var obj = new Object;
			obj.name = '{{dspam.domain}}';
			obj.y = {{dspam.total}};

			domain_spam.push(obj);
	{% endfor %}
	createCharts('domain-spam', domain_spam, false, false);
</script>