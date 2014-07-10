function ListPanelContent() {
	this.selectedValue = '';
	this.selectType = '';
	this.dataSource = [];
	this.selectData = [];
	this.selectedItems = [];
	this.filterlist = [];
}

ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
	});
	
	this.content.find('.sgm-add-item').on('click', function (e) {
		self.removeElementsAndResfreshSelect();
		self.refreshTotalContacts();
	});
	
	this.content.find('.sgm-reset-items').on('click', function (e) {
		for (var i = 0; i < self.selectedItems.length; i++) {
			self.selectedItems[i].remove();
		}
		
		self.selectedItems = [];
		
		self.content.find('.sgm-add-panel').remove();
		
		self.selectData = self.dataSource.slice(0);
		self.initializeSelect2(self.selectData);
	});
	
	var url = self.getUrlForDataSource();
	var dataSource = new DataSourceForSelect(url);

	dataSource.findDataSource().then(function() { 
		var source = dataSource.getDataSource();
		
		self.dataSource = self.cloneArrayObject(source);
		self.selectData = self.cloneArrayObject(source);
		self.initializeSelect2(self.selectData);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

ListPanelContent.prototype.refreshTotalContacts = function () {
	var ids = new Array();
	for (var i = 0; i < this.selectedItems.length; i++) {
		ids.push($(this.selectedItems[i]).attr('data-value'));
	}
	
	var self = this;
	var data = {
		criteria: this.criteria,
		ids: ids
	};
	
	console.log(data);
	
	var url = urlBase + 'api/gettotalcontacts';
	
	var dataSource = new DataSourceForSelect(url, data);
	dataSource.findDataSource().then(function() { 
		var total = dataSource.getDataSource();
		$('.sgm-panel-contacts-space').empty();
		$('.sgm-panel-contacts-space').append('Contactos aproximados: ' + total.totalContacts);
	});
};

ListPanelContent.prototype.removeElementsAndResfreshSelect = function () {
	var self = this;
	var value = null;
	var text = null;

	for (var i = 0; i < self.selectData.length; i++) {
		if (self.selectData[i].id === self.selectedValue) {
			value = self.selectData[i].id;
			text = self.selectData[i].text;

			self.selectData.splice(i, 1);
			break;
		}
		else if (self.selectData[i] !== undefined && self.selectData[i].children !== undefined && self.selectData[i].children !== null) {
			for (var j = 0; j < self.selectData[i].children.length; j++) {
				if (self.selectData[i].children[j].id === self.selectedValue) {
					value = self.selectData[i].children[j].id;
					text = self.selectData[ i].children[j].text;

					self.selectData[i].children.splice(j, 1);

					var n = self.selectData.splice(i, 1);

					self.selectData = n.slice(0);

					if (self.selectData[0].children.length === 0) {
						self.selectData.splice(0, 1);
					}
					break;
				}
			}
		}
	}
	
	if (value !== null && text !== null) {
		var item = $('<div class="sgm-item-added sgm-remove-item" data-criteria="' + self.criteria + '" data-value="' + value + '">\n\
						  <span class="glyphicon glyphicon-remove"></span> \n\
						  ' + text + '\
					  </div>'); 

		self.content.find('.sgm-box-content').append(item);

		self.selectedItems.push(item);

		self.content.find('.sgm-remove-item').on('click', function (e) {
			self.removeItem(e, this);

			if (self.selectedItems.length === 0) {
				self.content.find('.sgm-add-panel').remove();
			}
		});

		if (self.selectType === 'unique') {
			self.selectData = [];
		}

		self.initializeSelect2(self.selectData);
		self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
	}
};

ListPanelContent.prototype.cloneArrayObject = function (baseArray) {
	var newArray = new Array();
	
	for (var i = 0; i < baseArray.length; i++) {
		newArray.push(baseArray[i]);
	}
	return newArray;
};

ListPanelContent.prototype.removeItem = function (e, item) {
	e.preventDefault();
	var value = $(item).attr('data-value');
	
//	console.log(value);
//	console.log(this.dataSource);
	
	for (var i = 0; i < this.dataSource.length; i++) {
		if (this.dataSource[i].id === value) {
			var a = this.selectData.indexOf(this.dataSource[i]);
			if (a === -1) {
				this.selectData.push(this.dataSource[i]);
			}
			break;
		}
		else if (this.dataSource[i] !== undefined && this.dataSource[i].children !== undefined && this.dataSource[i].children !== null) {
			for (var j = 0; j < this.dataSource[i].children.length; j++) {
				if (this.dataSource[i].children[j].id === value) {
					if (this.selectData[i] === undefined) {
						var x = {
							id: this.dataSource[i].id, 
							text: this.dataSource[i].text,
							children: this.dataSource[i].children[j]
						};
						
						this.selectData.push(x);
					}
					else {
						this.selectData[i].children.push(this.dataSource[i].children[j]);
					}
					
					break;
				}
			}
		}
	}
	
	item.remove();
	
	var it = this.selectedItems.indexOf(item);
	
	if (it >= 0) {
		var tor = this.selectedItems.splice(it, 1);
		tor.remove();
	}
	
	this.initializeSelect2(this.selectData);
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

ListPanelContent.prototype.getUrlForDataSource = function() {
	var url = urlBase;
	switch (this.criteria) {
		case 'dbases':
			url += "api/getdbases";
			this.selectType = 'unique';
			break;
			
		case 'contactlists':
			url += "api/getcontactlists";
			this.selectType = 'multiple';
			break;
			
		case 'segments':
			url += "api/getsegments";
			this.selectType = 'multiple';
			break;
	}
	
	return url;
};

ListPanelContent.prototype.initializeSelect2 = function(data) {
	var d = data.slice(0);
	var self = this;
	var results = {
		more: false,
		results: d
	};
	
	var select = this.content.find('.select2');
	select.select2({
//		multiple: true,
		data: results,
		placeholder: "Selecciona una opción"
	});
	
	select.on("change", function(e) { 
		e.preventDefault();
//		self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
		self.selectedValue = e.val;
	});
};

ListPanelContent.prototype.addContent = function(e) {
	e.preventDefault();
	
	var filterPanelContent = new FilterPanelContent();
	filterPanelContent.setPanelContainer(this.container);
	filterPanelContent.setSelectedItems(this.selectedItems);
	filterPanelContent.createContent();
	
	this.filterlist.push(filterPanelContent);
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione una opción',
		content: filterPanelContent
	};
		
	this.container.addPanel(config);
};