TopPanelContent = function() {};

TopPanelContent.prototype = new PanelContent;

TopPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-selector-content').on('click', function (e) {
		self.updateView($(this));
		self.createNextPanel($(this));
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

TopPanelContent.prototype.updateView = function (obj) {
	$('.sgm-add-selector-content').removeClass('li-active');
	$(obj).addClass('li-active');
};

TopPanelContent.prototype.createNextPanel = function (obj) {
	var criteria = $(obj).attr('data-type');
	this.model.updateObject(this);
	
	var ser = {
		type: 'list-panel',
		serialization: {criteria: criteria}
	};
	
	this.model.createListPanel(ser);
};

TopPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-content-selector">\n\
						 <ul>\n\
							<li class="sgm-add-selector-content" data-type="dbases">\n\
								<span class="glyphicon glyphicon-tasks"></span> Bases de datos\n\
							</li>\n\
							<li class="sgm-add-selector-content" data-type="contactlists">\n\
								<span class="glyphicon glyphicon-list-alt"></span> Listas de contactos\n\
							</li>\n\
							<li class="sgm-add-selector-content" data-type="segments">\n\
								<span class="glyphicon glyphicon-user"></span> Segmentos\n\
							</li>\n\
						 </ul>\n\
					  </div>');
};

TopPanelContent.prototype.serialize = function(obj) {
	if (obj.criteria !== null) {
		this.content.find('.sgm-add-selector-content[data-type="' + obj.criteria + '"]').addClass('li-active');
	}
};