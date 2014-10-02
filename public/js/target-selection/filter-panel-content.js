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
		var container = self.setValues('mail-sent', this);
		var filter = new FilterMailContent();
		self.createFilter(filter, container);
		
		self.content.find('.smg-add-open-filter').remove();
		self.content.find('.smg-add-click-filter').remove();
	});
	
	this.content.find('.smg-add-open-filter').on('click', function (e) {
		var container = self.setValues('mail-open', this);
		var filter = new FilterMailContent();
		self.createFilter(filter, container);
		
		self.content.find('.smg-add-sent-filter').remove();
		self.content.find('.smg-add-click-filter').remove();
	});
	
	this.content.find('.smg-add-click-filter').on('click', function (e) {
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
			if (self.content.find('.sgm-add-neg').length == 0) {
				var active = (self.negation ? 'sgm-neg-active' :'');
				var addNeg = $('<div class="sgm-tooltip sgm-add-neg ' + active + '" data-toggle="tooltip" data-placement="right" title="La condición cambiará a ser lo contrario">Not!</div>');
				self.content.find('.sgm-content-negation-filter').append(addNeg);
				self.updateNegationValue();
				self.initializeTooltip();
			}
		}
		
		select.on("change", function(e) { 
			e.preventDefault();
			self.selectedValue = e.val;
			self.updateObject();
			
			var addFilter = $('<div class="sgm-tooltip sgm-add-panel" data-toggle="tooltip" data-placement="right" title="Agregar otro filtro"><span class="glyphicon glyphicon-filter"></span></div>');
			self.content.find('.sgm-content-add-filter').append(addFilter);
			
			self.initializeTooltip();
			addFilter.on('click', function (e) {
				self.createNextPanel(e);
				$(this).tooltip('hide');
				$(this).remove();
			});
			
			if (self.content.find('.sgm-add-neg').length == 0) {
				var addNeg = $('<div class="sgm-tooltip sgm-add-neg" data-toggle="tooltip" data-placement="right" title="La condición cambiará a ser lo contrario">Not!</div>');
				self.content.find('.sgm-content-negation-filter').append(addNeg);

				self.updateNegationValue();
				self.initializeTooltip();
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
							  <div class="sgm-filter-content-header"></div>\n\
							  <div class="sgm-filter-content-selector">\n\
								  <ul>\n\
									  <li class="smg-add-sent-filter">\n\
										<div class="smg-filter filter-icon">\n\
											<div class="sgm-mail-sent-icon"></div>\n\
										</div>\n\
										Enviar a contactos que hayan recibido un correo\n\
									  </li>\n\
									  <li class="smg-add-open-filter">\n\
										 <div class="smg-filter filter-icon">\n\
											<div class="sgm-mail-open-icon"></div>\n\
										 </div>\n\
										 Enviar a contactos que hayan abierto un correo\n\
									  </li>\n\
									  <li class="smg-add-click-filter">\n\
										 <div class="smg-filter filter-icon">\n\
											<div class="sgm-mail-click-icon"></div>\n\
										 </div>\n\
										 Enviar a contactos que hayan hecho clic un enlace\n\
									  </li>\n\
								  </ul>\n\
							  </div>\n\
						  </div>\n\
						  <div class="sgm-filter-content-body">\n\
							   <div class="sgm-filter-content-select-mail"></div>\n\
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
				var active = this.content.find('.smg-add-sent-filter');
				var container = this.setValues(type, active);
				var filter = new FilterMailContent();
				this.createFilter(filter, container);
				this.content.find('.smg-add-open-filter').remove();
				this.content.find('.smg-add-click-filter').remove();
				break;
				
			case 'mail-open':
				var active = this.content.find('.smg-add-open-filter');
				var container = this.setValues(type, active);
				var filter = new FilterMailContent();
				this.createFilter(filter, container);
				this.content.find('.smg-add-sent-filter').remove();
				this.content.find('.smg-add-click-filter').remove();
				break;
				
			case 'click':
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

FilterPanelContent.prototype.initializeTooltip = function() {	
	$('.sgm-tooltip').tooltip();
};