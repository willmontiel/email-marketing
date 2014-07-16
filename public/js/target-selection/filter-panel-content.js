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
		self.content.find('.sgm-filter-content-body').empty();
		var openFilter = new FilterOpenContent();
		self.createFilter(openFilter);
	});
	
	this.content.find('.sgm-filter-select-button-add').on('click', function (e) {
		self.createNextPanel(e);
		$(this).remove();
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
			self.content.find('.sgm-filter-select-button-add').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
			self.selectedValue = e.val;
			self.updateObject();
		});
	});
};

FilterPanelContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-selector">\n\
						  <div class="sgm-filter-content">\n\
							  <div class="sgm-filter-content-header">\n\
								  <span class="smg-add-click-filter filter-icon glyphicon glyphicon-hand-up"></span>\n\
								  <span class="smg-add-open-filter filter-icon glyphicon glyphicon-eye-open"></span>\n\
								  <span class="filter-icon glyphicon glyphicon-envelope"></span>\n\
								  <span class="filter-icon glyphicon glyphicon-comment"></span>\n\
								  <span class="filter-icon glyphicon glyphicon-globe"></span>\n\
								  <span class="filter-icon glyphicon glyphicon-star"></span>\n\
							  </div>\n\
						  </div>\n\
						  <div class="sgm-filter-content-body"></div>\n\
						  <div class="sgm-filter-select-button-add"></div>\n\
					  </div>');
};

FilterPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.serialization.items !== null) {
		this.oldCriteria = this.serializerObject;
		this.type = this.serializerObject.serialization.type;
		
		switch (this.type) {
			case 'open':
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