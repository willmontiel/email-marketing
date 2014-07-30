function FilterPanelContent() {
	this.oldCriteria = {
		type: 'filter-panel',
		serialization: {
			type: null,
			idMail: null,
			items: null,
			negation: false
		}
	};
	
	this.selectedValue = null;
	this.mailSelected = null;
	this.type = null;
	this.negation = false;
}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.setValues = function(type, himself) {
	this.type = type;
	
	var mtitle = this.content.find('.sgm-mail-title');
	var ctitle = this.content.find('.sgm-click-title');
	
	mtitle.empty();
	ctitle.empty();
	
	if (this.mtitle !== '') {
		mtitle.append('<div class="sgm-title-box">' + this.mtitle + '</div>');
	}
	
	if (this.ctitle !== '') {
		ctitle.append('<div class="sgm-title-box">' + this.ctitle + '</div>');
	}
	
	this.content.find('.smg-filter').removeClass('sgm-filter-active');
	$(himself).addClass('sgm-filter-active');
	
	var mailContainer = this.content.find('.sgm-filter-content-select-mail');
	mailContainer.empty();
	
	var clickContainer = this.content.find('.sgm-filter-content-select-click');
	clickContainer.empty();
	
	return mailContainer;
};

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	
	this.content.find('.smg-add-sent-filter').on('click', function (e) {
		self.ctitle = '';
		self.mtitle = 'Enviar a contactos que hayan recibido el siguiente correo';
		var container = self.setValues('mail-sent', this);
		var filter = new FilterMailContent();
		self.createFilter(filter, container);
		
		self.content.find('.smg-add-open-filter').remove();
		self.content.find('.smg-add-click-filter').remove();
	});
	
	this.content.find('.smg-add-open-filter').on('click', function (e) {
		self.ctitle = '';
		self.mtitle = 'Enviar a contactos que hayan abierto el siguiente correo';
		var container = self.setValues('mail-open', this);
		var filter = new FilterMailContent();
		self.createFilter(filter, container);
		
		self.content.find('.smg-add-sent-filter').remove();
		self.content.find('.smg-add-click-filter').remove();
	});
	
	this.content.find('.smg-add-click-filter').on('click', function (e) {
		self.mtitle = '';
		self.ctitle = 'Enviar a contactos que hayan hecho click en el siguiente enlace';
		var container = self.setValues('click', this);
		var filter = new FilterClickContent();
		self.createClickFilter(filter, container);
		
		self.content.find('.smg-add-sent-filter').remove();
		self.content.find('.smg-add-open-filter').remove();
	});
	
//	this.content.find('.smg-add-field-filter').on('click', function (e) {
//		var container = self.setValues('field', this);
//		var filter = new FilterFieldContent();
//		self.createFilter(filter);
//	});
	
	panel.find('.sgm-panel-content').append(this.content);
	this.serialize();
};

FilterPanelContent.prototype.createFilter = function(obj, container) {
	var self = this; 
	
	obj.setModel(this.model);
	obj.setMailSelected(this.mailSelected);
	obj.setContainer(container);
	obj.createContent();

	obj.createSelect().then(function() { 
		var select = obj.getSelect();
		
		if (self.selectedValue !== null) {
			select.select2('val', self.selectedValue);
			var active = (self.negation ? 'sgm-neg-active' :'');
			var addNeg = $('<div class="sgm-add-neg ' + active + '">Not!</div>');
			self.content.find('.sgm-content-negation-filter').append(addNeg);
			
			self.updateNegationValue();
		}
		
		select.on("change", function(e) { 
			e.preventDefault();
			self.selectedValue = e.val;
			self.updateObject();
			
			var addFilter = $('<div class="sgm-add-panel"><span class="glyphicon glyphicon-filter"></span></div>');
			self.content.find('.sgm-content-add-filter').append(addFilter);
			
			self.content.find('.sgm-add-panel').on('click', function (e) {
				self.createNextPanel(e);
				$(this).remove();
			});
			
			console.log(self.content.find('.sgm-add-neg'));
			if (!self.content.find('.sgm-add-neg')) {
				var addNeg = $('<div class="sgm-add-neg">Not!</div>');
				self.content.find('.sgm-content-negation-filter').append(addNeg);

				self.updateNegationValue();
			}
			
			self.model.refreshTotalContacts();
		});
	});
};

FilterPanelContent.prototype.updateNegationValue = function() {
	var self = this;
	this.content.find('.sgm-add-neg').on('click', function (e) {
		if ($(this).hasClass("sgm-neg-active")) {
			$(this).removeClass('sgm-neg-active');
			self.negation = false;
			self.updateObject();
		}
		else {
			$(this).addClass('sgm-neg-active');
			self.negation = true;
			self.updateObject();
		}
		
		self.model.refreshTotalContacts();
	});
};

FilterPanelContent.prototype.createClickFilter = function(obj, container) {
	var self = this; 
//	this.mailSelected = null;
	
	obj.setModel(this.model);
	obj.setContainer(container);
	obj.createContent();

	obj.createSelectForMails().then(function() { 
		var select = obj.getSelect();
		
		if (self.mailSelected !== null) {
			select.select2('val', self.mailSelected);
		}
		
		select.on("change", function(e) { 
			e.preventDefault();
			self.mailSelected = e.val;
			self.updateObject();
			var container = self.content.find('.sgm-filter-content-select-click');
			container.empty();
			var filter = new FilterClickContent();
			self.createFilter(filter, container);
		});
	});
};

FilterPanelContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-selector">\n\
						  <div class="sgm-filter-content">\n\
							  <div class="sgm-filter-content-header">\n\
								  <div class="smg-filter smg-add-sent-filter filter-icon">\n\
									  <span class="glyphicon glyphicon-envelope"></span>\n\
								  </div>\n\
								  <div class="smg-filter smg-add-open-filter filter-icon">\n\
									  <span class="glyphicon glyphicon-eye-open"></span>\n\
								  </div>\n\
								  <div class="smg-filter smg-add-click-filter filter-icon">\n\
									  <span class="glyphicon glyphicon-hand-up"></span>\n\
								  </div>\n\
							  </div>\n\
						  </div>\n\
						  <div class="sgm-filter-content-body">\n\
							   <div class="sgm-mail-title"></div>\n\
							   <div class="sgm-filter-content-select-mail"></div>\n\
							   <div class="sgm-click-title"></div>\n\
							   <div class="sgm-filter-content-select-click"></div>\n\
							   <div class="sgm-content-add-filter"></div>\n\
							   <div class="sgm-content-negation-filter"></div>\n\
						  </div>\n\
					  </div>');
};

FilterPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.serialization.items !== null) {
		this.oldCriteria = this.serializerObject;
		var type = this.serializerObject.serialization.type;
		this.selectedValue = this.serializerObject.serialization.items;
		this.negation = this.serializerObject.serialization.negation;
		
		switch (type) {
			case 'mail-sent':
				this.mtitle = 'Enviar a contactos que hayan recibido el siguiente correo';
				this.ctitle = '';
				var active = this.content.find('.smg-add-sent-filter');
				var container = this.setValues(type, active);
				var filter = new FilterMailContent();
				this.createFilter(filter, container);
				this.content.find('.smg-add-open-filter').remove();
				this.content.find('.smg-add-click-filter').remove();
				break;
				
			case 'mail-open':
				this.mtitle = 'Enviar a contactos que hayan abierto el siguiente correo';
				this.ctitle = '';
				var active = this.content.find('.smg-add-open-filter');
				var container = this.setValues(type, active);
				var filter = new FilterMailContent();
				this.createFilter(filter, container);
				this.content.find('.smg-add-sent-filter').remove();
				this.content.find('.smg-add-click-filter').remove();
				break;
				
			case 'click':
				this.mtitle = '';
				this.ctitle = 'Enviar a contactos que hayan hecho click en el siguiente enlace';
				var active = this.content.find('.smg-add-click-filter');
				this.mailSelected = this.serializerObject.serialization.idMail;
				var container = this.setValues(type, active);
				var filter = new FilterClickContent();
				this.createClickFilter(filter, container);
				
				var container = this.content.find('.sgm-filter-content-select-click');
				container.empty();
				var filter = new FilterClickContent();
				this.createFilter(filter, container);
				
				this.content.find('.smg-add-sent-filter').remove();
				this.content.find('.smg-add-open-filter').remove();
				break;
		}
		
	}
};

FilterPanelContent.prototype.createNextPanel = function (e) {
	e.preventDefault();
	this.model.createFilterPanel();
};

FilterPanelContent.prototype.updateObject = function () {
	this.newCriteria = {
		type: 'filter-panel',
		serialization: {
			type: this.type,
			idMail: this.mailSelected,
			items: this.selectedValue,
			negation: this.negation
		}
	};
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.oldCriteria = this.newCriteria;
};