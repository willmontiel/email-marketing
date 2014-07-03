function ListPanelContent() {}

ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.setContentCriteria = function(criteria) {
	this.criteria = criteria;
};

ListPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
	});
	
	var url = self.getUrlForDataSource();
	var dataSource = new DataSourceForSelect(url);
	var source;

	dataSource.findDataSource().then(function() { 
		source = dataSource.getDataSource();
//		self.insertSource(source);
		self.initializeSelect2(source);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

//ListPanelContent.prototype.addContent = function (e) {
//	e.preventDefault();
//	
//	var filterPanelContent = new FilterPanelContent();
//	filterPanelContent.setPanelContainer(this.container);
//	filterPanelContent.createContent();
//	
//	var config = {
//		sticky: false, 
//		leftArrow: true, 
//		title: 'Seleccione una opción',
//		content: filterPanelContent
//	};
//		
//	this.container.addPanel(config);
//};

ListPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-target-selector">\n\
						<div class="sgm-selector-content">\n\
							<input type="hidden" class="select2"/>\n\
						</div>\n\
						<div class="sgm-add-filter-content sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>\n\
						</div> \n\
					 </div>');
};

ListPanelContent.prototype.getUrlForDataSource = function() {
	var url = urlBase;
	switch (this.criteria) {
		case 'dbases':
			url += "api/getdbases";
			break;
			
		case 'lists':
			url += "api/getcontactlists";
			break;
			
		case 'segments':
			url += "api/getsegments";
			break;
	}
	
	return url;
};

ListPanelContent.prototype.initializeSelect2 = function(data) {
	$(".select2").select2({
		 data: data,
		 placeholder: "Selecciona una opción",
	});
};

//ListPanelContent.prototype.insertSource = function(source) {
//	var options = '';
//	for(var i = 0; i < source.length; i++) {
//		var dbase = source[i];
//		options += '<option value="' + dbase.idDbase + '">' + dbase.name + '</option>';
//	}
//	
//	var select = $('<select class="select2">\n\
//						' + options + '\n\
//					</select');
//	
//	this.content.find('.sgm-selector-content').append(select);
//};