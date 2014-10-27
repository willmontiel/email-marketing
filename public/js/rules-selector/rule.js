function Rule(config) {
	this.config = config;
}

Rule.prototype.setContainer = function(container) {
	this.container = container;
};

Rule.prototype.createRule = function() {
	this.html = $('<div class="row base"><hr></div>');
	
	this.createPart1();
	this.createPart2();
	this.createPart3();
	this.createPart4();
	this.createPart5();
	this.createPart6();

	this.html.append(this.part1);
	this.html.append(this.part2);
	this.html.append(this.part3);
	this.html.append(this.part4);
	this.html.append(this.part5);
	this.html.append(this.part6);
	
	var self = this;
	
	this.html.find('.add-rule').on('click', function (e) {
		var config;
		self.container.addRule(config);
	});
	
	this.html.find('.remove-rule').on('click', function (e) {
		self.remove(e);
	});
	
	this.container.element.find('#rules-content').append(this.html);
//	this.html.find('.sgm-panel').animate({width: '100%'});
};

Rule.prototype.createPart1 = function () {
	this.part1 = $('<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">\n\
						<select class="select2" name="" style="width:100%">\n\
							<option value="opens">Aperturas</option>\n\
							<option value="bounced">Rebotes</option>\n\
							<option value="unsubscribed">Des-suscritos</option>\n\
							<option value="spam">Quejas de spam</option>\n\
						</select>\n\
					</div>');
};

Rule.prototype.createPart2 = function () {
	this.part2 = $('<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">\n\
						<select class="select2" name="" style="width:100%">\n\
							<option value="<"><</option>\n\
							<option value=">">></option>\n\
							<option value="=">=</option>\n\
							<option value="!=">!=</option>\n\
						</select>\n\
					</div>	');
};

Rule.prototype.createPart3 = function () {
	this.part3 = $('<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">\n\
						<div class="" style="display: inline-flex;">\n\
							<input type="text" style="width: 30px;"/>% \n\
						</div>\n\
					</div>');
};

Rule.prototype.createPart4 = function () {
	this.part4 = $('<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">\n\
						<div class="" style="display: inline-flex;">\n\
							<input type="checkbox" /> Puntos\n\
						</div>\n\
					</div>');
};

Rule.prototype.createPart5 = function () {
	this.part5 = $('<div class="col-xs-11 col-sm-1 col-md-1 col-lg-1">\n\
						<div class="" style="display: inline-flex;">\n\
							<input type="text" style="width: 30px;"/>\n\
						</div>\n\
					</div>');
};

Rule.prototype.createPart6 = function () {
	this.part6 = $('<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 action-buttons">\n\
						<div class="remove-rule">-</div>\n\
						<div class="add-rule">+</div>\n\
					</div>');
};

Rule.prototype.remove = function (e) {
	this.container.removeRule(this);
	this.html.remove();
};