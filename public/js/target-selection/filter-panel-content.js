function FilterPanelContent() {
	this.oldCriteria = {
		type: 'filter-panel',
		serialization: {
			type: null,
			idMail: null,
			items: null
		}
	};
	
	this.selectedValue = null;
	this.mailSelected = null;
	this.type = null;
}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	
	this.content.find('.smg-add-sent-filter').on('click', function (e) {
		self.type = 'mail-sent';
		self.content.find('.smg-filter').removeClass('sgm-filter-active');
		$(this).addClass('sgm-filter-active');
		self.content.find('.sgm-filter-content-body').empty();
		var container = self.content.find('.sgm-filter-content-select-mail');
		var filter = new FilterMailContent();
		self.createFilter(filter, container);
	});
	
	this.content.find('.smg-add-open-filter').on('click', function (e) {
		self.type = 'mail-open';
		self.content.find('.smg-filter').removeClass('sgm-filter-active');
		$(this).addClass('sgm-filter-active');
		self.content.find('.sgm-filter-content-body').empty();
		var container = self.content.find('.sgm-filter-content-select-mail');
		var filter = new FilterMailContent();
		self.createFilter(filter, container);
	});
	
	this.content.find('.smg-add-click-filter').on('click', function (e) {
		self.type = 'click';
		self.content.find('.smg-filter').removeClass('sgm-filter-active');
		$(this).addClass('sgm-filter-active');
		self.content.find('.sgm-filter-content-body').empty();
		var container = self.content.find('.sgm-filter-content-select-mail');
		var filter = new FilterClickContent();
		self.createClickFilter(filter, container);
	});
	
	this.content.find('.smg-add-field-filter').on('click', function (e) {
		self.type = 'field';
		self.content.find('.smg-filter').removeClass('sgm-filter-active');
		$(this).addClass('sgm-filter-active');
		self.content.find('.sgm-filter-content-body').empty();
		var filter = new FilterFieldContent();
//		self.createFilter(filter);
	});
	
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
		}
		
		select.on("change", function(e) { 
			e.preventDefault();
			self.selectedValue = e.val;
			self.updateObject();
			
			var content = $('<div class="sgm-add-panel"><span class="glyphicon glyphicon-filter"></span></div>');
			self.content.find('.sgm-filter-content-footer').append(content);
			
			self.content.find('.sgm-add-panel').on('click', function (e) {
				self.createNextPanel(e);
				$(this).remove();
			});
			
			self.model.refreshTotalContacts();
		});
	});
};

FilterPanelContent.prototype.createClickFilter = function(obj, container) {
	var self = this; 
	
	obj.setModel(this.model);
	obj.setContainer(container);
	obj.createContent();

	obj.createSelectForMails().then(function() { 
		var select = obj.getSelect();
		
		if (self.selectedValue !== null) {
			select.select2('val', self.selectedValue);
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
								   <div class="smg-filter smg-add-field-filter filter-icon">\n\
									  <span class="glyphicon glyphicon-text-width"></span>\n\
								  </div>\n\
							  </div>\n\
						  </div>\n\
						  <div class="sgm-filter-content-body">\n\
							   <div class="sgm-filter-content-select-mail"></div>\n\
							   <div class="sgm-filter-content-select-click"></div>\n\
						  </div>\n\
						  <div class="sgm-filter-content-footer"></div>\n\
					  </div>');
};

FilterPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.serialization.items !== null) {
		this.oldCriteria = this.serializerObject;
		this.type = this.serializerObject.serialization.type;
		this.selectedValue = this.serializerObject.serialization.items;
		
		switch (this.type) {
			case 'mail-open':
				this.content.find('.sgm-filter-content-body').empty();
				var openFilter = new FilterMailContent();
				this.createFilter(openFilter);
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
			items: this.selectedValue
		}
	};
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.oldCriteria = this.newCriteria;
};