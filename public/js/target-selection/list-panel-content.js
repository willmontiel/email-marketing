function ListPanelContent() {
	this.oldCriteria = {
		type: 'list-panel',
		serialization: {
			items: [],
			names: [],
			conditions: 'all'
		}
	};
	
	this.selectType = 'Multiple';
	this.selectedValue = '';
	this.ds = [];
	this.sd = [];
	this.selectedItems = [];
	this.conditions = 'all';
}

ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.initialize = function(panel) {
	var self = this;
	
//	this.content.find('.sgm-add-item').on('click', function (e) {
//		self.resfreshData();
//		self.model.updatePanelList();
//		self.updateObject();
//		self.model.refreshTotalContacts();
//	});
	
	this.content.find('.sgm-reset-items').on('click', function (e) {
		self.resetItems();
		self.model.updatePanelList();
		self.updateObject();
		self.model.refreshTotalContacts();
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
							<div class="sgm-list-select">\n\
								<input style="width: 90%;" type="hidden" class="select2" />\n\
							</div>\n\
							<div class="sgm-list-button">\n\
								<span class="sgm-reset-items sgm-button-reset glyphicon glyphicon-repeat"></span>\n\
							</div>\n\
						</div>\n\
						<div class="sgm-box-content"></div>\n\
						<div class="sgm-box-footer-content"></div>\n\
					 </div>');
};

ListPanelContent.prototype.serialize = function() {
	if (this.serializerObject !== undefined && this.serializerObject.serialization.items.length > 0) {
		this.oldCriteria = this.serializerObject;
		for (var i = 0; i < this.serializerObject.serialization.items.length; i++) {
			this.selectedValue = this.serializerObject.serialization.items[i];
			this.conditions = this.serializerObject.serialization.conditions;
			this.resfreshData();
		}
	}
};

ListPanelContent.prototype.createNextPanel = function () {
	this.model.createFilterPanel();
};

ListPanelContent.prototype.updateObject = function () {
	var items = [];
	var names = [];
	for (var i = 0; i < this.selectedItems.length; i++) {
		items.push(this.selectedItems[i].attr('data-value'));
		names.push(this.selectedItems[i].attr('data-name'));
	}
	
	this.newCriteria = {
		type: 'list-panel',
		serialization: {
			items: items,
			names: names,
			conditions: this.conditions
		}	
	};
	
	if (items.length <= 0) {
		this.newCriteria.serialization = null;
	}
	
//	console.log('Old');
//	console.log(this.oldCriteria);
//	console.log('New');
//	console.log(this.newCriteria);
	
	this.model.updateObject(this.oldCriteria, this.newCriteria);
	this.oldCriteria = this.newCriteria;
};

ListPanelContent.prototype.resetItems = function () {
	for (var i = 0; i < this.selectedItems.length; i++) {
		this.selectedItems[i].remove();
	}

	this.selectedItems = [];

	this.content.find('.sgm-box-footer-content').empty();

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
	
	var item = $('<div class="sgm-item-added sgm-remove-item" data-name="' + text + '" data-value="' + value + '">\n\
					  <div class="sgm-item-text">' + text + '</div>\n\
					  <div class="sgm-item-icon"><span class="glyphicon glyphicon-minus-sign"></span></div>\n\
				  </div>'); 

	this.content.find('.sgm-box-content').append(item);
	this.selectedItems.push(item);

	item.on('click', function (e) {
		e.preventDefault();

		self.removeItem(this);
		self.model.updatePanelList();
		self.updateObject();
		self.model.refreshTotalContacts();
		if (self.selectedItems.length === 0) {
			self.content.find('.sgm-box-footer-content').empty();
		}
	});
	
	if (self.selectType === 'Unique') {
		self.sd = [];
	}
	
	self.initializeSelect2(self.sd);
	
	var buttons = $('<div class="sgm-add-panel">\n\
						 <span class="glyphicon glyphicon-filter"></span>\n\
					 </div>');
	
	self.content.find('.sgm-box-footer-content').append(buttons);
	
	this.content.find('.sgm-add-panel').on('click', function (e) {
		e.preventDefault();
		
		if (self.content.find('.sgm-all-conditions').length == 0) {
			var all = $('<div class="sgm-all-conditions ' + (self.conditions === 'all' ? 'sgm-condition-active': '') + '" data-conditions="all">All</div>');
			self.content.find('.sgm-box-footer-content').append(all);
		}
		
		if (self.content.find('.sgm-any-conditions').length == 0) {
			var any = $('<div class="sgm-any-conditions ' + (self.conditions === 'any' ? 'sgm-condition-active': '') + '" data-conditions="any">Any</div>');
			self.content.find('.sgm-box-footer-content').append(any);
		}
		
		self.content.find('.sgm-all-conditions').on('click', function (e) {
			e.preventDefault();
			$('.sgm-any-conditions').removeClass('sgm-condition-active');
			$(this).addClass('sgm-condition-active');
			self.conditions = 'all';
			self.updateObject();
	//		self.model.updatePanelList();
			self.model.refreshTotalContacts();
		});
	
		self.content.find('.sgm-any-conditions').on('click', function (e) {
			e.preventDefault();
			$('.sgm-all-conditions').removeClass('sgm-condition-active');
			$(this).addClass('sgm-condition-active');
			self.conditions = 'any';
			self.updateObject();
	//		self.model.updatePanelList();
			self.model.refreshTotalContacts();
		});
		
		self.createNextPanel();
	});
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
		placeholder: "Seleccione una opción"
	}).select2('data', null);
	
	select.on("change", function(e) { 
		e.preventDefault();
		self.selectedValue = e.val;
		
		self.resfreshData();
		self.model.updatePanelList();
		self.updateObject();
		self.model.refreshTotalContacts();
	});
};

ListPanelContent.prototype.checkArrayData = function(array) {
	if (array[0] != undefined && array[0].children == undefined) {
		this.selectType = 'Unique';
	}
};