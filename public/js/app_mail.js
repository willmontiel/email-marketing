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
	dbases: DS.attr('string'),
	contactlists: DS.attr('string'),
	segments: DS.attr('string'),
	filterByEmail: DS.attr('string'),
	filterByOpen: DS.attr('string'),
	filterByClick: DS.attr('string'),
	filterByExclude: DS.attr('string'),
	googleAnalytics: DS.attr('string'),
	campaignName: DS.attr('string'),
	previewData: DS.attr('string'),
	mailcontent: DS.attr('boolean'),
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

App.IndexRoute = Ember.Route.extend({
	model: function() {
	   if (App.maildata !== undefined) {
		   var id = App.maildata[0].id;
		   return this.store.find('mail', id);
	   }
	   else {
		   return this.store.createRecord('mail');
	   }
	}
});

App.ExternalLinkComponent = Ember.Component.extend({
  tagName: "a",
  classNames: [],
  attributeBindings: ["href"],
  href: (function() {
    return this.get('pattern').fmt(this.get('content.id'));
  }).property("content.id")
});

Ember.Handlebars.helper("external-link", App.ExternalLinkComponent);

App.IndexController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	senderName: '',
	senderEmail: '',
	date: '',
	time: '',
	senderAttr: [],
	dbaselist: [],
	list: [],
	segmentlist: [],
	open: [],
	click: [],
	exclude: [],
	fbaccountsel: [],
	twaccountsel: [],
	scheduleRadio: '',
	linksAnalytics: [],
	fromSummary: '',
	summaryMail: '',
	summaryAnalytics: '',
	
	imageUrl: function () {
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("#my-dropzone");
		$("#my-dropzone").addClass('dropzone');
		myDropzone.on("success", function(file, response) {
			var newMedia = new Gallery(response.thumb, response.filelink, response.title, response.id);
			newMedia.createMedia();
			newMedia.mediaSelected();
			media.setGallery(newMedia);
			media.imageSelected(response.filelink, response.title);
		});

		var res = config.assetsUrl;
		if( this.get('fbimagepublication') === undefined || this.get('fbimagepublication') === 'default' || this.get('fbimagepublication') === 'post_default.png' ) {
			this.set('fbimagepublication', 'post_default.png');
			res = config.imagesUrl;
		}
		return res;
	}.property('fbimagepublication'),
	//Retorna el id del correo para crear las url's
	url: function () {
		return '/' + this.get('id');
	}.property('id'),
	
	//Setear la imagen por defecto de facebook en caso de que no tenga ninguna
	facebookImage: function() {
		if( this.get('fbimagepublication') === undefined || this.get('fbimagepublication') === 'default' ) {
			this.set('fbimagepublication', 'post_default.png');
		}
	}.observes('this.content'),
	
	//Si hay un id se encargara se recrear el correo para su edición
	setSelectsContent: function () {
		if (this.get('id') !== null) {
			var arrayDbase = setTargetValues(this.get('this.dbases'), App.dbs);
			var arrayList = setTargetValues(this.get('this.contactlists'), App.lists);
			var arraySegment = setTargetValues(this.get('this.segments'), App.segments);
			var fbaccounts = setTargetValues(this.get('this.fbaccounts'), App.fbaccounts);
			var twaccounts = setTargetValues(this.get('this.twaccounts'), App.twaccounts);
			
			this.set('dbaselist', arrayDbase);
			this.set('list', arrayList);
			this.set('segmentlist', arraySegment);
			
			this.set('fbaccountsel', fbaccounts);
			this.set('twaccountsel', twaccounts);
			
			var arrayOpen = setTargetValues(this.get('this.filterByOpen'), App.sendByOpen);
			var arrayClick = setTargetValues(this.get('this.filterByClick'), App.sendByClick);
			var arrayExclude = setTargetValues(this.get('this.filterByExclude'), App.excludeContact);
			
			this.set('open', arrayOpen);
			this.set('click', arrayClick);
			this.set('exclude', arrayExclude);
			
			if( this.get('fbimagepublication') !== undefined || this.get('fbimagepublication') !== 'default' ) {
				App.fbimage = this.get('fbimagepublication');
			}
			
			if (App.googleAnalyticsLinks !== undefined) {
				var arrayAnalytics = setGoogleAnalyticsValues(this.get('this.googleAnalytics'), App.googleAnalyticsLinks);
				this.set('linksAnalytics', arrayAnalytics);
			}
			
			var sender = setTargetValue(this.get('this.sender'), App.senders);
			this.set('senderAttr', sender);
			
			var scheduleDate = this.get('scheduleDate');
			var date = moment(scheduleDate, "DD-MM-YYYY HH:mm").lang('es');
			var day = getDay(date);
			var month = getNumberMonth(date);
			var year = getYear(date);
			var d = day + '/' + month + '/' + year;
			this.set('date', d);
			
			var time = getTime(date);
			this.set('time', time);
		}
	}.observes('this.content'),
	
			
	checkFormsStatus: function(content, dbases, contactlists, segments) {
		if(content && (dbases || contactlists || segments)) {
			var t = this;
			var id = this.get('this.id');
			$.ajax({
				url: config.baseUrl + 'mail/checkforms/' + id,
				type: "POST",			
				error: function(){
				},
				success: function(msg) {
					t.set('invalidDbaseForm', false);
					if(msg.status) {
						t.set('invalidDbaseForm', true);
					}
				}
			});
		}
	},
	
	//Observa el contenido del header (fromName, fromEmail, etc)
	headerEmpty: function () {
		var sn, s;
		sn = this.get('content.sender');
		s = this.get('content.subject');
		
		sn = (sn === '')?null:sn;
		s = (s === '')?null:s;
		
		if (!sn || !s) {
			this.set('fromSummary', 'Sin definir <email@domain.com>');
			return true;
		}
		
		var sender = sn.split("/");
		this.set('fromSummary', sender[1] + '<' + sender[0] + '>');
		
		return false;
	}.property('content.sender', 'content.subject'),
		
	//Observa que se hayan seleccionado destinatarios
	targetEmpty: function () {
		
		this.checkFormsStatus(this.get('this.mailcontent'), this.get('this.dbases'), this.get('this.contactlists'), this.get('this.segments'));

		var d, l, s;
		d = this.get('this.dbaselist');
		l = this.get('this.list');
		s = this.get('this.segmentlist');
		
		d = (d.length === 0)?null:d;
		l = (l.length === 0)?null:l;
		s = (s.length === 0)?null:s;
		
		if (!d && !l && !s) {
			return true;
		}
		return false;
	}.property('dbaselist.[]', 'list.[]', 'segmentlist.[]'), 
		
	//Si hay un id de correo la seleccion de contenido (editor, plantillas, html, importar contenido) se habilita de lo contrario no
	isContentAvailable: function () {
		var id;
		id = this.get('content.id');
		id = (id === '')?null:id;
		
		if (!id) {
			return false;
		}
		return true;
	}.property('content.id'),
	
	socialEmpty: function () {
		var f, t;
		f = this.get('this.fbaccountsel');
		t = this.get('this.twaccountsel');
		
		f = (f == null || f.length === 0)?null:f;
		t = (t == null || t.length === 0)?null:t;

		if (!f && !t) {
			return true;
		}
		return false;

	}.property('fbaccountsel.[]','twaccountsel.[]'),

	//Observa si hay filtro en selección de destinatarios
	filterEmpty: function () {
		var byEmail, byOpen, byClick, byEx;
		
		byEmail = this.get('content.filterByEmail');
		byOpen = this.get('this.open');
		byClick = this.get('this.click');
		byEx = this.get('this.exclude');
		
		byEmail = (byEmail === '')?null:byEmail;
		byOpen = (byOpen.length === 0)?null:byOpen;
		byClick = (byClick.length === 0)?null:byClick;
		byEx = (byEx.length === 0)?null:byEx;
		
		if (!byEmail && !byOpen && !byClick && !byEx) {
			return true;
		}
		return false;
	}.property('content.filterByEmail','open.[]', 'click.[]', 'exclude.[]'), 
	
	//Observa el contenido del correo
	contentEmpty: function () {
		var mailcontent, preview;
		mailcontent = this.get('this.mailcontent');
		preview = this.get('this.previewData');
		preview = (preview === 'null')?null:preview;
		if (!mailcontent) {
			return true;
		}
		if (!preview) {
			preview = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSI+PC9yZWN0Pjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4=";
		}
		else {
			preview = 'data:image/png;base64,' + preview;
		}
		this.set('contentSummary', preview);
		return false;
		
	}.property('content.mailcontent'),
	
	//Valida si el contenido se hizo en el editor avanzado o es html puro para recrear las url's
	isEditor: function () {
		var t;
		t = this.get('this.type');
		
		if (t === 'Editor') {
			return true;
		}
		return false;
	}.property('content.type'),
	
	//Observa si no se ha configurado google analitycs
	GoogleAnalitycsEmpty: function () {
		var g, c;
		g = this.get('this.linksAnalytics');
		c = this.get('this.campaignName');
		
		g = (g.length === 0)?0:g;
		c = (c === '')?0:c;
		if (!c) {
			this.set('summaryAnalytics', 'inactivo, falta el nombre de la campaña');
			return true;
		}
		else if (!g) {
			this.set('summaryAnalytics', 'inactivo, no se han seleccionado los enlaces');
			return true;
		}
		this.set('summaryAnalytics', 'activo');
		return false;
	}.property('linksAnalytics.[]', 'content.campaignName'),
	
	//Valida si hay links en el contenido, si es asi se habilita
	isGaAvailable: function () {
		if (this.get('id') !== null) {
			if (App.googleAnalyticsLinks !== undefined) {
				return true;
			}
			return false;
		}
		return false;
	}.property('content.mailcontent'),
		
	//Observa si se ha programado el correo
	scheduleEmpty: function () {
		var scheduleDate = this.get('content.scheduleDate');
		if (!scheduleDate) {
			return true;
		}
		
		var date = moment(scheduleDate, "DD-MM-YYYY HH:mm").lang('es');
		var day = getDay(date);
		var month = getMonth(date);
		var year = getYear(date);
		var time = getTime(date);
		
		this.set('scheduleDay', day);
		this.set('scheduleMonth', month);
		this.set('scheduleYear', year);
		this.set('scheduleTime', time);
		return false;
	}.property('content.scheduleDate'),
	
	isTargetBydbases: function () {
		return setFilterValues(this.get('this.dbases'), 'dbaseChecked', this);
	}.property('content.dbases'),
//	
	isTargetByLists: function () {
		return setFilterValues(this.get('this.contactlists'), 'listChecked', this);
	}.property('content.contactlists'),
//	
	isTargetBySegments: function () {
		return setFilterValues(this.get('this.segments'), 'segmentChecked', this);
	}.property('content.segments'),
//		
	isFilterByEmail: function () {
		return setFilterValues(this.get('this.filterByEmail'), 'filterEmailChecked', this);
	}.property('content.filterByEmail'),
//			
	isFilterByOpen: function () {
		return setFilterValues(this.get('this.filterByOpen'), 'filterOpenChecked', this);
	}.property('content.filterByOpen'),
//	
	isFilterByClick: function () {
		return setFilterValues(this.get('this.filterByClick'), 'filterClickChecked', this);
	}.property('content.filterByClick'),
//	
	isFilterByExclude: function () {
		return setFilterValues(this.get('this.filterByExclude'), 'filterExcludeChecked', this);
	}.property('content.filterByExclude'),
	
	//Observa el contenido del correo y una vez este completo habilita el botón para enviarlo
	isMailReadyToSend: function () {
		var name, sender, subject, mailcontent, plainText, totalContacts, scheduleDate;
		
		name = this.get('this.name');
		sender = this.get('this.sender');
		subject = this.get('this.subject');
		mailcontent = this.get('this.mailcontent');
		plainText = this.get('this.plainText');
		totalContacts = this.get('this.totalContacts');
		scheduleDate = this.get('this.scheduleDate');

		name = (name === '')?0:name;
		sender = (sender === '')?0:sender;
		subject = (subject === '')?0:subject;
		mailcontent = (mailcontent === 0)?0:mailcontent;
		plainText = (plainText === '')?0:plainText;
		totalContacts = (totalContacts === '')?0:totalContacts;
		scheduleDate = (scheduleDate === '')?0:scheduleDate;

		if (!name) {
			this.set('summaryMail', 'El campo "Nombre" se encuentra vacío');
			return false;
		}
		else if (!sender) {
			this.set('summaryMail', 'El campo "Remitente" se encuentra vacío');
			return false;
		}
		else if (!subject) {
			this.set('summaryMail', 'El campo "Asunto" se encuentra vacío');
			return false;
		}
		else if (!totalContacts || totalContacts === '0') {
			this.set('summaryMail', 'No hay destinatarios');
			return false;
		}
		else if (!mailcontent) {
			this.set('summaryMail', 'Aún no hay contenido');
			return false;
		}
		else if (!plainText) {
			this.set('summaryMail', 'Aún no hay contenido');
			return false;
		}
		else if (!scheduleDate) {
			this.set('summaryMail', 'Aún no se ha programado la fecha y hora de envío');
			return false;
		}
		
		return true;
	}.property('content.name', 'content.fromName', 'content.fromEmail', 'content.subject', 'content.mailcontent', 'content.plainText', 'content.totalContacts', 'content.scheduleDate'),

	SetAndSave: function (mail) {
//		var filter=/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
			
		
		//else if (!filter.test(mail.get('email'))) {
			//$.gritter.add({title: 'Error', text: 'La dirección de correo de origen ingresada no es válida, por favor verifique la información', sticky: false, time: 3000});
		//}
		
		var senderName = this.get('senderName');
		var senderEmail = this.get('senderEmail');
	
		var sender = (this.get('senderAttr') !== undefined && this.get('senderAttr') !== null && this.get('senderAttr') !== '' ? this.get('senderAttr').id : '');
		
		if (senderName !== undefined && 
				senderName !== '' && 
				senderName !== null && 
				senderEmail !== undefined && 
				senderEmail !== '' &&
				senderEmail !== null) {
			sender = senderEmail + '/' + senderName;
		}
		
		var dbases = getArrayValue(this.get('dbaselist'));
		var contactlists = getArrayValue(this.get('list'));
		var segments = getArrayValue(this.get('segmentlist'));
		var open = getArrayValue(this.get('open'));
		var click = getArrayValue(this.get('click'));
		var exclude = getArrayValue(this.get('exclude'));
		var fbaccounts = getArrayValue(this.get('fbaccountsel'));
		var twaccounts = getArrayValue(this.get('twaccountsel'));
		
		var array = [];
			var obj = this.get('linksAnalytics').toArray();
			for (var i = 0; i < obj.length; i++) {
				array.push(obj[i].name);
			}
		var analitycs = array.toString();

		var value = this.get('scheduleRadio');


		if (value === 'now') {
			mail.set('scheduleDate', value);
		}
		else {
			var date = this.get('date');
			var time = this.get('time');
			var schedule = date + ' ' + time;
			
			mail.set('scheduleDate', schedule);
		}
		
		mail.set('sender', sender);
		mail.set('dbases', dbases);
		mail.set('contactlists', contactlists);
		mail.set('segments', segments);
		mail.set('filterByOpen', open);
		mail.set('filterByClick', click);
		mail.set('filterByExclude', exclude);
		mail.set('googleAnalytics', analitycs);
		mail.set('fbaccounts', fbaccounts);
		mail.set('twaccounts', twaccounts);
		mail.set('fbimagepublication', App.fbimage);
		
		return mail;
		
	},
			
	actions: {
		gotosocial: function(mail, url) {
			if (mail.get('name') === undefined) {
				$.gritter.add({title: 'Error', text: 'No ha ingresado un nombre para el correo, por favor verifique la información', sticky: false, time: 3000});
			} 
			else {
				mail = this.SetAndSave(mail);
				this.handleSavePromise(mail.save(), '', '');
				window.location = mail.get('' + url);
			}
		},
				
		save: function(mail) {
			if (mail.get('name') === undefined) {
				$.gritter.add({title: 'Error', text: 'No ha ingresado un nombre para el correo, por favor verifique la información', sticky: false, time: 3000});
			}
			else {
				mail = this.SetAndSave(mail);
				
				if (!validateSender(mail)) {
					$.gritter.add({title: 'Error', text: 'El remitente ingresado es inválido, por favor verifique la información', sticky: false, time: 3000});
				}
				else {
					this.handleSavePromise(mail.save(), '', 'Se han aplicado los cambios existosamente');
					this.set('isHeaderExpanded', false);
					this.set('isTargetExpanded', false);
					this.set('isGoogleAnalitycsExpanded', false);
					this.set('isScheduleExpanded', false);
					this.set('isSocialExpanded', false);
				}
			}
		},
				
		cancelNewSender: function () {
			this.set('senderName', '');
			this.set('senderEmail', '');
		},
				
		expandHeader: function () {
			if (this.get('this.id') !== null) {
				var sender = setTargetValue(this.get('this.sender'), App.senders);
				this.set('valueSender', sender);
			}
			this.set('senderName', '');
			this.set('senderEmail', '');
			this.set('isHeaderExpanded', true);
			this.set('isTargetExpanded', false);
			this.set('isSocialExpanded', false);
			this.set('isGoogleAnalitycsExpanded', false);
			this.set('isScheduleExpanded', false);
		},
				
		expandTarget: function () {
			if (this.get('this.id') !== null) {
				var arrayDbase = setTargetValues(this.get('this.dbases'), App.dbs);
				var arrayList = setTargetValues(this.get('this.contactlists'), App.lists);
				var arraySegment = setTargetValues(this.get('this.segments'), App.segments);
				
				this.set('databases', arrayDbase);
				this.set('clists', arrayList);
				this.set('csegments', arraySegment);

				var arrayOpen = setTargetValues(this.get('this.filterByOpen'), App.sendByOpen);
				var arrayClick = setTargetValues(this.get('this.filterByClick'), App.sendByClick);
				var arrayExclude = setTargetValues(this.get('this.filterByExclude'), App.excludeContact);

				this.set('fiteropens', arrayOpen);
				this.set('filterclicks', arrayClick);
				this.set('filterexcludes', arrayExclude);
			}
			this.set('isTargetExpanded', true);
			this.set('isHeaderExpanded', false);
			this.set('isSocialExpanded', false);
			this.set('isGoogleAnalitycsExpanded', false);
			this.set('isScheduleExpanded', false);
		},
		
		expandSocial: function () {
			if (this.get('this.id') !== null) {
				var arrayFb = setTargetValues(this.get('this.fbaccounts'), App.fbaccounts);
				var arrayTw = setTargetValues(this.get('this.twaccounts'), App.twaccounts);

				this.set('facebook', arrayFb);
				this.set('twitter', arrayTw);
			}
			
			this.set('isSocialExpanded', true);
			this.set('isTargetExpanded', false);
			this.set('isHeaderExpanded', false);
			this.set('isGoogleAnalitycsExpanded', false);
			this.set('isScheduleExpanded', false);
		},

		expandGA: function () {
			if (this.get('this.id') !== null) {
				if (App.googleAnalyticsLinks !== undefined) {
					var arrayAnalytics = setGoogleAnalyticsValues(this.get('this.googleAnalytics'), App.googleAnalyticsLinks);
					this.set('linksgoogleanalytics', arrayAnalytics);
				}
			}	
			setExpandAttr(this, 'isGoogleAnalitycsExpanded');
			this.set('isHeaderExpanded', false);
			this.set('isTargetExpanded', false);
			this.set('isSocialExpanded', false);
			this.set('isScheduleExpanded', false);
		},
				
		expandSchedule: function () {
			var sch = this.get('scheduleRadio');
			var date = this.get('date');
			var time = this.get('time');
			
			this.set('schTmp', sch);
			this.set('dateTmp', date);
			this.set('timeTmp', time);
			
			this.set('isScheduleExpanded', true);
			this.set('isHeaderExpanded', false);
			this.set('isTargetExpanded', false);
			this.set('isSocialExpanded', false);
			this.set('isGoogleAnalitycsExpanded', false);
		},
		
		discardChanges: function () {
			if (this.get('this.id') !== null) {
				this.get('model').rollback();
				this.set('senderAttr', this.get('valueSender'));
				this.set('fbaccountsel', this.get('facebook'));
				this.set('twaccountsel', this.get('twitter'));
			}
			this.set('senderName', '');
			this.set('senderEmail', '');
			this.set('isSocialExpanded', false);
			this.set('isHeaderExpanded', false);
			this.set('isScheduleExpanded', false);
		},
		
		discardSocial: function () {
			if (this.get('this.id') !== null) {
				this.get('model').rollback();
				this.set('fbaccountsel', this.get('facebook'));
				this.set('twaccountsel', this.get('twitter'));
			}
			
			this.set('isSocialExpanded', false);
			this.set('isHeaderExpanded', false);
			this.set('isScheduleExpanded', false);
		},	
				
		discardTarget: function() {
			if (this.get('this.id') !== null) {
				this.set('dbaselist', this.get('databases'));
				this.set('list', this.get('clists'));
				this.set('segmentlist', this.get('csegments'));
				this.set('open', this.get('fiteropens'));
				this.set('click', this.get('filterclicks'));
				this.set('exclude', this.get('filterexcludes'));
			}
			this.set('isTargetExpanded', false);
		},
				
		discardGoogleAnalytics: function () {
			this.get('model').rollback();
			if (App.googleAnalyticsLinks !== undefined) {
				this.set('linksAnalytics', this.get('linksgoogleanalytics'));
			}	
			this.set('isGoogleAnalitycsExpanded', false);
		},
				
		cleanGoogleAnalytics: function () {
			if (App.googleAnalyticsLinks !== undefined) {
				this.set('linksAnalytics', []);
				this.set('campaignName', '');
				$('.select2').select2('val', '');
			}
		},
				
		cleanSocial: function () {
			if (App.fbaccounts !== undefined) {
				this.set('fbaccountsel', []);
				this.set('fbmessagecontent', '');
				this.set('fbtitlecontent', '');
				this.set('fbdescriptioncontent', '');
				this.set('fbimagepublication', 'post_default.png');
			}
			
			if (App.twaccounts !== undefined) {
				this.set('twaccountsel', []);
				this.set('twpublicationcontent', '');
			}
		},
				
		discardSchedule: function () {
			if (this.get('this.id') !== null) {
				var sche = this.get('schTmp');
				this.set('scheduleRadio', sche);
				
				var dateTmp = this.get('dateTmp');
				this.set('date', dateTmp);
				
			    var timeTmp = this.get('timeTmp');
				this.set('time', timeTmp);
			
				this.set('isScheduleExpanded', false);
			}
		}
	}
});

function validateSender(mail){
	var sender = mail.get('sender');
	
	if (sender === undefined || sender === null || sender === '') {
		return false;
	}
	
	return true;
}

function getArrayValue(value) {
	if( value !== null && value !== undefined ) {
		var array = [];
		var obj = value.toArray();
		for (var i = 0; i < obj.length; i++) {
			array.push(obj[i].id);
		}
		return array.toString();
	}
	return '';
}

function setExpandAttr(self, expand) {
	if(self.get(expand)) {
		self.set(expand, false);
	}
	else {
		self.set(expand, true);
	}
}

function setTargetValues(values, select) {
	var newArray = [];
	if(select !== undefined) {
		var array = values.split(",");
		for (var i = 0; i < select.length; i++) {
			for (var j = 0; j < array.length; j++) {
				array[j] = (typeof array[j] === 'string')?parseInt(array[j]):array[j];
				if (select[i].id === array[j]) {
					newArray.push(select[i]);
				}
			}
		}
	}
	return newArray;
}

function setTargetValue(value, select) {
	var object;
	for (var j = 0; j < select.length; j++) {
		if (select[j].id === value) {
			object = select[j];
		}
	}
	
	return object;
}

function setGoogleAnalyticsValues(values, select) {
	var array = values.split(",");
	var newArray = [];
	for (var i = 0; i < select.length; i++) {
		for (var j = 0; j < array.length; j++) {
			if (select[i].name === array[j]) {
				newArray.push(select[i]);
			}
		}
	}
	return newArray;
}

function setFilterValues(values, checked, self) {
	values = (values === '')?null:values;
	if (values) {
		self.set(checked, 'display: block;');
		return true;
	}	
	self.set(checked, 'display: none;');
	return false;
}

function getTime(date) {
	var hour = '' + date.hour();
	hour = (hour.length === 1)? '0' + hour: hour;
	var minutes = '' + date.minute();
	minutes = (minutes.length === 1)? '0' + minutes: minutes;
		
	var time = hour + ':' + minutes;
	return time;
}

function getMonth(date) {
	var m = date.month() + 1;
	var month = moment('' + m).lang('es').format('MMMM');	
	return month;	
}

function getNumberMonth(date) {
	var m = date.month() + 1;	
	return m;
}

function getDay(date) {
	var day = date.date();
	return day;
}

function getYear(date) {
	var year = date.year();
	return year;
}

App.Select2 = Ember.Select.extend({
	didInsertElement: function() {
		$(".select2").select2({
			
		});
	}
});

App.TimePicker = Ember.TextField.extend({
	didInsertElement: function() {
		$('.time-picker').timepicker({
			showMeridian: false,
			defaultTime: false,
			showInputs: false
		});
	}
});

App.DatePicker = Ember.TextField.extend({
	didInsertElement: function() {
		var now = moment().format('D/M/YYYY');
		$('.date-picker').datetimepicker({
			language: 'es',
			autoclose: true,
			weekStart: false,
			todayBtn: true,
			startDate: now,
			format: "dd/mm/yyyy",
			todayHighlight: true,
			showMeridian: false,
			startView: 2,
			minView: 2,
			forceParse: 0
		});
	}
});

Ember.RadioButton = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id", "checked"],
    click : function() {
        this.set("selection", this.$().val());
		var selection = this.get("selection");
		$("#programmer").hide();
		$("#schedule").val('');
		
		switch (selection) {
			case "now":
				break;

			case "later":
				$("#programmer").show();
				break;
		}
    },
    checked : function() {
		if (this.get("selection") === 'now') {
			this.set("selection", 'later');
		}

		return this.get("value") === this.get("selection");   
    }.property()
});

Ember.RadioButtonTarget = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id", "checked"],
    click : function() {
        $("#db").hide();
		$("#list").hide();
		$("#seg").hide();
		
		this.set('controller.dbaselist', []);
		this.set('controller.list', []);
		this.set('controller.segmentlist', []);
		
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
    }
});

Ember.RadioFilter = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id", "checked"],
    click : function() {
		$("#mail").hide();
		$("#open").hide();
		$("#click").hide();
		$("#exclude").hide();
		
		this.set('controller.filterByEmail', '');
		this.set('controller.open', []);
		this.set('controller.click', []);
		this.set('controller.exclude', []);
	
		$("#sendByMail").val('');
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
    }	
});
