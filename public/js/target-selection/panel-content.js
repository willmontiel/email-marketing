function PanelContent() {
	
}

PanelContent.prototype.initialize = function(panel) {
	panel.find('.sgm-panel-content').append(this.content);
};

function TopPanelContent() {
	
}

TopPanelContent.prototype = new PanelContent;


TopPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-content-selector">\n\
						 <ul>\n\
							<li class="addPanel" data-type="dbases">\n\
								<span class="glyphicon glyphicon-tasks"></span> Bases de datos\n\
							</li>\n\
							<li class="addPanel" data-type="lists">\n\
								<span class="glyphicon glyphicon-list-alt"></span> Listas de contactos\n\
							</li>\n\
							<li class="addPanel" data-type="segments">\n\
								<span class="glyphicon glyphicon-user"></span> Segmentos\n\
							</li>\n\
						 </ul>\n\
					  </div>');
};

function ListPanelContent() {
	
}

ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.createContent = function () {
	this.content = $('<strong>Hola</strong>');
};