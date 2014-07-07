function FilterPanelContent() {
	this.selectedValue = '';
}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.setSelectedValue = function(val) {
	this.criteriaVal = val;
};

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
		$(this).remove();
	});
	
	this.content.find('.smg-add-click-filter').on('click', function (e) {
		self.addSelectContent(e);
		
//		var url = self.getUrlForDataSource();
//		url += '/' + e.val;
		
//		var dataSource = new DataSourceForSelect(url);
		var source = [
			{id: 1, text: 'Algo 1'},
			{id: 2, text: 'Algo 2'},
			{id: 3, text: 'Algo 3'}
		];
		
//		dataSource.findDataSource().then(function() { 
//			source = dataSource.getDataSource();
			self.initializeSelect2(source);
//		});
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

FilterPanelContent.prototype.addSelectContent = function (e) {
	e.preventDefault();
};

FilterPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-filter-selector">\n\
						<div class="sgm-filter-content">\n\
							<div class="sgm-filter-content-header">\n\
								<span class="smg-add-click-filter filter-icon glyphicon glyphicon-hand-up"></span>\n\
								<span class="filter-icon glyphicon glyphicon-eye-open"></span>\n\
								<span class="filter-icon glyphicon glyphicon-envelope"></span>\n\
								<span class="filter-icon glyphicon glyphicon-comment"></span>\n\
								<span class="filter-icon glyphicon glyphicon-globe"></span>\n\
								<span class="filter-icon glyphicon glyphicon-star"></span>\n\
							</div>\n\
						</div>\n\
						<div class="sgm-filter-content-body sgm-selector-content">\n\
							<input type="hidden" class="select2"/>\n\
						</div>\n\
						<div class="sgm-add-filter-content"></div>\n\
						</div> \n\
					 </div>');
};

FilterPanelContent.prototype.getUrlForDataSource = function() {
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

FilterPanelContent.prototype.initializeSelect2 = function(data) {
	console.log('Initializing');
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

