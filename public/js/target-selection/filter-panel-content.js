function FilterPanelContent() {
	this.criteria = '';
	this.selectedItems = [];
	this.ids = [];
}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.setSelectedItems = function(val) {
	this.selectedItems = val;
	for (var i = 0; i < val.length; i++) {
		this.ids.push($(val[i]).attr('data-value'));
	}
	var d = val[0];
	this.criteria = $(d).attr('data-criteria');
};

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
		$(this).remove();
	});
	
	this.content.find('.smg-add-open-filter').on('click', function (e) {
		var url = urlBase + 'api/getopenfilter';
		self.setContentFilter(url);
	});
	
	this.content.find('.smg-add-click-filter').on('click', function (e) {
		var url = urlBase + 'api/getclicksfilter';
		self.setContentFilter(url);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

FilterPanelContent.prototype.setContentFilter = function (url) {
	var data = {
		criteria: this.criteria,
		ids: this.ids
	};

	var dataSource = new DataSourceForSelect(url, data);
	dataSource.findDataSource().then(function() { 
		var source = dataSource.getDataSource();
		this.initializeSelect2(source);
	});
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
						<div class="sgm-filter-content-body sgm-selector-content">\n\
							<input type="hidden" class="select2"/>\n\
						</div>\n\
						</div> \n\
					 </div>');
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

FilterPanelContent.prototype.addContent = function(e) {
	e.preventDefault();
	
	var filterPanelContent = new FilterPanelContent();
	filterPanelContent.setPanelContainer(this.container);
	filterPanelContent.setSelectedItems(this.selectedItems);
	filterPanelContent.createContent();
	
	this.content.find();
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione una opción',
		content: filterPanelContent
	};
		
	this.container.addPanel(config);
};

