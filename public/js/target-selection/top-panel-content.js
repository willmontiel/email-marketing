function TopPanelContent() {}

TopPanelContent.prototype = new PanelContent;

TopPanelContent.prototype.initialize = function() {
	console.log('Initializing from TopPanelContent');
	var self = this;
	
	this.content.find('.sgm-add-selector-content').on('click', function (e) {
		self.addSelector(e);
		
		var criteria = $(this).attr('data-type');
		
		$('.sgm-add-selector-content').removeClass('li-active');
		$(this).addClass('li-active');
		
		var url = self.getUrlForDataSource(criteria);
		
		var dataSource = new DataSourceForSelect(url);
		var source = dataSource.getDataSource();
	
		self.processTarget(source);
		
		self.initializeSelect2();
	});
};

TopPanelContent.prototype.getUrlForDataSource = function(criteria) {
	var url = urlBase;
	switch (criteria) {
		case 'dbases':
			url += "dbase/getall";
			break;
			
		case 'lists':
			url += "dbase/getall";
			break;
			
		case 'segments':
			url += "dbase/getall";
			break;
	}
	
	return url;
};

TopPanelContent.prototype.processTarget = function(target) {
	var select = $('<select class="select2">');
	for (var i = 0; i < target.length; i++) {
		var option = $('<option value="' + target[i].idDbase + '">' + target[i].name + '</option>');
		select += option;
	}
	select += $('</select>');
	
	console.log(select);
	this.content.find('sgm-selector-content').append(select);
};

TopPanelContent.prototype.addSelector = function (e) {
	e.preventDefault();
	
	var listPanelContent = new ListPanelContent();
	listPanelContent.setPanelContainer(this.container);
	listPanelContent.createContent();
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione un criterio',
		content: listPanelContent
	};
		
	this.container.addPanel(config);
};

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

TopPanelContent.prototype.initializeSelect2 = function() {
	$(function () {
		$(".select2").select2({
			
		});
	});
};