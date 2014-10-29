function PointsRule(config) {
	this.config = config;
	this.pointsstatus = '';
	this.points = 'false';
	this.displayInput = 'none';
	this.value = '';
}

PointsRule.prototype.setRule = function(rule) {
	this.rule = rule;
};

PointsRule.prototype.create = function() {
	this.serialize();
	
	this.html = $('<div class="points col-xs-12 col-sm-12 col-md-2 col-lg-2">\n\
					   <div class="" style="display: inline; width: 60%;">\n\
						   <input type="checkbox" name="points-rule-checkbox" id="points-rule-checkbox" ' + this.pointsstatus + '/> Puntos\n\
					   </div>\n\
					   <div class="" style="display: inline-flex; width: 40%;">\n\
						   <input type="text" id="points-rule-input" name="points-rule-input" style="display: ' + this.displayInput + ';" value="' + this.value + '"/>\n\
			           </div>\n\
				   </div>');
	
	this.rule.html.append(this.html);
	
	var self = this;
	
	this.html.find("#points-rule-checkbox").change(function () {
		if ($(this).prop('checked')) {
			self.html.find('#points-rule-input').show('slow');
			self.points = 'true';
		}
		else {
			self.html.find('#points-rule-input').hide('slow');
			self.points = 'false';
		}
	});
};

PointsRule.prototype.serialize = function() {
	if (this.config != null || this.config != undefined) {
		if (this.config.points === 'true') {
			this.points = this.config.points;
			this.value = this.config.value;
			this.displayInput = 'block';
			this.pointsstatus = 'checked';
		}
	}
};

PointsRule.prototype.getSerializerObject = function() {
	this.value = this.html.find('#points-rule-input').val();
	var obj = {type: 'points-rule', points: this.points, value: this.value};
	
	return obj;
};