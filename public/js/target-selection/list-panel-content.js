function ListPanelContent() {
	this.selectedValue = '';
}

ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
		$(this).remove();
	});
	
	var url = self.getUrlForDataSource();
	var dataSource = new DataSourceForSelect(url);
	var source;

	dataSource.findDataSource().then(function() { 
		source = dataSource.getDataSource();
		self.initializeSelect2(source);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

ListPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-target-selector">\n\
						<div class="sgm-selector-content">\n\
							<input type="hidden" class="select2"/>\n\
						</div>\n\
						<div class="sgm-add-filter-content"></div>\n\
						</div> \n\
					 </div>');
};

ListPanelContent.prototype.getUrlForDataSource = function() {
	var url = urlBase;
	switch (this.criteria) {
		case 'dbases':
			url += "api/getdbases";
			break;
			
		case 'contactlists':
			url += "api/getcontactlists";
			break;
			
		case 'segments':
			url += "api/getsegments";
			break;
	}
	
	return url;
};

ListPanelContent.prototype.initializeSelect2 = function(data) {
	var self = this;
	var results = {
		more: false,
		results: data
	};
	
	var select = this.content.find('.select2');
	select.select2({
//		multiple: true,
		data: results,
		placeholder: "Selecciona una opción"
	});
	
	select.on("change", function(e) { 
		e.preventDefault();
		self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
		self.selectedValue = e.val;
	});
};

ListPanelContent.prototype.addContent = function(e) {
	e.preventDefault();
	
	var filterPanelContent = new FilterPanelContent();
	filterPanelContent.setPanelContainer(this.container);
	filterPanelContent.setContentCriteria(this.criteria);
	filterPanelContent.setSelectedValue(this.selectedValue);
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