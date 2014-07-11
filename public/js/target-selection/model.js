function Model() {
	this.dataObjects = new Array();
}

Model.prototype.setPanelContainer = function(container){
	this.container = container;
};

Model.prototype.setSerializerObject = function(serializerObj) {
	this.serializerObj = serializerObj;
};

Model.prototype.serializer = function() {
	for (var i = 0; i < this.serializerObj.length; i++) {
		this.selectTypeObject(this.serializerObj[i]);
	}
};

Model.prototype.selectTypeObject = function(obj) {
	switch (obj.type) {
		case 'top-panel':
			this.createTopPanel(obj.serialization);
			break
			
		case 'list-panel':
			this.createListPanel(obj.serialization);
			break
			
		case 'filter-panel':
			this.createFilterPanel(obj.serialization);
			break
	}
	
	this.dataObjects.push(obj);
};

Model.prototype.createTopPanel = function(obj) {
	var topPanelContent = new TopPanelContent();
	topPanelContent.setModel(this);
//	topPanelContent.setPanelContainer(this.container);
	topPanelContent.createContent();
	topPanelContent.serialize(obj);

	var config = {
		sticky: true, 
		leftArrow: false, 
		title: 'Seleccione una opción',
		content: topPanelContent
	};

	this.container.addPanel(config);
};

Model.prototype.createListPanel = function(obj) {
	var listPanelContent = new ListPanelContent();
	listPanelContent.setModel(this.model);
//	listPanelContent.setPanelContainer(this.container);
	listPanelContent.createContent();
	listPanelContent.serialize(obj);
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione un criterio',
		content: listPanelContent
	};
		
	this.container.addPanel(config);
};

Model.prototype.addDataObject = function(object) {
	this.dataObjects.push(object);
};

Model.prototype.getDataSource = function() {
	
};

Model.prototype.updateObject = function() {
	
};