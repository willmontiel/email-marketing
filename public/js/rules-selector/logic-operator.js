function LogicOperator(config) {
	this.config = config;
	this.value = 'and';
	this.and = '';
	this.or = '';
};

LogicOperator.prototype.setManager = function(manager) {
	this.manager = manager;
};

LogicOperator.prototype.create = function() {
	if (this.config != undefined && this.config != null) {
		if (this.config.value === 'and') {
			this.and = 'selected';
			this.or = '';
		}
		else if (this.config.value === 'or') {
			this.and = '';
			this.or = 'selected';
		}
	}
	
	//<option value="or" ' + this.or + '>Que cumpla cualquiera de las reglas</option>\n\
	this.html = $('<div class="row">\n\
					   <div class="logic-operator col-xs-12 col-sm-12 col-md-6 col-lg-6">\n\
						   <select class="select2" id="logic-operator" name="logic-operator">\n\
							   <option value="and" ' + this.and + '>Que cumpla todas las reglas</option>\n\
						   </select>\n\
						</div>\n\
					</div>');
	
	var self = this;
	
	this.html.find("#logic-operator").change(function () {
		self.value = $(this).val();
	});
	
	this.manager.element.find('#rules-content').append(this.html);
};

LogicOperator.prototype.getSerializerObject = function() {
	var obj = {type: 'logic-operator', value: '' + this.value};
	
	return obj;
};