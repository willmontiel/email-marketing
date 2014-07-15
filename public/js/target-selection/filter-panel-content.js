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
		
		var filterContent = new FilterOpenContent();
		filterContent.setModel(self.model);
		filterContent.createContent();
		
		filterContent.createSelect().then(function() { 
			var select = filterContent.getSelect();
			
			select.on("change", function(e) { 
				e.preventDefault();
				self.content.find('.sgm-filter-select-button-add').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
				self.selectedValue = e.val;

				self.updateObject();
			});
			
			console.log(select);
			self.content.find('.sgm-filter-content-body').append(select);
		});
	});
	
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
		$(this).remove();
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

FilterPanelContent.prototype.createContent = function () {
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

FilterPanelContent.prototype.serialize = function(obj) {
	this.oldCriteria = obj;
	if (obj.serialization.items !== null) {
		
	}
};

FilterPanelContent.prototype.createNextPanel = function () {
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