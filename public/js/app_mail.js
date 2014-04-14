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
	plainText: DS.attr('string'),
	
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
	scheduleRadio: '',
	fromSummary: '',
	
	headerEmpty: function () {
		var n, e, s;
		n = this.get('content.fromName');
		e = this.get('content.fromEmail');
		s = this.get('content.subject');
		
		n = (n == '')?null:n;
		e = (e == '')?null:e;
		s = (s == '')?null:s;
		
		if (!e ||  !n || !s) {
			this.set('fromSummary', 'Sin definir <email@domain.com>');
			return true;
		}
		this.set('fromSummary', n + '<' + e + '>');
		return false;
	}.property('content.fromName', 'content.fromEmail', 'content.subject'),
			
	targetEmpty: function () {
		var d, l, s;
		
		d = this.get('this.dbaselist');
		l = this.get('this.list');
		s = this.get('this.segmentlist');
		
		d = (d == '')?null:d;
		l = (l == '')?null:l;
		s = (s == '')?null:s;
		
		if (!d && !l && !s) {
			return true;
		}
		return false;
	}.property('dbaselist.[]', 'list.[]', 'segmentlist.[]'), 
			
	filterEmpty: function () {
		var byEmail, byOpen, byClick, byEx;
		
		byEmail = this.get('content.filterByEmail');
//		byOpen = this.get('this.open');
//		byClick = this.get('this.click');
//		byEx = this.get('this.exclude');
		
		byEmail = (byEmail == '')?null:byEmail;
//		byOpen = (byOpen == '')?null:byOpen;
//		byClick = (byClick == '')?null:byClick;
//		byEx = (byEx == '')?null:byEx;
		
//		if (!byEmail && !byOpen && !byClick && !byEx) {
		if (!byEmail) {
			return true;
		}
		return false;
	}.property('content.filterByEmail'), 
	
	GAEmpty: function () {
		return true;
	}.property(),
	
	scheduleEmpty: function () {
		var schedule;
		
		schedule = this.get('this.scheduleRadio');
		
		if (!schedule) {
			return true;
		}
		return false;
	}.property('scheduleRadio'),
	
	actions: {
		save: function(mail) {
			console.log('Nombre: ' + mail.get('name'));
			if (mail.get('name') === undefined) {
				$.gritter.add({title: 'Error', text: 'No ha ingresado un nombre para el correo, por favor verifique la información', sticky: false, time: 3000});
			}
			else {
				var dbases = getArrayValue(this.get('dbaselist'));
				var contactlists = getArrayValue(this.get('list'));
				var segments = getArrayValue(this.get('segmentlist'));
				var open = getArrayValue(this.get('open'));
				var click = getArrayValue(this.get('click'));
				var exclude = getArrayValue(this.get('exclude'));
				
				var type;
				var content;
				
				if (document.getElementById('iframeEditor') != null) {
					type = 'Editor';
					content = document.getElementById('iframeEditor').contentWindow.catchEditorData();
					document.getElementById('iframeEditor').contentWindow.RecreateEditor();
				}
				else if (document.getElementById('iframeHtml') != null) {
					type = 'Html';
					content = document.getElementById('iframeHtml').contentWindow.$('#redactor_content').val();
				}
				
				var value = this.get('scheduleRadio');
				
				if (value === 'now') {
					console.log('its now')
					mail.set('scheduleDate', value);
				}
				else {
					console.log('its not now')
					console.log(mail.get('scheduleDate'));
				}
				
				mail.set('dbases', dbases);
				mail.set('contactlists', contactlists);
				mail.set('segments', segments);
				mail.set('filterByOpen', open);
				mail.set('filterByClick', click);
				mail.set('filterByExclude', exclude);
				mail.set('type', type);
				mail.set('content', content);
				
//				this.handleSavePromise(mail.save(), 'Se han aplicado los cambios existosamente');
				this.set('isHeaderExpanded', false);
				this.set('isTargetExpanded', false);
				this.set('isContentExpanded', false);
				this.set('isGAExpanded', false);
				this.set('isScheduleExpanded', false);
			}
		},
		
		expandHeader: function () {
			setExpandAttr(this, 'isHeaderExpanded');
		},
				
		expandTarget: function () {
			setExpandAttr(this, 'isTargetExpanded');
		},
		
		expandContent: function () {
			setExpandAttr(this, 'isContentExpanded');
		},
				
		expandGA: function () {
			setExpandAttr(this, 'isGAExpanded');
		},
				
		expandSchedule: function () {
			setExpandAttr(this, 'isScheduleExpanded');
		},
		
		discardHeader: function () {
			console.log(this.get('fromName'));
			this.set('fromName', '');
			this.set('fromEmail', '');
			this.set('replyTo', '');
			this.set('subject', '');
			console.log(this.get('fromName'));
			setExpandAttr(this, 'isHeaderExpanded');
		},
				
		discardTarget: function () {
			this.set('dbaselist', []);
			this.set('list', []);
			this.set('segmentlist',[]);
			this.set('open',[]);
			this.set('click',[]);
			this.set('exclude',[]);
			setExpandAttr(this, 'isTargetExpanded');
		},
	}
});

function getArrayValue(value) {
	var array = [];
	var obj = value.toArray();
	for (var i = 0; i < obj.length; i++) {
		array.push(obj[i].id);
	}
	return array.toString();
}

function setExpandAttr(self, expand) {
//	self.set('isHeaderExpanded', false);
//	self.set('isTargetExpanded', false);
//	self.set('isContentExpanded', false);
//	self.set('isGAExpanded', false);
//	self.set('isScheduleExpanded', false);
	
	if(self.get(expand)) {
		self.set(expand, false);
	}
	else {
		self.set(expand, true);
	}
}

App.DateTimePicker = Em.View.extend({
	templateName: 'datetimepicker',
	didInsertElement: function() {
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), nowTemp.getHours(), nowTemp.getMinutes(), nowTemp.getSeconds(), 0);
		
		$('#schedule').datetimepicker({
			language: 'es',
			maskInput: true,
			pickTime: true,
			format: "MM/DD/YYYY H:mm",
			pickSeconds: false,
			startDate: now
		});
	}
});

Ember.RadioButton = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id"],
    click : function() {
        this.set("selection", this.$().val())
		
		$("#programmer").hide();
		$('#schedule').data("DateTimePicker").hide();
		$("#schedule").val('');

		switch (this.get('selection')) {
			case "now":
				break;

			case "later":
				$("#programmer").show();
				$('#schedule').data("DateTimePicker").show();
				break;
		}
    },
    checked : function() {
        return this.get("value") == this.get("selection");   
    }.property()
});

Ember.RadioButtonTarget = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id"],
    click : function() {
        $("#db").hide();
		$("#list").hide();
		$("#seg").hide();
		
		$("#dbases").val('');
		$('#segments').val('');
		$('#contactlists').val('');
		
		var value = this.$().val();
		switch (value) {
			case "dataBase":
				$("#db").show();
				break;
			case "contactList":
				$("#list").show();
				break;
			case "segment":
				$("#seg").show();
				break;
		}
    },
});

Ember.RadioFilter = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id"],
    click : function() {
		$("#mail").hide();
		$("#open").hide();
		$("#click").hide();
		$("#exclude").hide();

		$("#sendMail").val('');
		$('#sendOpen').val('');
		$('#sendClick').val('');
		$('#sendExclude').val('');
		
		var value = this.$().val();
		
		switch (value) {
			case "byMail":
				$("#mail").show();
				break;
			case "byOpen":
				$("#open").show();
				break;
			case "byClick":
				$("#click").show();
				break;
			case "byExclude":
				$("#exclude").show();
				break;
		}
    },
});

//App.Mail.FIXTURES = [
//  {id: 1, name: 'Mi nuevo correo', fromName: 'Will Montiel', fromEmail: 'william.montiel@sigmamovil.com', replyTo: 'noreply@noreply.com', subject: 'Este es un correo de prueba' }
//];