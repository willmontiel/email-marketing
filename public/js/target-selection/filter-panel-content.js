function FilterPanelContent() {
	this.oldCriteria = {
		type: 'filter-panel',
		serialization: {
			type: null,
			items: null
		}
	};
	
	this.selectedValue = null;
	this.type = null;
}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	
	this.content.find('.smg-add-open-filter').on('click', function (e) {
		self.type = 'open';
		$('.smg-filter').removeClass('sgm-filter-active');
		$(this).addClass('sgm-filter-active');
		self.content.find('.sgm-filter-content-body').empty();
		var openFilter = new FilterOpenContent();
		self.createFilter(openFilter);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
	this.serialize();
};

FilterPanelContent.prototype.createFilter = function(obj) {
	var self = this; 
	
	obj.setModel(this.model);
	obj.setParent(this.content);
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

FilterPanelContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-selector">\n\
						  <div class="sgm-filter-content">\n\
							  <div class="sgm-filter-content-header">\n\
								  <span class="smg-filter smg-add-click-filter filter-icon glyphicon glyphicon-hand-up"></span>\n\
								  <span class="smg-filter smg-add-open-filter filter-icon glyphicon glyphicon-eye-open"></span>\n\
							  </div>\n\
						  </div>\n\
						  <div class="sgm-filter-content-body"></div>\n\
						  <div class="sgm-filter-content-footer"></div>\n\
					  </div>');
};

FilterPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.serialization.items !== null) {
		this.oldCriteria = this.serializerObject;
		this.type = this.serializerObject.serialization.type;
		this.selectedValue = this.serializerObject.serialization.items;
		
		switch (this.type) {
			case 'open-view':
				this.content.find('.sgm-filter-content-body').empty();
				var openFilter = new FilterOpenContent();
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
			items: this.selectedValue
		}
	};
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.oldCriteria = this.newCriteria;
};