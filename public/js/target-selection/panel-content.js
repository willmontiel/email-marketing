function PanelContent() {}

PanelContent.prototype.setPanelContainer = function(container) {
	this.container = container;
};

PanelContent.prototype.initialize = function(panel) {
	var self = this;
	
	this.content.find('.sgm-add-selector-content').on('click', function (e) {
		self.addSelector(e);
//		console.log($(this).attr('data-type'));
		$('.sgm-add-selector-content').removeClass('li-active');
		$(this).addClass('li-active');
		self.initializeSelect2();
	});
	
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addFilter(e);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

PanelContent.prototype.addSelector = function (e) {
	e.preventDefault();
	
	var listPanelContent = new ListPanelContent();
	listPanelContent.setPanelContainer(this.container);
	listPanelContent.createContent();
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione una opción',
		content: listPanelContent
	};
		
	this.container.addPanel(config);
};

PanelContent.prototype.addFilter = function (e) {
	e.preventDefault();
	
	var filterPanelContent = new FilterPanelContent();
	filterPanelContent.setPanelContainer(this.container);
	filterPanelContent.createContent();
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione una opción',
		content: filterPanelContent
	};
		
	this.container.addPanel(config);
};

PanelContent.prototype.initializeSelect2 = function() {
	$(function () {
		$(".select2").select2({
			
		});
	});
};

//---------------------------------------------------------------------------------

function TopPanelContent() {}
TopPanelContent.prototype = new PanelContent;


TopPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-content-selector">\n\
						 <ul>\n\
							<li class="sgm-add-selector-content" data-type="dbases">\n\
								<span class="glyphicon glyphicon-tasks"></span> Bases de datos\n\
							</li>\n\
							<li class="sgm-add-selector-content" data-type="lists">\n\
								<span class="glyphicon glyphicon-list-alt"></span> Listas de contactos\n\
							</li>\n\
							<li class="sgm-add-selector-content" data-type="segments">\n\
								<span class="glyphicon glyphicon-user"></span> Segmentos\n\
							</li>\n\
						 </ul>\n\
					  </div>');
};

//-------------------------------------------------------------------------------------------

function ListPanelContent() {}
ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-target-selector">\n\
						<div class="sgm-selector-content">\n\
							<select class="select2">\n\
								<option value="uno">Acción uno</option>\n\
								<option value="dos">Acción dos</option>\n\
							</select>\n\
						<div class="sgm-add-filter-content sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>\n\
						</div> \n\
					 </div>');
};

//--------------------------------------------------------------------------------------------

function FilterPanelContent() {}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-filter-selector">\n\
						<div class="sgm-filter-content">\n\
							<div class="sgm-filter-content-header">\n\
								<span class="filter-icon glyphicon glyphicon-hand-up"></span>\n\
								<span class="filter-icon glyphicon glyphicon-eye-open"></span>\n\
								<span class="filter-icon glyphicon glyphicon-envelope"></span>\n\
								<span class="filter-icon glyphicon glyphicon-comment"></span>\n\
								<span class="filter-icon glyphicon glyphicon-globe"></span>\n\
								<span class="filter-icon glyphicon glyphicon-star"></span>\n\
							</div>\n\
						</div>\n\
						<div class="sgm-add-filter-content sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>\n\
						</div> \n\
					 </div>');
};