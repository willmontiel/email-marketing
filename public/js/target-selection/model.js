function Model() {
	this.serializerObj = [];
	this.totalContacts = 0;
	this.totalSelectedValues = 0;
	this.criteriaType = 'Indefinido';
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
			
		case 'total-contacts':
			this.updateTotalContactsView(obj);
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

Model.prototype.createListPanel = function(obj, criteria) {
	var listPanelContent = new ListPanelContent();
	listPanelContent.setModel(this);
	listPanelContent.createContent();
	
	if (obj !== undefined) {
		listPanelContent.setSerializerObj(obj);
	}
	
	var title = this.validateTitle(criteria);
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: title,
		content: listPanelContent
	};
	
	this.container.addPanel(config, this);
};

Model.prototype.validateTitle = function(criteria) {
	var title;
	
	switch (criteria) {
		case 'dbases':
			this.criteriaType = 'Base de datos';
			title = 'Seleccione una base de datos';
			break;
			
		case 'contactlists':
			this.criteriaType = 'Lista(s) de contacto';
			title = 'Seleccione listas de contactos';
			break;
			
		case 'segments':
			this.criteriaType = 'Segmento(s)';
			title = 'Seleccione segmentos';
			break;
		
		default:
			this.criteriaType = 'Indefinido';
			title = 'Seleccione un criterio';
			break;
	}
	return title;
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
		title: 'Seleccione un filtro',
		content: filterPanelContent
	};
		
	this.container.addPanel(config, this);
};

Model.prototype.updateTotalContactsView = function(obj) {
	this.totalContacts = obj.total;
	this.refreshTotalContactsView('Contactos aproximados: ' + obj.total);
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
		var l = this.serializerObj.length - 1;
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
	var k = [];
	
	for (var i = 0; i < this.serializerObj.length; i++) {
		if (this.serializerObj[i].type === 'top-panel' ||
			this.serializerObj[i].type === 'list-panel') {
			k.push(this.serializerObj[i]);
		}
	}
	
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
		self.refreshTotalContactsObject();
	});
};

Model.prototype.refreshTotalContactsObject = function() {
	var total = false;
	
	for (var i = 0; i < this.serializerObj.length; i++) {
		if (this.serializerObj[i].type === 'total-contacts') {
			this.serializerObj[i].total = this.totalContacts;
			total = true;
			break;
		}
	}
	
	if (!total) {
		var tc = {
			type: 'total-contacts',
			total: this.totalContacts
		};
		
		this.serializerObj.push(tc);
	}
};

Model.prototype.refreshTotalContactsView = function(data) {
	$('.sgm-panel-contacts-space').empty();
	$('.sgm-panel-contacts-space').append(data);
};

Model.prototype.getModel = function() {
	return this.serializerObj;
};

Model.prototype.getCriteriaType = function() {
	return this.criteriaType;
};

Model.prototype.getSelectedValues = function() {
	var array = [];
	for (var i = 0; i < this.serializerObj.length; i++) {
		if (this.serializerObj[i].type === 'list-panel') {
			array = this.serializerObj[i].serialization.items;
		}
	}
	
	this.totalSelectedValues = array.length;
	return array[0];
//	var self = this;
//	var DataSource = this.getDataSource();
//	DataSource.find('/gettotalcontacts').then(function() { 
//		var total = DataSource.getData();
//		self.totalContacts = total.totalContacts;
//		self.refreshTotalContactsView('Contactos aproximados: ' + self.totalContacts);
//		self.refreshTotalContactsObject();
//	});
//	return this.criteriaType;
};

Model.prototype.getTotalSelectedValues = function() {
	return this.totalSelectedValues;
};

Model.prototype.getTotalFilters = function() {
	var f = 0;
	for (var i = 0; i < this.serializerObj.length; i++) {
		if (this.serializerObj[i].type === 'filter-panel') {
			f++;
		}
	}
	
	return f;
};

Model.prototype.getTotalContacts = function() {
	return this.totalContacts;
};