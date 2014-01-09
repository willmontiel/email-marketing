function BtnBlock(block) {
	this.block = block;
}

BtnBlock.prototype.createFields = function() {
		
	$('#btntext').val(this.block.btntext);
	$('#btnlink').val(this.block.btnlink);
	$('#btnbgcolor').colorpicker();
	$('#btntextcolor').colorpicker();
	$('#btnradius').val(this.block.btnradius);
	$('#btnbordercolor').colorpicker();
	$('#btnbgimage').val(this.block.btnbgimage);
	$('#btnwidth').val(this.block.btnwidth);
	$('#btnheight').val(this.block.btnheight);
	
	this.colorField('btnbgcolor');
	this.colorField('btntextcolor');
	this.colorField('btnbordercolor');
	
	var t = this;
	$('#savebtndata').on('click', function() {
		t.saveBtn();
		t.designBtn();
	});
};

BtnBlock.prototype.saveBtn = function() {
	this.block.btntext = $('#btntext').val();
	this.block.btnlink = $('#btnlink').val();
	this.block.btnbgcolor = $('#field-btnbgcolor').val();
	this.block.btntextcolor = $('#field-btntextcolor').val();
	this.block.btnradius = $('#btnradius').val();
	this.block.btnbordercolor = $('#field-btnbordercolor').val();
	this.block.btnbgimage = $('#btnbgimage').val();
	this.block.btnwidth = $('#btnwidth').val();
	this.block.btnheight = $('#btnheight').val();
};

BtnBlock.prototype.designBtn = function() {
	var content = this.block.htmlData.find('.content-button');
	content.text(this.block.btntext);
	content.css('background-color', this.block.btnbgcolor);
	content.css('color', this.block.btntextcolor);
	content.css('border-radius', this.block.btnradius);
	content.css('border-color', this.block.btnbordercolor);
	content.css('width', this.block.btnwidth);
	content.css('height', this.block.btnheight);
};

BtnBlock.prototype.colorField = function(field) {
	$('#field-' + field).on('change', function(){
		$('#' + field).colorpicker('setValue', $(this).val());
	});
};
