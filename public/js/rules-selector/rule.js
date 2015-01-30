function Rule(config) {
	this.config = config;
	this.criteriaRule = [];
	this.serializerObject = [];
}

Rule.prototype.setManager = function(container) {
	this.container = container;
};

Rule.prototype.createRule = function() {
//	this.html = $('<div class="row base space-row" style="display: none;"><hr class="hr-rules"></div>');
	this.html = $('<div class="row base space-row"><hr class="hr-rules"></div>');
	
	this.serialize();
	var self = this;
	
	this.html.find('.add-rule').on('click', function (e) {
		var config;
		self.container.addRule(config);
	});
	
	this.html.find('.remove-rule').on('click', function (e) {
		e.preventDefault();
		self.container.removeRule(self);
	});
	
	this.html.find('.base').show();
	
	this.container.element.find('#rules-content').append(this.html);
};

Rule.prototype.serialize = function() {
	if (this.config != null || this.config != undefined) {
		for (var i = 0; i < this.config.length; i++) {
			switch (this.config[i].type) {
				case 'index-rule':
					this.createIndex(this.config[i]);
					break;

				case 'operator-rule':
					this.createOperator(this.config[i]);
					break;

				case 'condition-rule':
					this.createCondition(this.config[i]);
					break;

				case 'points-rule':
					this.createPoints(this.config[i]);
					break;
			}
		}
	}
	else {
		this.createIndex(null);
		this.createOperator(null);
		this.createCondition(null);
		this.createPoints(null);
	}
	
	this.createButtons();
};

Rule.prototype.createIndex = function (config) {
	var index = new IndexRule(config);
	index.setRule(this);
	index.create();
	this.criteriaRule.push(index);
};

Rule.prototype.createOperator = function (config) {
	var op = new OperatorRule(config);
	op.setRule(this);
	op.create();
	this.criteriaRule.push(op);
};

Rule.prototype.createCondition = function (config) {
	var con = new ConditionRule(config);
	con.setRule(this);
	con.create();
	this.criteriaRule.push(con);
};

Rule.prototype.createPoints = function (config) {
	var po = new PointsRule(config);
	po.setRule(this);
	po.create();
	this.criteriaRule.push(po);
};

Rule.prototype.createLogicOperator = function (config) {
	var lo = new LogicOperatorRule(config);
	lo.setRule(this);
	lo.create();
	this.criteriaRule.push(lo);
};

Rule.prototype.createButtons = function () {
	var config = {add:true, remove:true};
	var bu = new ButtonsRule(config);
	bu.setRule(this);
	bu.create();
//	this.criteriaRule.push(bu);
};

Rule.prototype.getSerializerObject = function () {
	this.serializerObject = [];
	
	for (var i = 0; i < this.criteriaRule.length; i++) {
		var obj = this.criteriaRule[i].getSerializerObject();
		this.serializerObject.push(obj);
	}
	
	return this.serializerObject;
};

Rule.prototype.remove = function () {
	this.html.remove();
};