function Model() {
	this.serializerObj = [];
	this.totalContacts = 0;
}

Model.prototype.setPanelContainer = function(container){
	this.container = container;
};

Model.prototype.setSerializerObject = function(serializerObj) {
	this.serializerObj = serializerObj;
};

Model.prototype.serializer = function() {
	if (this.serializerObj === null || this.serializerObj === undefined) {
		var obj = {
			type: 'top-panel',
			serialization: {criteria: null}
		};
		
		this.createTopPanel(obj);
	}
	else {
		for (var i = 0; i < this.serializerObj.length; i++) {
			this.selectTypeObject(this.serializerObj[i]);
		}
	}
};

Model.prototype.selectTypeObject = function(obj) {
	switch (obj.type) {
		case 'top-panel':
			this.createTopPanel(obj);
			break
			
		case 'list-panel':
			this.createListPanel(obj);
			break
			
		case 'filter-panel':
			this.createFilterPanel(obj);
			break
	}
	
//	this.dataObjects.push(obj);
};

Model.prototype.createTopPanel = function(obj) {
	var topPanelContent = new TopPanelContent();
	topPanelContent.setModel(this);
	topPanelContent.createContent();
	
	if (obj !== undefined) {
		topPanelContent.setSerializerObj(obj);
	}
	
	var config = {
		sticky: true, 
		leftArrow: false, 
		title: 'Seleccione una opción',
		content: topPanelContent
	};
	
	this.container.addPanel(config, this);
};

Model.prototype.createListPanel = function(obj) {
//	this.container.resetContainer();
	
	var listPanelContent = new ListPanelContent();
	listPanelContent.setModel(this);
	listPanelContent.createContent();
	
	if (obj !== undefined) {
		listPanelContent.setSerializerObj(obj);
	}
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione un criterio',
		content: listPanelContent
	};
	
	this.container.addPanel(config, this);
};

Model.prototype.createFilterPanel = function(obj) {
	
	var filterPanelContent = new FilterPanelContent();
	filterPanelContent.setModel(this);
	filterPanelContent.createContent();
	
	if (obj !== undefined) {
		filterPanelContent.setSerializerObj(obj);
	}
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione una opción',
		content: filterPanelContent
	};
		
	this.container.addPanel(config, this);
};

Model.prototype.getDataSource = function() {
	var dataSource = new DataSource(this.serializerObj);
	return dataSource;
};

Model.prototype.cleanObject = function() {
	for (var i = 0; i < this.serializerObj.length; i++) {
		if ( this.serializerObj[i] === undefined ) {
			this.serializerObj.splice(i,1);
			i--;
		}
	}
};

Model.prototype.updateObject = function(oldObj, newObj) {
	this.cleanObject();
	var key = this.serializerObj.indexOf(oldObj);
	
	if (newObj.serialization !== null) {
		if (key >= 0) {
			this.serializerObj[key] = newObj;
		}
		else {
			this.serializerObj.push(newObj);
		}
	}
	else {
		if (key >= 0) {
			var j = this.serializerObj.splice(key, 1);
		}
	}
};

Model.prototype.removePanel = function(panel) {
	var key = this.container.removePanel(panel);
	
	if (key >= 0) {
		var l = this.serializerObj.length;
		this.serializerObj.splice(key, l - key);
	}
	
	this.refreshTotalContacts();
};

Model.prototype.reset = function() {
	this.refreshTotalContactsView('Contactos aproximados: 0');
	this.container.resetContainer();
	this.serializerObj = [];
};

Model.prototype.updatePanelList = function() {
	this.container.updateContainer();
	
//	console.log(this.serializerObj);
	var k = [];
	k.push(this.serializerObj[0]);
	k.push(this.serializerObj[1]);
	
	this.serializerObj = [];
	this.serializerObj = k.slice(0);
};

Model.prototype.refreshTotalContacts = function() {
	var self = this;
	
	this.refreshTotalContactsView('Contactos aproximados: <div class="sgm-loading-image"></div>');
	var DataSource = this.getDataSource();
	DataSource.find('/gettotalcontacts').then(function() { 
		var total = DataSource.getData();
		self.totalContacts = total.totalContacts;
		self.refreshTotalContactsView('Contactos aproximados: ' + self.totalContacts);
	});
};


Model.prototype.refreshTotalContactsView = function(data) {
	$('.sgm-panel-contacts-space').empty();
	$('.sgm-panel-contacts-space').append(data);
};

Model.prototype.getModel = function() {
	return this.serializerObj;
};

Model.prototype.getTotalContacts = function() {
	return this.totalContacts;
};