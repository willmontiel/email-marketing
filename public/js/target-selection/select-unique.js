function SelectUnique() {
}

SelectUnique.prototype.setData = function(data) {
	this.data = data;
};

SelectUnique.prototype.updateSelect = function() {
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
