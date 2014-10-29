function ConditionRule(config) {
	this.config = config;
	this.value = '';
	this.class = '%';
}

ConditionRule.prototype.setRule = function(rule) {
	this.rule = rule;
};

ConditionRule.prototype.create = function() {
	this.serialize();
	
	this.html = $('<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">\n\
						<div class="" style="display: inline-flex; width: 60%;">\n\
							<input type="text" name="condition-rule-input" required="required" id="condition-rule-input" style="width: 100%;" value="' + this.value + '"/>\n\
						</div>\n\
						<div class="" style="display: inline-flex; width: 30%;">\n\
						   <select class="select2" id="condition-rule-select" name="condition-rule-select" style="width: 100%;">\n\
							<option value="%" ' + this.percent + '>%</option>\n\
							<option value="#" ' + this.number + '>#</option>\n\
						</select>\n\
					</div>');
	
	this.rule.html.append(this.html);
	
	var self = this;
	
	this.html.find("#condition-rule-select").change(function () {
		self.class = $(this).val();
	});
};

ConditionRule.prototype.serialize = function() {
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

ConditionRule.prototype.getSerializerObject = function() {
	this.value = this.html.find('#condition-rule-input').val();
	var obj = {type: 'condition-rule', value: '' + this.value, class: this.class};
	
	return obj;
};
