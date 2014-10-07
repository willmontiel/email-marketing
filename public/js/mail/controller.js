/* 
 * Index: Ruta y Controlador
 */

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

App.IndexController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	senderName: '',
	senderEmail: '',
	date: '',
	time: '',
	senderAttr: [],
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
			var fbaccounts = setTargetValues(this.get('this.fbaccounts'), App.fbaccounts);
			var twaccounts = setTargetValues(this.get('this.twaccounts'), App.twaccounts);
			
			this.set('fbaccountsel', fbaccounts);
			this.set('twaccountsel', twaccounts);
			
			var target = this.get('content.target');
			if (target !== '') {
				App.serializerObject = JSON.parse(target);
			}
			createSelectorTarget();
			
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
			
			if (scheduleDate) {
				var date = moment(scheduleDate, "DD-MM-YYYY HH:mm").lang('es');
				var day = getDay(date);
				var month = getNumberMonth(date);
				var year = getYear(date);
				var d = day + '/' + month + '/' + year;
				this.set('date', d);

				var time = getTime(date);
				this.set('time', time);
			}
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
	
	//Refresca el modelo
	refreshAttachment: function() {
		var t = this;
		Ember.$.getJSON(urlComplete + '/' + idMail).then(function(data) {
//			t.set('content', data.mails);
//			t.set('model', data.mails);
			var m = t.get('model');
			
			m.set('attachment', data.mails.attachment);
			m.set('attachmentsName', data.mails.attachmentsName);
		});
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
		
//		this.checkFormsStatus(this.get('this.mailcontent'), this.get('this.dbases'), this.get('this.contactlists'), this.get('this.segments'));

		var t;
		t = this.get('content.target');
		
		t = (t === '') ? null : t;
		
		if (!t) {
			return true;
		}
		
		this.set('criteriaType', App.model.getCriteriaType());
		this.set('selectedValue', App.model.getSelectedValues());
		
		var v = App.model.getTotalSelectedValues() - 1;
		var total = (v < 1 ? '' : 'y ' + v + ' más');
		this.set('totalSelectedValues', total);
		
		var f = App.model.getTotalFilters();
		var filter = (f > 0 ? 'Filtrado' : 'Sin filtrar');
		this.set('totalFilters', filter);
		return false;
	}.property('content.target'), 
	
	//Si hay un id de correo la seleccion de contenido (editor, plantillas, html, importar contenido) se habilita de lo contrario no
	isContentAvailable: function () {
		idMail = this.get('content.id');
		idMail = (idMail === '')?null:idMail;
		
		if (!idMail) {
			return false;
		}
		return true;
	}.property('content.id'),
	
	attachmentEmpty: function () {
		var att = this.get('this.attachment');
		
		if (!att || att == 0) {
			return true;
		}
		return false;
	}.property('this.attachment'),
	
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
		
		var m = getNumberMonth(date);
		var d = day + '/' + m + '/' + year;
		this.set('date', d);

		this.set('time', time);
		
		return false;
	}.property('content.scheduleDate'),
	
	isTargetBydbases: function () {
		return setFilterValues(this.get('this.dbases'), 'dbaseChecked', this);
	}.property('content.dbases'),

	isTargetByLists: function () {
		return setFilterValues(this.get('this.contactlists'), 'listChecked', this);
	}.property('content.contactlists'),
	
	isTargetBySegments: function () {
		return setFilterValues(this.get('this.segments'), 'segmentChecked', this);
	}.property('content.segments'),
		
	isFilterByEmail: function () {
		return setFilterValues(this.get('this.filterByEmail'), 'filterEmailChecked', this);
	}.property('content.filterByEmail'),
			
	isFilterByOpen: function () {
		return setFilterValues(this.get('this.filterByOpen'), 'filterOpenChecked', this);
	}.property('content.filterByOpen'),
	
	isFilterByClick: function () {
		return setFilterValues(this.get('this.filterByClick'), 'filterClickChecked', this);
	}.property('content.filterByClick'),
	
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
		else if (value) {
			var date = this.get('date');
			var time = this.get('time');
			var schedule = date + ' ' + time;
			
			mail.set('scheduleDate', schedule);
		}
		
		var target = null;
		var totalContacts = 0;
		
		if (App.model !== undefined) {
			var model = App.model.getModel();
			App.serializerObject = model;
			target = JSON.stringify(model);
			totalContacts = App.model.getTotalContacts();
		}
		
		mail.set('sender', sender);
		mail.set('target', target);
		mail.set('totalContacts', totalContacts);
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
			if (mail === undefined) {
				mail = this.content;
			}
	
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
					this.set('isAttachementExpanded', false);
					this.set('isScheduleExpanded', false);
					this.set('isSocialExpanded', false);
				}
			}
		},
			
		contractingAttachment: function () {
			this.set('isAttachementExpanded', false);
		},
				
		refreshModel: function () {
			this.set('isAttachementExpanded', false);
			App.controller = this;
//			this.refreshRecords();
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
				var target = this.get('content.target');
				this.set('oldTarget', target);
			}
			this.set('isTargetExpanded', true);
			this.set('isHeaderExpanded', false);
			this.set('isSocialExpanded', false);
			this.set('isGoogleAnalitycsExpanded', false);
			this.set('isScheduleExpanded', false);
		},
				
		expandAttachment: function () {
			this.set('isTargetExpanded', false);
			this.set('isHeaderExpanded', false);
			this.set('isSocialExpanded', false);
			this.set('isGoogleAnalitycsExpanded', false);
			this.set('isScheduleExpanded', false);
			this.set('isAttachementExpanded', true);
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
				if (this.get('oldTarget') !== '') {
					App.serializerObject = JSON.parse(this.get('oldTarget'));
				}
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
			}
			this.set('isScheduleExpanded', false);
		}
	}
});