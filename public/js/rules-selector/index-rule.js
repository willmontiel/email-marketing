function IndexRule(config) {
	this.config = config;
	this.opens = '';
	this.bounced = '';
	this.unsubscribed = '';
	this.spam = '';
	
	this.value = 'opens';
}

IndexRule.prototype.setRule = function(rule) {
	this.rule = rule;
};

IndexRule.prototype.create = function() {
	this.serialize();
	
	this.html = $('<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">\n\
						<select class="select2" name="index-rule-select" id="index-rule-select" style="width:100%">\n\
							<option value="opens" ' + this.opens + '>Aperturas</option>\n\
							<option value="bounced" ' + this.bounced + '>Rebotes</option>\n\
							<option value="unsubscribed" ' + this.unsubscribed + '>Des-suscritos</option>\n\
							<option value="spam" ' + this.spam + '>Quejas de spam</option>\n\
						</select>\n\
					</div>');
	
	this.rule.html.append(this.html);
	
	var self = this;
	
	this.html.find("#index-rule-select").change(function () {
		self.value = $(this).val();
	});
};

IndexRule.prototype.serialize = function() {
	if (this.config != null || this.config != undefined) {
		switch (this.config.value) {
			case 'opens':
				this.opens = 'selected';
				break;
				
			case 'bounced':
				this.bounced = 'selected';
				break;
				
			case 'unsubscribed':
				this.unsubscribed = 'selected';
				break;
				
			case 'spam':
				this.spam = 'selected';
				break;
		}
		
		this.value = this.config.value;
	}
};

IndexRule.prototype.getSerializerObject = function() {
	var obj = {type: 'index-rule', value: '' + this.value};
	return obj;
};