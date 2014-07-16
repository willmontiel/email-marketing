function ListPanelContent() {
	this.oldCriteria = {
		type: 'list-panel',
		serialization: {items: []}
	};
	
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
	});
	
	this.content.find('.sgm-reset-items').on('click', function (e) {
		self.resetItems();
		self.updateObject();
	});
	
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.createNextPanel();
	});
	
	var DataSource = this.model.getDataSource();

	DataSource.find('list').then(function() { 
		self.ds = DataSource.getData();
		self.sd = self.ds.slice(0);
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
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.oldCriteria = this.newCriteria;
};

ListPanelContent.prototype.resetItems = function () {
	for (var i = 0; i < this.selectedItems.length; i++) {
		this.selectedItems[i].remove();
	}

	this.selectedItems = [];

	this.content.find('.sgm-add-panel').remove();

	this.sd = this.ds.slice(0);
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
			for (var j = 0; j < this.sd[i].children.length; j++) {
				if (this.sd[i].children[j].id == this.selectedValue) {
					value = this.sd[i].children[j].id;
					text = this.sd[ i].children[j].text;

					this.sd[i].children.splice(j, 1);
					var n = this.sd.splice(i, 1);
					this.sd = n.slice(0);

					if (this.sd[0].children.length == 0) {
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
					  <span class="glyphicon glyphicon-remove"></span> \n\
					  ' + text + '\
				  </div>'); 

	this.content.find('.sgm-box-content').append(item);
	this.selectedItems.push(item);

	this.content.find('.sgm-remove-item').on('click', function (e) {
		e.preventDefault();

		self.removeItem(this);
		self.updateObject();
		if (self.selectedItems.length === 0) {
			self.content.find('.sgm-add-panel').remove();
		}
	});

	self.initializeSelect2(self.sd);
	self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
};

ListPanelContent.prototype.removeItem = function (item) {
	var value = $(item).attr('data-value');
	
	for (var i = 0; i < this.ds.length; i++) {
		if (this.ds[i].id === value) {
			var a = this.sd.indexOf(this.ds[i]);
			if (a === -1) {
				this.sd.push(this.ds[i]);
			}
			
			break;
		}
		else if (this.ds[i] !== undefined && this.ds[i].children !== undefined) {
			for (var j = 0; j < this.ds[i].children.length; j++) {
				if (this.ds[i].children[j].id === value) {
					if (this.sd[i] === undefined) {
						var x = {
							id: this.ds[i].id, 
							text: this.ds[i].text,
							children: this.ds[i].children[j]
						};
						
						this.ds.push(x);
					}
					else {
						this.sd[i].children.push(this.ds[i].children[j]);
					}
					
					break;
				}
			}
		}
	}
	
	item.remove();

	for (var m = 0; m < this.selectedItems.length; m++) {
		if (value === this.selectedItems[m].attr('data-value')) {
			this.selectedItems.splice(m, 1);
			
			break;
		}
	}
			
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
	});
	
	select.on("change", function(e) { 
		e.preventDefault();
		self.selectedValue = e.val;
	});
};