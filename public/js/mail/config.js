/* 
 * Configuración de Ember y model del objeto Mail
 */

App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

DS.RESTAdapter.reopen({
	namespace: MyUrl
});

App.Store = DS.Store.extend({});

App.fbimage = 'default';


App.Mail = DS.Model.extend({
	type: DS.attr('string'),
	scheduleDate: DS.attr('string'),
	name: DS.attr('string'),
	sender: DS.attr('string'),
	replyTo: DS.attr('string'),
	subject: DS.attr('string'),
	target: DS.attr('string'),
	googleAnalytics: DS.attr('string'),
	campaignName: DS.attr('string'),
	previewData: DS.attr('string'),
	mailcontent: DS.attr('boolean'),
	attachment: DS.attr('boolean'),
	plainText: DS.attr('string'),
	totalContacts: DS.attr('string'),
	fbaccounts: DS.attr('string'),
	fbmessagecontent: DS.attr('string'),
	fbimagepublication: DS.attr('string'),
	fbtitlecontent: DS.attr('string'),
	fbdescriptioncontent: DS.attr('string'),
	twaccounts: DS.attr('string'),
	twpublicationcontent: DS.attr('string'),
	fbloginurl: DS.attr('string'),
	twloginurl: DS.attr('string')
});