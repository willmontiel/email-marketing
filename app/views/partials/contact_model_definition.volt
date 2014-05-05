{# Definicion del modelo de contactos #}

{# NOTA: esto esta dentro de un <script> tag #}
var myContactModel = {

	email: DS.attr( 'string' ),
	name: DS.attr( 'string' ),
	lastName: DS.attr( 'string' ),
	status: DS.attr( 'number' ),
	activatedOn: DS.attr('string'),
	bouncedOn: DS.attr('string'),
	subscribedOn: DS.attr('string'),
	unsubscribedOn: DS.attr('string'),
	spamOn: DS.attr('string'),
	ipActive: DS.attr('string'),
	ipSubscribed: DS.attr('string'),
	updatedOn: DS.attr('string'),
	createdOn: DS.attr('string'),
	isBounced: DS.attr('boolean'),
	isSubscribed: DS.attr('boolean'),
	isSpam: DS.attr('boolean'),
	isActive: DS.attr('boolean'),
	isEmailBlocked: DS.attr('boolean'),
	mailHistory: DS.attr('string'),

	{% if include_list %}
	list: DS.belongsTo('list'),
	{% endif %}
	
	isReallyActive: function () {
		if (this.get('isActive') && this.get('isSubscribed') && !(this.get('isSpam') || this.get('isBounced'))) {
			return true;
		}
		return false;
	}.property('isSubscribed,isActive'),
	mailHistoryArray: function () {
		if(this.get('mailHistory') !== undefined) {
			return JSON.parse(this.get('mailHistory'))
		}
		return null;
	}.property(),

	visualCues: function () {
		var color = '#5cb85c';
		if (this.get('isSpam')) {
			color = '#d9534f';
		}
		else if (this.get('isEmailBlocked')) {
			color = '#505050';
		}
		else if (this.get('isBounced')) {
			color = '#EF8807';
		}
		else if (!this.get('isSubscribed') && this.get('isActive')) {
			color = '#9d9d9d';
		}
		else if (!this.get('isActive')) {
			color = '#5bc0de';
		}
		return 'border-left: solid 4px ' + color +  ';';
	}.property('isSubscribed')
	

{# Definicion de campos variables #}

	{%for field in fields%}
	,
		{% if field.type == "Text" %}
			campo{{field.idCustomField }}: DS.attr('string')
		{% elseif field.type == "Date" %}
			campo{{field.idCustomField }}: DS.attr('string')
		{% elseif field.type == "TextArea" %}
			campo{{field.idCustomField }}: DS.attr('string')
		{% elseif field.type == "Numerical" %}
			campo{{field.idCustomField }}: DS.attr('number')
		{% elseif field.type == "Select" %}
			campo{{field.idCustomField }}: DS.attr('string')
		{% elseif field.type == "MultiSelect" %}
			campo{{field.idCustomField }}: DS.attr('string')
		{% endif %}
	
	{%endfor%}
};
