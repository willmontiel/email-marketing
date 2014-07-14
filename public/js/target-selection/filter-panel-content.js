function FilterPanelContent() {
	this.oldCriteria = {
		type: 'filter-panel',
		serialization: {items: []}
	};
	this.selectedItems = [];
	this.ids = [];
}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	
	this.content.find('.smg-add-open-filter').on('click', function (e) {
		var filterContent = new FilterOpenContent();
		filterContent.setModel(self.model);
		filterContent.createContent();
		filterContent.initialize();
		this.content.find('.sgm-filter-content-body').append(filterContent.getContent());
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

FilterPanelContent.prototype.initializeSelect2 = function(data) {
	var self = this;
	
	var select = this.content.find('.select2');
	
	select.select2({
		data: data,
		placeholder: "Selecciona una opción"
	});
	
	select.on("change", function(e) { 
		e.preventDefault();
		self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
		self.selectedValue = e.val;
	});
};

FilterPanelContent.prototype.setSelectedItems = function(val) {
	this.selectedItems = val;
	for (var i = 0; i < val.length; i++) {
		this.ids.push($(val[i]).attr('data-value'));
	}
	var d = val[0];
	this.criteria = $(d).attr('data-criteria');
};


FilterPanelContent.prototype.setContentFilter = function (url) {
	var self = this;
	var data = {
		criteria: this.criteria,
		ids: this.ids
	};

	var dataSource = new DataSourceForSelect(url, data);
	dataSource.findDataSource().then(function() { 
		var source = dataSource.getDataSource();
		self.initializeSelect2(source);
	});
};

FilterPanelContent.prototype.refreshTotalContacts = function() {
	var ids = new Array();
	for (var i = 0; i < this.selectedItems.length; i++) {
		ids.push($(this.selectedItems[i]).attr('data-value'));
	}
	
	var self = this;
	var data = {
		criteria: this.criteria,
		ids: ids
	};
	
	console.log(data);
	
	var url = urlBase + 'api/gettotalcontacts';
	
	var dataSource = new DataSourceForSelect(url, data);
	dataSource.findDataSource().then(function() { 
		var total = dataSource.getDataSource();
		$('.sgm-panel-contacts-space').empty();
		$('.sgm-panel-contacts-space').append('Contactos aproximados: ' + total.totalContacts);
	});
};