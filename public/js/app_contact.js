App.set('errormessage', '');

//Rutas
App.ContactsIndexRoute = Ember.Route.extend(Ember.MixinSearchReferencePagination, {
	model: function(){
		return this.store.find('contact', {searchCriteria: App.criteria, filter: App.finalFilter});
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
		//$('.easy-pie-step').easyPieChart({barColor: '#599cc7', trackColor: '#a1a1a1', scaleColor: false, lineWidth: 10, size: 50, lineCap: 'butt'});
    }
});
//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend({
	actions: {
		cancel: function() {
			this.transitionToRoute("contacts");
		}
	}
});

App.ContactsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions: {
		save: function() {
			var filter = /^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
			if (filter.test(this.get('email'))) {
				this.content.set('isActive', true);
				this.content.set('isSubscribed', true);
				App.set('errormessage', '');
				App.set('segment', '');

				// Este metodo del MIXIN muestra errores en App.errormessage
				// No hace rollback y tampoco hace transicion hacia otra ruta
				this.handleSavePromiseAppErrorNoRollback(this.content.save(), 'contacts', 'El contacto ha sido creado con exito!', null, 'contacts.new');
			}
			else {
				App.set('errormessage', 'La dirección de correo electrónico ingresada es invalida, por favor verifica la información');
				this.transitionToRoute('contacts.new');
			}
			
		},	
		
		cancel: function(){
			App.set('errormessage', '');
			this.transitionToRoute("contacts");
		}
	}
	,
	emailChanged: function () {
		var em = this.get('content.email');
	}.observes('content.email')
});

App.ContactsDeleteController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
    actions : {
		delete: function() {
			this.get('model').deleteRecord();
			this.handleSavePromise(this.content.save(), 'contacts', 'El contacto ha sido eliminado con exito!');
		},
				
		cancel: function(){
			 this.get("model").rollback();
			 this.transitionToRoute("contacts");
		}
	}
	
	
});

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinSearchReferencePagination, Ember.AclMixin, Ember.SaveHandlerMixin,{

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
		App.criteria = this.get('searchCriteria');
		App.finalFilter = this.get('filter.value');
//		this.criteria = this.get('searchCriteria');
//		this.finalFilter = this.get('filter.value');
		var t = this;
		this.store.find('contact', {searchCriteria: App.criteria, filter: App.finalFilter}).then(function(d) {
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
			App.criteria = '';
			App.finalFilter = '';
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



App.ContactsImportController = Ember.ObjectController.extend({
	cancel: function() {
		this.get("target").transitionTo("lists");
	}
});

//Views

App.ContactsNewView = Ember.View.extend({
  didInsertElement: function() {
//		$('.date_view_picker').datetimepicker({
//			format:'d/m/Y',
//			inline:true,
//			timepicker:false,
//			lang:'es',
//			startDate: 0
//		});
		
		$('.date_view_picker').datetimepicker({
			language: 'es',
			autoclose: true,
			weekStart: false,
			todayBtn: true,
			inline:true,
			format: "dd/mm/yyyy",
			todayHighlight: true,
			showMeridian: false,
			startView: 2,
			minView: 2,
			forceParse: 0
		});
    }
});

//App.ContactsEditView = Ember.View.extend({
//  didInsertElement: function() {
//        $('.date_view_picker').datetimepicker({
//			format:'Y-m-d',
//			inline:true,
//			timepicker:false,
//			lang:'es',
//			startDate: 0
//		});
//    }
//});       


App.DatePickerField = Em.View.extend({
  templateName: 'datepicker',
  didInsertElement: function() {
    var onChangeDate, self;
    self = this;
    onChangeDate = function(ev) {
      return self.set("value", moment.utc(ev.date).format("DD-MM-YYYY"));
    };
    return this.$('.datepicker').datepicker({
      separator: "/"
    }).on("changeDate", onChangeDate);
  }
});


//App.DateTimePickerField = Em.View.extend({
//	templateName: 'datetimepicker',
//	didInsertElement: function() {
//		$('.datepicker').datepicker({
//			format: 'dd/mm/yyyy',
//			autoclose: true,
//			todayBtn: true,
//			todayHighlight: true
//		});
//	},
//			
//	onChangeValue: function () {
//		var fecha = new Date(this.get('value'));
//		console.log(fecha);
//	}.observes('this.value')
//});

//App.DatePicker = Em.View.extend({
//	templateName: 'datepicker_b3',
//	didInsertElement: function() {
//		$('.datepicker').datetimepicker({
////			format:'dd/mm/yyyy',
//			pickDate: true,                
//			pickTime: false,  
//			showToday: true,
//		});
//	}
//});


//App.DropDownSelect = Ember.View.Extend({
//	templateName: 'dropdown',
//	didInsertElement: function() {
//		$('.dropdown-toggle').dropdown({
//
//		});
//	}
//});

function collapse_contact(id) {
	$('.collapse-link-' + id).hide();
}