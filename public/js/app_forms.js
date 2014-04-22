App.Form = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	urlsuccess: DS.attr( 'string' ),
	urlerror: DS.attr( 'string' ),
	urlwelcome: DS.attr( 'string' ),
	optin: DS.attr( 'boolean' ),
	optinmail: DS.attr( 'string' ),
	welcome: DS.attr( 'boolean' ),
	welcomemail: DS.attr( 'string' ),
	notify: DS.attr( 'string' ),
	notifymail: DS.attr( 'string' )
});

App.FormsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('form');
	}
});

App.FormsIndexController = Ember.ObjectController.extend({});

App.FormsNewRoute = Ember.Route.extend({});

App.FormsNewController = Ember.ObjectController.extend({
	init : function() {
		$(function(){
			formeditor.startEvents();
		});
	}
});

App.FormsSetupRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('form');
	}
});

App.FormsSetupController = Ember.ObjectController.extend({
	init : function() {
		$(function(){
			$('#double-optin').on('click', function() {
				
			});
		});
	},
	show_field_url_welcome: function(form) {
		$('div.welcome-url-field').show();
		$('#optin')[0].checked = ( $('#optin')[0].checked ) ? false : true ;
		form.set('optin', $('#optin')[0].checked);
	} ,
	show_editor: function(option) {
		$('.form-setup-content').hide();
		$('.create-email-spot').show();
		$('.title-advanced-editor').html('<h5>Correo de Doble Optin</h5>');
		$('.here-comes-frame').html('<iframe id="iframeEditor" src="' + config.baseUrl + 'mail/editor_frame" width="100%" onload="iframeResize()" seamless></iframe>');
		$('#btn-for-' + option).show();
	},
	create_optin_mail: function(form) {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		form.set('optinmail', editor);
		this.cleanEditor();
	},
	create_welcome_mail: function(form) {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		form.set('welcomemail', editor);
		this.cleanEditor();
	},
	create_notify_mail: function(form) {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		form.set('notifymail', editor);
		this.cleanEditor();
	},
	cleanEditor: function() {
		$('.title-advanced-editor').empty();
		$('.here-comes-frame').empty();
		$('.create-email-spot').hide();
		$('#btn-for-optin').hide();
		$('.form-setup-content').show();
	}
});