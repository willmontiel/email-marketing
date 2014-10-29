function ButtonsRule(config) {
	this.config = config;
	this.add = '';
	this.remove = '';
}

ButtonsRule.prototype.setRule = function(rule) {
	this.rule = rule;
};

ButtonsRule.prototype.create = function() {
	this.serialize();
	this.html = $('<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 action-buttons">\n\
						' + this.remove + '\n\
						' + this.add + '\n\
					</div>');
	
	this.rule.html.append(this.html);
};

ButtonsRule.prototype.serialize = function() {
	if (this.config.add) {
		this.add = '<div class="add-rule">+</div>';
	}
	
	if (this.config.remove) {
		this.remove = '<div class="remove-rule">-</div>';
	}
};

