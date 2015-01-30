function OperatorRule(config) {
	this.config = config;
	this.less = '';
	this.greater = '';
	this.equals = '';
	this.notequals = '';
	this.greaterequals = '';
	this.lessequals = '';
	this.value = '<';
}

OperatorRule.prototype = new SectionRule;

OperatorRule.prototype.create = function() {
	this.serialize();
	
	this.html = $('<div class="res-space col-xs-12 col-sm-12 col-md-2 col-lg-2">\n\
						<select class="select2" id="operator-rule-select" name="operator-rule-select" style="width:100%">\n\
							<option value="<" ' + this.less + '><</option>\n\
							<option value=">" ' + this.greater + '>></option>\n\
							<option value="=" ' + this.equals + '>=</option>\n\
							<option value="!=" ' + this.notequals + '>!=</option>\n\
							<option value=">=" ' + this.greaterequals + '>>=</option>\n\
							<option value="<=" ' + this.lessequals + '><=</option>\n\
						</select>\n\
					</div>	');
	
	this.rule.html.append(this.html);
	
	var self = this;
	
	this.html.find("#operator-rule-select").change(function () {
		self.value = $(this).val();
	});
};

OperatorRule.prototype.serialize = function() {
	if (this.config != null || this.config != undefined) {
		switch (this.config.value) {
			case '<':
				this.less = 'selected';
				break;
				
			case '>':
				this.greater = 'selected';
				break;
				
			case '=':
				this.equals = 'selected';
				break;
				
			case '!=':
				this.notequals = 'selected';
				break;
				
			case '>=':
				this.greaterequals = 'selected';
				break;
				
			case '<=':
				this.lessequals = 'selected';
				break;
		}
		this.value = this.config.value;
	}
};

OperatorRule.prototype.getSerializerObject = function() {
	var obj = {type: 'operator-rule', value: '' + this.value};
	return obj;
};
