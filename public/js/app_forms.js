App.Form = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	title: DS.attr( 'string' ), 
	urlsuccess: DS.attr( 'string' ),
	urlerror: DS.attr( 'string' ),
	urlwelcome: DS.attr( 'string' ),
	optin: DS.attr( 'boolean' ),
	optinmail: DS.attr( 'string' ),
	welcome: DS.attr( 'boolean' ),
	welcomemail: DS.attr( 'string' ),
	notify: DS.attr( 'string' ),
	notifymail: DS.attr( 'string' ),
	content: DS.attr( 'string' )
});

App.FormsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('form');
	}
});

App.FormsIndexController = Ember.ObjectController.extend({});

App.FormsNewRoute = Ember.Route.extend({
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && model.get('isSaving') == false) {
			model.rollback();
		}
	}
});

App.FormsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions: {
		sendData: function() {
			var obj = JSON.stringify(formeditor.persist());
			this.content.set('content', obj);
			this.content.set('title', $('#form-title-name').text());
			this.handleSavePromise(this.content.save(), 'forms.index', 'El Formulario se ha modificado exitosamente');
		},
		previous: function(form) {
//			this.get("model").rollback();
//			this.transitionToRoute('forms.index');
			console.log(this.get('target'))
			console.log(this.get('target').get('renderedName'))
		},
		cancel: function() {
			this.get("model").rollback();
			this.transitionToRoute('forms.index');
		}
	}
});

App.FormsNewView = Ember.View.extend({
	didInsertElement: function() {
		formeditor = new FormEditor();
		formeditor.startEvents(this.controller.content.get('content'));
    }
});

App.FormsSetupRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('form');
	},
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && model.get('isSaving') == false) {
			model.rollback();
		}
	}
});

App.FormsSetupController = Ember.ObjectController.extend( Ember.SaveFormHandlerMixin, {
	show_field_url_welcome: function() {
		if( this.get('optin') ) {
			$('div.welcome-url-field').show();
		}
		else {
			$('div.welcome-url-field').hide();
		}
	}.observes('content.optin'),
	cleanEditor: function() {
		$('.title-advanced-editor').empty();
		$('.here-comes-frame').empty();
		$('.create-email-spot').hide();
		$('.btn-form-email-creator-save').hide();
		$('.form-setup-content').show();
	},
	actions: {
		show_editor: function(option) {
			$('.form-setup-content').hide();
			$('.create-email-spot').show();
			var msj = '';
			switch (option) {
				case 'optin':
					msj = 'Doble Optin';
					objMail = JSON.parse(this.get('optinmail'));
					break;
				case 'welcome':
					msj = 'Bienvenida';
					objMail = JSON.parse(this.get('welcomemail'));
					break;
				case 'notify':
					msj = 'Notificacion';
					objMail = JSON.parse(this.get('notifymail'));
					break;
			}
			$('.title-advanced-editor').html('<h5>Correo de ' + msj + ' </h5>');
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
		next: function() {
			this.handleSavePromise(this.content.save(), 'forms.new', 'Ok, listo');
		},
		cancel: function() {
			this.get("model").rollback();
			this.transitionToRoute('forms.index');
		}
	}
});

App.FormsEditRoute = Ember.Route.extend({
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && model.get('isSaving') == false) {
			model.rollback();
		}
	}
});

App.FormsEditController = Ember.ObjectController.extend( Ember.SaveFormHandlerMixin, {
	show_field_url_welcome: function() {
		if( this.get('optin') ) {
			$('div.welcome-url-field').show();
		}
		else {
			$('div.welcome-url-field').hide();
		}
	}.observes('content.optin'),
	cleanEditor: function() {
		$('.title-advanced-editor').empty();
		$('.here-comes-frame').empty();
		$('.create-email-spot').hide();
		$('.btn-form-email-creator-save').hide();
		$('.form-setup-content').show();
	},
	actions: {
		show_editor: function(option) {
			$('.form-setup-content').hide();
			$('.create-email-spot').show();
			var msj = '';
			switch (option) {
				case 'optin':
					msj = 'Doble Optin';
					objMail = JSON.parse(this.get('optinmail'));
					break;
				case 'welcome':
					msj = 'Bienvenida';
					objMail = JSON.parse(this.get('welcomemail'));
					break;
				case 'notify':
					msj = 'Notificacion';
					objMail = JSON.parse(this.get('notifymail'));
					break;
			}
			$('.title-advanced-editor').html('<h5>Correo de ' + msj + ' </h5>');
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
		next: function() {
			this.handleSavePromise(this.content.save(), 'forms.new', 'Se han aplicado los cambios');
		},
		cancel: function() {
			this.get("model").rollback();
			this.transitionToRoute('forms.index');
		}
	}
});

App.FormsRemoveRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('form');
	}
});

App.FormsRemoveController = Ember.ObjectController.extend( Ember.SaveHandlerMixin, {
	actions: {
		eliminate: function() {
			var form = this.get('model');
			form.deleteRecord();
			this.handleSavePromise(form.save(), 'forms.index', 'Se ha eliminado el formulario exitosamente'),
					
			function (error) {
				form.rollback();
            };
		},
		cancel: function() {
			this.get("model").rollback();
			this.transitionToRoute('forms.index');
		}
	}
});