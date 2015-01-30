function SectionRule(config) {};

SectionRule.prototype.setRule = function(rule) {
	this.rule = rule;
};

SectionRule.prototype.create = function() {};
SectionRule.prototype.serialize = function() {};
SectionRule.prototype.getSerializerObject = function() {};

SectionRule.prototype.setSelect2 = function() {
	$(".select2").select2({});
};