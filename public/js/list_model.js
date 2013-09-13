/*
 * Modelo de lista y DB simplificada
 */
if (!App.Dbase) {
	// definir version simplificada de Lista (si no esta definida)
	App.Dbase = DS.Model.extend({
		name: DS.attr('string'),
		lists: DS.hasMany('list')
	});
}

App.List = DS.Model.extend({
	dbase: DS.belongsTo('dbase'),
	name: DS.attr('string'),
	totalContacts: DS.attr('number'),
	activeContacts: DS.attr('number'),
	unsubscribedContacts: DS.attr('number'),
	bouncedContacts: DS.attr('number'),
	spamContacts: DS.attr('number'),
	inactiveContacts: DS.attr('number'),
	description: DS.attr( 'string' ),
	createdon: DS.attr('number'),
	updatedon: DS.attr('number'),
	
	totalContactsF: function() {
		return this.formatNumber(this.get('totalContacts'));
	}.property('totalContacts'),
			
	activeContactsF: function() {
		return this.formatNumber(this.get('activeContacts'));
	}.property('activeContacts'),
			
	unsubscribedContactsF: function() {
		return this.formatNumber(this.get('unsubscribedContacts'));
	}.property('unsubscribedContacts'),
			
	bouncedContactsF: function() {
		return this.formatNumber(this.get('bouncedContacts'));
	}.property('bouncedContacts'),
			
	spamContactsF: function() {
		return this.formatNumber(this.get('spamContacts'));
	}.property('spamContacts'),
			
	inactiveContactsF: function() {
		return this.formatNumber(this.get('inactiveContacts'));
	}.property('inactiveContacts'),

	formatNumber: function (n) {
		if (!n)
			return '0';
		return n.toLocaleString();
	}
});
