App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

DS.RESTAdapter.reopen({
	namespace: MyUrl
});

//App.ApplicationAdapter = DS.FixtureAdapter;

App.Store = DS.Store.extend({});


App.Mail = DS.Model.extend({
	type: DS.attr('string'),
	scheduleDate: DS.attr('string'),
	name: DS.attr('string'),
	fromName: DS.attr( 'string' ),
	fromEmail: DS.attr('string'),
	replyTo: DS.attr('string'),
	subject: DS.attr('string'),
	dbases: DS.attr('string'),
	contactlists: DS.attr('string'),
	segments: DS.attr('string'),
	filterByEmail: DS.attr('string'),
	filterByOpen: DS.attr('string'),
	filterByClick: DS.attr('string'),
	filterByExclude: DS.attr('string'),
	content: DS.attr('string'),
});

App.IndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('mail');
	},
			
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.rollback();
		}
	}
//	model: function(){
//		console.log('lala');
//		return this.store.find('mail');
//	},
////	deactivate: function () {
////		this.doRollBack();
////	},
////	contextDidChange: function() {
////		this.doRollBack();
////		this._super();
////    },
////	doRollBack: function () {
////		var model = this.get('currentModel');
////		if (model && model.get('isDirty') && !model.get('isSaving') ) {
////			model.get('transaction').rollback();
////		}
////	}
});

App.IndexController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	dbaselist: [],
	list: [],
	segmentlist: [],
	open: [],
	click: [],
	exclude: [],
	
	scheduleRadio: [
        {label: 'De inmediato:', value: 'now'},
        {label: 'En la siguiente fecha:', value: 'later'},
	],
	
	actions: {
		save: function(mail) {
			console.log(mail.get('name'));
			if (mail.get('name') === undefined) {
				$.gritter.add({title: 'Error', text: 'No ha ingresado un nombre para el correo, por favor verifique la información', sticky: false, time: 3000});
			}
			else {
				var dbaseArray = [];
				dbaseObj = this.get('dbaselist').toArray();
				for (var d=0; d<dbaseObj.length; d++) {
					dbaseArray.push(dbaseObj[d].id);
				}
				
				var contactlistArray = [];
				listObj = this.get('list').toArray();
				for (var d=0; d<listObj.length; d++) {
					contactlistArray.push(listObj[d].id);
				}
				
				var segmentArray = [];
				segmentObj = this.get('segmentlist').toArray();
				for (var d=0; d<segmentObj.length; d++) {
					segmentArray.push(segmentObj[d].id);
				}
				
				var openArray = [];
				openObj = this.get('open').toArray();
				for (var d=0; d<openObj.length; d++) {
					openArray.push(openObj[d].id);
					console.log(openObj[d].name);
				}
				
				var clickArray = [];
				clickObj = this.get('click').toArray();
				for (var d=0; d<clickObj.length; d++) {
					clickArray.push(clickObj[d].id);
					console.log(clickObj[d].name);
				}
				
				var excludeArray = [];
				excludeObj = this.get('exclude').toArray();
				for (var d=0; d<excludeObj.length; d++) {
					excludeArray.push(excludeObj[d].id);
					console.log(excludeObj[d].name);
				}
				
				var value = $('input[name=schedule]:checked').val();
				
				console.log('Date: ' + value);
				mail.set('dbases', dbaseArray.toString());
				mail.set('contactlists', contactlistArray.toString());
				mail.set('segments', segmentArray.toString());
				mail.set('filterByOpen', openArray.toString());
				mail.set('filterByClick', clickArray.toString());
				mail.set('filterByExclude', excludeArray.toString());
				if (value === 'now') {
					mail.set('scheduleDate', value);
				}
				else {
					
					mail.set('scheduleDate', excludeArray.toString());
				}
				
				this.handleSavePromise(mail.save(), 'Se han aplicado los cambios existosamente');
			}
		}
	}
});

App.DatePicker = Em.View.extend({
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

//App.Mail.FIXTURES = [
//  {id: 1, name: 'Mi nuevo correo', fromName: 'Will Montiel', fromEmail: 'william.montiel@sigmamovil.com', replyTo: 'noreply@noreply.com', subject: 'Este es un correo de prueba' }
//];