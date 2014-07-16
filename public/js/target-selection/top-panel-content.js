TopPanelContent = function() {
	this.oldCriteria = {
		type: 'top-panel',
		serialization: {criteria: null}
	};
};

TopPanelContent.prototype = new PanelContent;

TopPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-selector-content').on('click', function (e) {
		$('.sgm-add-selector-content').removeClass('li-active');
		$(this).addClass('li-active');
		self.model.reset();
		self.initializeNextContentPanel($(this));
	});
	
	panel.find('.sgm-panel-content').append(this.content);
	this.serialize();
};

TopPanelContent.prototype.initializeNextContentPanel = function (obj) {
	var criteria = $(obj).attr('data-type');
	
	this.newCriteria = {
		type: 'top-panel',
		serialization: {criteria: criteria}
	};
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.model.createListPanel();
	
	this.oldCriteria = this.newCriteria;
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

TopPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.criteria !== null) {
		this.oldCriteria = this.serializerObject;
		this.content.find('.sgm-add-selector-content[data-type="' + this.serializerObject.serialization.criteria + '"]').addClass('li-active');
	}
};