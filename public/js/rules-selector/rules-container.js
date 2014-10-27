function RulesContainer(container) {
	this.element = $(container);
	var base = $('<div class="panel panel-default"><div class="panel-body" style="background-color: #f5f5f5;" id="rules-content"></div></div>');
	this.element.append(base);
	this.ruleslist = [];
}

RulesContainer.prototype.initialize = function(config) {
	this.addRule(config);
};

RulesContainer.prototype.addRule = function(config) {
	var rule = new Rule(config);
	rule.setContainer(this);
	rule.createRule();
	this.ruleslist.push(rule);
};

RulesContainer.prototype.removeRule = function(rule) {
//	console.log(rule);
	var i = this.ruleslist.indexOf(rule);
	
	var l = this.ruleslist.length;
	
	console.log(i);
	console.log(l);
	
	if (i >= 0) {
		var tor = this.ruleslist.splice(i, l - i);
		for (var j=0; j<tor.length; j++) {
			tor[j].remove();
		}
		
//		if (this.ruleslist.length >= 0) {
//			this.addRule(null);
//		}
	}
};

RulesContainer.prototype.resetContainer = function () {
	for (var i = 1; i < this.ruleslist.length; i++) {
		this.ruleslist[i].remove();
	}
	
	this.addRule(null);
};