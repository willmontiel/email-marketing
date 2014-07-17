function ListPanelContent() {
	this.oldCriteria = {
		type: 'list-panel',
		serialization: {items: []}
	};
	
	this.selectType = 'Multiple';
	this.selectedValue = '';
	this.ds = [];
	this.sd = [];
	this.selectedItems = [];
}

ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.initialize = function(panel) {
	var self = this;
	
	this.content.find('.sgm-add-item').on('click', function (e) {
		self.resfreshData();
		self.updateObject();
		self.model.refreshTotalContacts();
	});
	
	this.content.find('.sgm-reset-items').on('click', function (e) {
		self.resetItems();
		self.updateObject();
		self.model.refreshTotalContacts();
	});
	
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.createNextPanel();
	});
	
	var DataSource = this.model.getDataSource();

	DataSource.find('list').then(function() { 
		var data = DataSource.getData();
		self.checkArrayData(data);
		self.ds = data;
		self.sd = $.extend(true, [], self.ds);
		self.initializeSelect2(self.sd);
		self.serialize();
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

ListPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-target-selector">\n\
						<div class="sgm-selector-content">\n\
							<input type="hidden" class="select2" />\n\
							<span class="sgm-reset-items sgm-button-reset glyphicon glyphicon-flash"></span>\n\
							<span class="sgm-add-item sgm-button-add glyphicon glyphicon-plus"></span>\n\
						</div>\n\
						<div class="sgm-box-content"></div>\n\
						<div class="sgm-add-filter-content"></div>\n\
					 </div>');
};

ListPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.serialization.items.length > 0) {
		this.oldCriteria = this.serializerObject;
		for (var i = 0; i < this.serializerObject.serialization.items.length; i++) {
			this.selectedValue = this.serializerObject.serialization.items[i];
			this.resfreshData();
		}
	}
};

ListPanelContent.prototype.createNextPanel = function () {
	this.model.createFilterPanel();
};

ListPanelContent.prototype.updateObject = function () {
	var items = [];
	for (var i = 0; i < this.selectedItems.length; i++) {
		items.push(this.selectedItems[i].attr('data-value'));
	}
	
	this.newCriteria = {
		type: 'list-panel',
		serialization: {items: items}
	};
	
	if (items.length <= 0) {
		this.newCriteria.serialization = null;
	}
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.oldCriteria = this.newCriteria;
};

ListPanelContent.prototype.resetItems = function () {
	for (var i = 0; i < this.selectedItems.length; i++) {
		this.selectedItems[i].remove();
	}

	this.selectedItems = [];

	this.content.find('.sgm-add-panel').remove();

	this.sd = $.extend(true, [], this.ds);
	this.initializeSelect2(this.sd);
};


ListPanelContent.prototype.resfreshData = function () {
	var self = this;
	var value = null;
	var text = null;
	
	for (var i = 0; i < this.sd.length; i++) {
		if (this.sd[i].id == this.selectedValue) {
			value = this.sd[i].id;
			text = this.sd[i].text;
			this.sd.splice(i, 1);
			
			break;
		}
		else if (this.sd[i] !== undefined && this.sd[i].children !== undefined) {
			var children = $.extend(true, [], this.sd[i].children);
			for (var j = 0; j < children.length; j++) {
				if (children[j].id == this.selectedValue) {
					value = children[j].id;
					text = children[j].text;
					
					children.splice(j, 1);
					
					if (children.length <= 0) {
						children = [];
					}
					
					var n = this.sd.splice(i, 1);
					
					this.sd = n; 
					this.sd[0].children = children;
					
					if (this.sd[0].children.length <= 0) {
						this.sd.splice(0, 1);
					}
					
					break;
				}
			}
		}
	}
	
	if (value !== null && text !== null) {
		self.createItemObject(value, text);
	}
};

ListPanelContent.prototype.createItemObject = function (value, text) {
	var self = this;
	
	var item = $('<div class="sgm-item-added sgm-remove-item" data-value="' + value + '">\n\
					  ' + text + '\n\
					  <span class="glyphicon glyphicon-minus-sign sgm-remove-item-style"></span>\n\
				  </div>'); 

	this.content.find('.sgm-box-content').append(item);
	this.selectedItems.push(item);

	item.on('click', function (e) {
		e.preventDefault();

		self.removeItem(this);
		self.updateObject();
		self.model.refreshTotalContacts();
		if (self.selectedItems.length === 0) {
			self.content.find('.sgm-add-panel').remove();
		}
	});
	
	if (self.selectType === 'Unique') {
		self.sd = [];
	}
	
	self.initializeSelect2(self.sd);
	self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
};

ListPanelContent.prototype.removeItem = function (item) {
	var value = $(item).attr('data-value');
	
	for (var i = 0; i < this.ds.length; i++) {
		if (this.ds[i].id == value) {
			var a = this.sd.indexOf(this.ds[i]);
			if (a == -1) {
				this.sd.push(this.ds[i]);
			}
			
			break;
		}
		else if (this.ds[i] != undefined && this.ds[i].children != undefined) {
			var children = this.ds[i].children;
			for (var j = 0; j < children.length; j++) {
				if (children[j].id == value) {
					if (this.sd[i] == undefined) {
						var c = [children[j]]; 
						var x = {
							id: this.ds[i].id, 
							text: this.ds[i].text,
							children: c
						};
						
						this.sd.push(x);
					}
					else {
						this.sd[i].children.push(children[j]);
					}
					
					break;
				}
			}
		}
	}
	
	item.remove();

	for (var m = 0; m < this.selectedItems.length; m++) {
		if (value == this.selectedItems[m].attr('data-value')) {
			this.selectedItems.splice(m, 1);
			
			break;
		}
	}
	
	this.selectedValue = null;
	this.initializeSelect2(this.sd);
};

ListPanelContent.prototype.initializeSelect2 = function(data) {
	var self = this;
	var results = {
		more: false,
		results: data
	};
	
	var select = this.content.find('.select2');
	select.select2({
		data: results,
		placeholder: "Selecciona una opción"
	}).select2('data', null);
	
	select.on("change", function(e) { 
		e.preventDefault();
		self.selectedValue = e.val;
	});
};

ListPanelContent.prototype.checkArrayData = function(array) {
	if (array[0].children == undefined) {
		this.selectType = 'Unique';
	}
};