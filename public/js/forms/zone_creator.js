function ZoneCreator() {}

ZoneCreator.prototype.designFieldEditZone = function(extraClass) {
	this.zone = $('<div class="field-edit-zone-row ' + extraClass + '">\n\
					<ul class="field-edit-zone">\n\
					</ul>\n\
				</div>\n\
				<div class="clearfix"></div>');
};

ZoneCreator.prototype.getZone = function() {
	return this.zone;
};

ZoneCreator.prototype.designSaveBtn = function() {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-button-row-in-zone"><a class="form-opt-field-container accept-form-field-changes btn btn-default btn-guardar btn-sm"><span class="glyphicon glyphicon-ok"></span></a></li>')
};

ZoneCreator.prototype.designNameField = function(name) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone"><div class="form-opt-field-container"><div class="field-text-label-form">Label:</div><div class="field-input-form"><input type="text" class="form-control field-label-name" value="' + name + '"></div></div></li>');
};

ZoneCreator.prototype.designPlaceholderField = function(placeholder) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone"><div class="form-opt-field-container"><div class="field-text-label-form">Placeholder:</div><div class="field-input-form"><input type="text" class="form-control field-placeholder" value="' + placeholder + '"></div></div></li>');
};

ZoneCreator.prototype.designRequiredField = function(required) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone"><div class="form-opt-field-container"><label class="btn btn-default btn-sm required-form-btn ' + required + '" for="check-required-input"><span class="glyphicon glyphicon-asterisk"><input id="check-required-input" type="checkbox" class="field-required-option" ' + required + '></span></label><div class="field-input-form"></div></div></li>');
};

ZoneCreator.prototype.designHideOptField = function(hide) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone"><div class="form-opt-field-container"><div><label for="field-hide-option">Oculto:</label></div><div class="field-input-form hide-form-checkinput"><input id="field-hide-option" type="checkbox" class="field-hide-option" ' + hide + '></div></div></li>');
};

ZoneCreator.prototype.designHideTxtValueField = function(defaultvalue, input) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone ' + defaultvalue + '"><div class="form-opt-field-container"><div class="field-input-form">' + input + '</div></div></li>');
};

ZoneCreator.prototype.designHideSelectValueField = function(defaultvalue, slctoptions, mult) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone ' + defaultvalue + '"><div class="form-opt-field-container"><div class="field-input-form"><select class="form-control field-default-value" ' + mult + '>' + slctoptions + '</select></div></div></li>');
};

ZoneCreator.prototype.designHideDateValueField = function(defaultvalue, days, months, year) {
	this.zone.find('ul.field-edit-zone').append('<li class="edit-row-in-zone ' + defaultvalue + '"><div class="field-default-value"><select class="form-control select-day-number-form">' + days + '</select></div><div class="field-default-value"><select class="form-control select-month-number-form">' + months + '</select></div><div class="field-default-value"><input type="text" class="form-control select-year-number-form" value="' + year + '"></div></li>');
};

ZoneCreator.prototype.designFontOptField = function(size, family, bold) {
	var font = $('<li class="edit-row-in-zone"><div class="field-default-value"><select class="form-control field-font-size">' + size + '</select></div><div class="field-default-value"><select class="form-control field-font-family">' + family + '</select></div><div class="font-property-btn font-bold-form ' + bold + '" for="form-font-bold"><span class="glyphicon glyphicon-bold"><input id="form-font-bold" type="checkbox"></span></div></li>');
	this.zone.find('ul.field-edit-zone').append(font);
	return font;
};

ZoneCreator.prototype.designFontAlignField = function(left, center, right) {
	var align = $('<li class="edit-row-in-zone"><label class="font-property-btn align-toolbar-form align-toolbar-form-left ' + left + '" for="form-font-align-left"><span class="glyphicon glyphicon-align-left"><input id="form-font-align-left" val="left" type="checkbox"></span></label><label class="font-property-btn align-toolbar-form align-toolbar-form-center ' + center + '" for="form-font-align-center"><span class="glyphicon glyphicon-align-center"><input id="form-font-align-center" val="center" type="checkbox"></span></label><label class="font-property-btn align-toolbar-form align-toolbar-form-right ' + right + '" for="form-font-align-right"><span class="glyphicon glyphicon-align-right"><input id="form-font-align-right" val="right" type="checkbox"></span></label></li>')
	this.zone.find('ul.field-edit-zone').append(align);
	return align;
};

