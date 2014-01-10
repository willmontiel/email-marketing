function BtnBlock(block) {
	this.block = block;
}

BtnBlock.prototype.createFields = function() {
	
	$('#btntext').val(this.block.btntext);
	$('#btnlink').val(this.block.btnlink);
	$('#btnbgcolor').colorpicker('setValue', this.block.btnbgcolor);
	$('#field-btnbgcolor').val(this.block.btnbgcolor);
	$('#btntextcolor').colorpicker('setValue', this.block.btntextcolor);
	$('#field-btntextcolor').val(this.block.btntextcolor);
	$('#btnradius').val(this.block.btnradius);
	$('#btnbordercolor').colorpicker('setValue', this.block.btnbordercolor);
	$('#field-btnbordercolor').val(this.block.btnbordercolor);
	$('#btnbgimage').val(this.block.btnbgimage);
	$('#btnwidth').val(this.block.btnwidth);
	$('#btnheight').val(this.block.btnheight);
	$('#btnalign').val(this.block.btnalign);
	$('#btnfontsize').val(this.block.btnfontsize);
	$('#btnfontfamily').val(this.block.btnfontfamily);
	$('#withborderradius')[0].checked = this.block.btnwithborderradius;
	$('#withbordercolor')[0].checked = this.block.btnwithbordercolor;
	$('#withbgimage')[0].checked = this.block.btnwithbgimage;

	this.colorField('btnbgcolor');
	this.colorField('btntextcolor');
	this.colorField('btnbordercolor');
	
	var t = this;
	
	$('#savebtndata').off('click');
	
	$('#savebtndata').on('click', function() {
		t.saveBtn();
		t.designBtn();
		$('#savebtndata').off('click');
	});
	
	$('#cancelbtndata').on('click', function() {
		$('#savebtndata').off('click');
	});
};

BtnBlock.prototype.saveBtn = function() {
	this.block.btntext = $('#btntext').val();
	this.block.btnlink = $('#btnlink').val();
	this.block.btnbgcolor = $('#field-btnbgcolor').val();
	this.block.btntextcolor = $('#field-btntextcolor').val();
	this.block.btnwithborderradius = $('#withborderradius')[0].checked;
	this.block.btnradius = $('#btnradius').val();
	this.block.btnwithbordercolor = $('#withbordercolor')[0].checked;
	this.block.btnbordercolor = $('#field-btnbordercolor').val();
	this.block.btnwithbgimage = $('#withbgimage')[0].checked;
	this.block.btnbgimage = $('#btnbgimage').val();
	this.block.btnwidth = $('#btnwidth').val();
	this.block.btnheight = $('#btnheight').val();
	this.block.btnalign = $('#btnalign').val();
	this.block.btnfontsize = $('#btnfontsize').val();
	this.block.btnfontfamily = $('#btnfontfamily').val();
};

BtnBlock.prototype.designBtn = function() {
	var content = this.block.htmlData.find('.content-button');
	content.text(this.block.btntext);
	content.css('background-color', this.block.btnbgcolor);
	content.css('color', this.block.btntextcolor);

	if(this.block.btnwithborderradius) {
		content.css('border-radius', this.block.btnradius);
	}
	else {
		content.css('border-radius', 0);
	}
	
	if(this.block.btnwithbordercolor) {
		content.css('border-color', this.block.btnbordercolor);
		content.css('border-style', 'solid');
	}
	else {
		content.css('border-color', '');
		content.css('border-style', '');
	}
	
	if(this.block.btnwithbgimage) {
		content.css('background-image', 'url(' + config.imagesUrl + '/btn-' + this.block.btnbgimage + '.png)');
	}
	else {
		content.css('background-image', '');
	}
	
	content.css('width', this.block.btnwidth);
	content.css('height', this.block.btnheight);
	content.css('font-size', this.block.btnfontsize);
	content.css('font-family', this.block.btnfontfamily);
	
	content.removeClass('pull-center');
	content.removeClass('pull-left');
	content.removeClass('pull-right');
	content.addClass('pull-' + this.block.btnalign);
};

BtnBlock.prototype.colorField = function(field) {
	$('#field-' + field).on('change', function(){
		$('#' + field).colorpicker('setValue', $(this).val());
	});
};
