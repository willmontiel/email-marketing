function RulesManager() {
	this.ruleslist = [];
	this.serializeObj = [];
}

RulesManager.prototype.setData = function(rules) {
	this.rules = rules;
};

RulesManager.prototype.setContainer = function(container) {
	this.element = $(container);
	var base = $('<div class="panel panel-default"><div class="panel-body panel-body2" id="rules-content"></div></div>');
	this.element.append(base);
};

RulesManager.prototype.initialize = function() {
	if (this.rules === null || this.rules === undefined) {
		this.addRule(null);
	}
	else {
		for (var i = 0; i < this.rules.length; i++) {
			this.addRule(this.rules[i]);
		}
	};
};

RulesManager.prototype.addRule = function(config) {
	var rule = new Rule(config);
	rule.setManager(this);
	rule.createRule();
	this.ruleslist.push(rule);
};

RulesManager.prototype.removeRule = function(rule) {
	var i = this.ruleslist.indexOf(rule);
	
	if (i >= 0) {
		var tor = this.ruleslist.splice(i, 1);
		tor[0].remove();
		
		if (this.ruleslist.length <= 0) {
			this.addRule(null);
		}
	}
};

RulesManager.prototype.resetContainer = function() {
	for (var i = 0; i < this.ruleslist.length; i++) {
		this.ruleslist[i].remove();
	}
	
	this.addRule(null);
};

RulesManager.prototype.serializeRules = function() {
	this.serializeObj = [];
	
//	try {
		for (var i = 0; i < this.ruleslist.length; i++) {
			var obj = this.ruleslist[i].getSerializerObject();
			this.serializeObj.push(obj);
		}
//	}
//	catch (e) {
//		console.log(e);
//	}
};

RulesManager.prototype.getSerializerObject = function() {
	return this.serializeObj;
};