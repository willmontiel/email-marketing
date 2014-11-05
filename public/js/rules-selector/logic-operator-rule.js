function LogicOperatorRule(config) {
	this.config = config;
	this.value = '';
	this.class = '%';
}

LogicOperatorRule.prototype = new SectionRule;

LogicOperatorRule.prototype.create = function() {
	this.serialize();
	
	this.html = $('<div class="res-space col-xs-12 col-sm-12 col-md-1 col-lg-1">\n\
						<div class="" style="display: inline-flex; width: 60%;">\n\
							<input type="text" name="condition-rule-input" required="required" id="condition-rule-input" style="width: 100%;" value="' + this.value + '"/>\n\
						</div>\n\
					</div>');
	
	this.rule.html.append(this.html);
	
	var self = this;
	
	this.html.find("#condition-rule-select").change(function () {
		self.class = $(this).val();
	});
};

LogicOperatorRule.prototype.serialize = function() {
	if (this.config != null || this.config != undefined) {
		this.value = this.config.value;
		if (this.config.class === '%') {
			this.percent = 'selected';
		}
		else {
			this.number = 'selected';
		}
	}
};

LogicOperatorRule.prototype.getSerializerObject = function() {
	this.value = this.html.find('#condition-rule-input').val();
	
	if (this.value === undefined || this.value === '') {
		throw "El valor en alguna de las condiciones esta vacío, por favor valide la información";
	}
	
	var obj = {type: 'logic-operator-rule', value: '' + this.value, class: this.class};
	
	return obj;
};
