function BoxedTextBlock(block) {
	this.block = block;
}

BoxedTextBlock.prototype.activateFields = function() {
	$('#boxbgcolor').colorpicker('setValue', this.block.boxedcolor);
	$('#field-boxbgcolor').val(this.block.boxedcolor);
	$('#boxbordercolor').colorpicker('setValue', this.block.boxedbrcolor);
	$('#field-boxbordercolor').val(this.block.boxedbrcolor);
	$('#boxborderstyle').val(this.block.boxedbrstyle);
	$('#boxborderwidth').val(this.block.boxedbrwidth);
	$('#boxborderradius').val(this.block.boxedbrradius);
	
	
	this.activateEvents();
};

BoxedTextBlock.prototype.activateEvents = function() {
	var t = this;
	var bgcolor = this.block.boxedcolor;
	var bordercolor = this.block.boxedbrcolor;
	var borderstyle = this.block.boxedbrstyle;
	var borderwidth = this.block.boxedbrwidth;
	var borderradius = this.block.boxedbrradius;

	$('#boxbgcolor').colorpicker().on('changeColor', function(ev){
		t.block.htmlData.css('background-color', ev.color.toHex());
		bgcolor = ev.color.toHex();
	});
	$('#boxbordercolor').colorpicker().on('changeColor', function(ev){
		t.block.htmlData.css('border-color', ev.color.toHex());
		bordercolor = ev.color.toHex();
	});
	
	$('#boxborderstyle').on('change', function() {
		t.block.htmlData.css('border-style', $(this).val());
		borderstyle = $(this).val();
	});
	
	$('#boxborderwidth').on('change', function() {
		t.block.htmlData.css('border-width', $(this).val());
		borderwidth = $(this).val();
	});
	
	$('#boxborderradius').on('change', function() {
		t.block.htmlData.css('border-radius', $(this).val());
		borderradius = $(this).val();
	});
	
	$('#acceptboxtext').on('click', function() {
		t.block.boxedcolor = bgcolor;
		t.block.boxedbrcolor = bordercolor;
		t.block.boxedbrstyle = borderstyle;
		t.block.boxedbrwidth = borderwidth;
		t.block.boxedbrradius = borderradius;
		t.cleanEventsBox();
	});
	
	$('#cancelboxtext').on('click', function() {
		t.designBox();
		t.cleanEventsBox();
	});
	
	this.colorField('boxbgcolor');
	this.colorField('boxbordercolor');
};

BoxedTextBlock.prototype.designBox = function() {
	this.block.htmlData.css('background-color', this.block.boxedcolor);
	this.block.htmlData.css('border-color', this.block.boxedbrcolor);
	this.block.htmlData.css('border-style', this.block.boxedbrstyle);
	this.block.htmlData.css('border-width', this.block.boxedbrwidth);
	this.block.htmlData.css('border-radius', this.block.boxedbrradius);
};

BoxedTextBlock.prototype.cleanEventsBox = function() {
	$('#boxbgcolor').colorpicker().off('changeColor');
	$('#boxbordercolor').colorpicker().off('changeColor');
	$('#boxborderstyle').off('change');
	$('#boxborderwidth').off('change');
	$('#boxborderradius').off('change');
	$('#acceptboxtext').off('click');
	$('#cancelboxtext').off('click');
};

BoxedTextBlock.prototype.colorField = function(field) {
	$('#field-' + field).on('change', function(){
		$('#' + field).colorpicker('setValue', $(this).val());
	});
};