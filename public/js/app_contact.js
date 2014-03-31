App.set('errormessage', '');

//Rutas
App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('contact');
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
		if (model && model.get('isDirty') && !model.get('isSaving') ) {
			model.get('transaction').rollback();
		}
	}
});

//App.ContactsShowRoute = Ember.Route.extend({
//});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('contact');
	},

	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.rollback();
		}
	}

});

App.ContactsNewbatchRoute = Ember.Route.extend();

//App.ContactsEditRoute = Ember.Route.extend({
//	deactivate: function () {
//		this.doRollBack();
//	},
//	contextDidChange: function() {
//		this.doRollBack();
//		this._super();
//    },
//	doRollBack: function () {
//		var model = this.get('currentModel');
//		if (model && model.get('isDirty') && !model.get('isSaving') ) {
//			model.get('transaction').rollback();
//		}
//	}
//});

App.ContactsImportRoute = Ember.Route.extend();

App.ContactsImportView = Ember.View.extend({
	didInsertElement: function() {
		$('.easy-pie-step').easyPieChart({barColor: '#599cc7', trackColor: '#a1a1a1', scaleColor: false, lineWidth: 10, size: 50, lineCap: 'butt'});
    }
});
//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend();

App.ContactsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions: {
		save: function() {
			var filter = /^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
			if (filter.test(this.get('email'))) {
				this.content.set('isActive', true);
				this.content.set('isSubscribed', true);
				App.set('errormessage', '');
				App.set('segment', '');
				this.handleSavePromise(this.content.save(), 'contacts', 'El contacto ha sido creado con exito!');
			}
			else {
				App.set('errormessage', 'La dirección de correo electrónico ingresada es invalida, por favor verifica la información');
				this.transitionToRoute('contacts.new');
			}
			
		},	
		
		cancel: function(){
			App.set('errormessage', '');
//			var record = this.get('model');
//			if (record.get('isDirty')) {
//				record.rollback();
//			}
			this.transitionToRoute("contacts");
		}
	}
	,
	emailChanged: function () {
		var em = this.get('content.email');
//		if (em.regexp()) {
//			
//		}
	}.observes('content.email')
});

//App.ContactsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
//	actions : {
//		edit: function() {
//			var filter = /^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
//			if (filter.test(this.get('email'))) {
//				App.set('errormessage', '');
//				this.handleSavePromise(this.content.save(), 'contacts', 'El contacto fue actualizado exitosamente');
//			}
//			else {
//				App.set('errormessage', 'La dirección de correo electrónico ingresada no es valida por favor verifique la información')
//			}
//		},
//		cancel: function(){
//			App.set('errormessage', '');
//			this.get('model').rollback();
//			this.transitionToRoute("contacts");
//		}
//	}
//});

App.ContactsDeleteController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
    actions : {
		delete: function() {
			this.get('model').deleteRecord();
			this.handleSavePromise(this.content.save(), 'contacts', 'El contacto ha sido eliminado con exito!');
		},
				
		cancel: function(){
			 this.get("model").rollback();
			 this.transitionTo("contacts");
		}
	}
	
	
});

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinSearchReferencePagination, Ember.AclMixin, Ember.SaveHandlerMixin,{
//	historyMail: function(){
//		var content = null;
//		if(this.content.content === undefined) {
//			content = this.content;
//		}
//		else {
//			content = this.content.content;
//		}
//		for(var i = 0; i < content.length; i++) {
//			content[i].set('mailHistory', JSON.parse(content[i].get('mailHistory')))
//		}
//	}.observes('this.content'),
	
	init: function () {
		this.set('acl', App.contactACL);
	},
	filters: [
		{name: "Des-suscritos", value: "unsubscribed"},
		{name: "Rebotados",    value: "bounced"},
		{name: "Spam",    value: "spam"},
		{name: "Bloqueados",    value: "blocked"},
		{name: "Todos",    value: "all"}
	],
	filter: {
		value: "all"
	},
	refreshRecords: function() {
		this.criteria = this.get('searchCriteria');
		this.finalFilter = this.get('filter.value');
		var t = this;
		this.store.find('contact', {searchCriteria: this.criteria, filter: this.finalFilter}).then(function(d) {
			t.set('content', d.content);
		});
	},
	
	actions: {
		search: function() {
			this.refreshRecords();
		},

		reset: function() {
			this.set('searchCriteria', '');
			this.set('filter.value', "all");
			this.criteria = '';
			this.finalFilter = '';
			this.refreshRecords();	
		},
		expand: function (contact) {
			if(contact.get('isExpanded')) {
				contact.set('isExpanded', false);
			}
			else {
				contact.set('isExpanded', true);
			}
		},

		subscribedcontact: function (contact) {
			contact.set('isSubscribed', true);
			contact.save();
		},
		unsubscribedcontact: function (contact) {
			contact.set('isSubscribed', false);
			contact.save();
		},

		edit: function(contact) {
			var filter = /^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
			var self = this;
			if (filter.test(contact.get('email'))) {
				App.set('errormessage', '');
				App.set('isEditable', false);
				this.handleSavePromise(contact.save(), 'contacts', 'El contacto fue actualizado exitosamente', function () {
					$('.x-editable.editable-unsaved').removeClass('editable-unsaved');
				});
			}
			else {
				App.set('errormessage', 'La dirección de correo electrónico ingresada no es valida por favor verifique la información')
			}
		},

		discard: function(contact) {
			contact.rollback();
			$('.x-editable.editable-unsaved').removeClass('editable-unsaved');
		},
		
		collapse: function(contact) {
			contact.set('isExpanded', false);
		}

	},
	
	onChangedFilter: function() {
		this.refreshRecords();
	}.observes('filter.value')		,

	modelClass: App.Contact
});

//App.ContactsShowController = Ember.ObjectController.extend({
//	historyMail: function(){
//		var mailHistory = JSON.parse(this.content.get('mailHistory'));
//		this.set('history', mailHistory);
//	}.observes(this.content),
//			
//	actions :{
//		subscribedcontact: function () {
//			//this.set("isSubscribed", true);
//			var self = this;
//			self.content.set('isSubscribed', true);
//			self.content.save();
//		},
//		unsubscribedcontact: function () {
//			var self = this;
//			self.content.set('isSubscribed', false);
//			self.content.save();
//		}
//	}
//});

App.ContactsImportController = Ember.ObjectController.extend({
	cancel: function() {
		this.get("target").transitionTo("lists");
	}
});

//Views

App.ContactsNewView = Ember.View.extend({
  didInsertElement: function() {
        this.$("select").select2({
			placeholder: "Seleccione las Opciones"
		});
    }
});

App.ContactsEditView = Ember.View.extend({
  didInsertElement: function() {
        this.$("select").select2({
			placeholder: "Seleccione las Opciones"
		});
    }
});       

App.DatePickerField = Em.View.extend({
  templateName: 'datepicker',
  didInsertElement: function() {
    var onChangeDate, self;
    self = this;
    onChangeDate = function(ev) {
      return self.set("value", moment.utc(ev.date).format("YYYY-MM-DD"));
    };
    return this.$('.datepicker').datepicker({
      separator: "-"
    }).on("changeDate", onChangeDate);
  }
});

function collapse_contact(id) {
	$('.collapse-link-' + id).hide();
}