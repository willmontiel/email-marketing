function Block (parentBlock, typeBlock, contentData, htmlData) {
	this.parentBlock = parentBlock;
	this.typeBlock = typeBlock;
	this.contentData = contentData;
	this.htmlData = htmlData;
	
	if(typeBlock != undefined && typeBlock.search('social') > 0) {
		
		this.createSocialBlocks();
	}
	else if(typeBlock != undefined && typeBlock.search('button') > 0) {
		
		this.createButtonBlock();
	}
	else if(typeBlock != undefined && typeBlock.search('text-boxed') > 0) {
		
		this.createTextBoxed();
	}
	else if(typeBlock != undefined && typeBlock.search('text') > 0) {
		
		this.createText();
	}
		
	newRedactor();
}

function newRedactor() {
	$('.content-text').on('click', function() {
		var t = this;
		
		if (!$(t).hasClass('redactor_editor')) {
			
			$('.redactor_editor').destroyEditor();
			
			$(t).redactor({
				focus: true,
				buttons: [
					'save', '|', 'formatting', '|', 
					'bold', 'italic', 'deleted', '|', 
					'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 
					'link', '|', 
					'alignment' 
				],

				plugins: ['fontcolor', 'fontfamily', 'fontsize', 'clips'],

				buttonsCustom: {
					save: {
						title: 'save',
						callback: function() {
							$(t).destroyEditor();
						}
					}
				}
			});
		}
	});
}

Block.prototype.deleteBlock = function() {
	for (var key in this)
      delete this[key];	
};

Block.prototype.setHtmlData = function(htmlData) {
	
	this.htmlData = htmlData;
	
	if(this.typeBlock.search('social') > 0) {
		
		for(var i = 0; i < this.contentData.length; i++) {
			
			this.contentData[i].setParentBlock(this.htmlData);
			
			this.contentData[i].createSocialNetHtml();
		}
		
		this.createSocialBlocks();
	}
};

Block.prototype.persist = function() {
	
	var obj = {
			type: this.typeBlock.match(/module-[^ ]*/)[0]
		};
	
	if(this.typeBlock.search('text') > 0 && this.typeBlock.search('image') > 0) {
		obj.contentData = {image: $.trim(this.contentData.image.html()), text: $.trim(this.contentData.text.html())};
	}
	else if(this.typeBlock.search('social') > 0) {
		var sb = [];
		
		for(var i = 0; i < this.contentData.length; i++) {
			sb[i] = this.contentData[i].persist()
		}
		obj.contentData = sb;
	}
	else if(this.typeBlock.search('button') > 0) {
		obj.text = this.btntext;
		obj.link = this.btnlink;
		obj.bgcolor = this.btnbgcolor;
		obj.textcolor = this.btntextcolor;
		obj.withborderradius = this.btnwithborderradius;
		obj.radius = this.btnradius;
		obj.withbordercolor = this.btnwithbordercolor;
		obj.bordercolor = this.btnbordercolor;
		obj.withbgimage = this.btnwithbgimage;
		obj.bgimage = this.btnbgimage;
		obj.width = this.btnwidth;
		obj.height = this.btnheight;
		obj.align = this.btnalign;
		obj.fontsize = this.btnfontsize;
		obj.fontfamily = this.btnfontfamily;
	}
	else if(this.typeBlock.search('text-boxed') > 0) {
		obj.boxedcolor = this.boxedcolor;
		obj.boxedbrcolor = this.boxedbrcolor;
		obj.boxedbrstyle = this.boxedbrstyle;
		obj.boxedbrwidth = this.boxedbrwidth;
		obj.boxedbrradius = this.boxedbrradius;
		obj.contentData = $.trim(this.contentData.html());	
	}
	else if(this.typeBlock.search('text-mult') > 0) {
		var txt = [];
		
		for(var i = 0; i < this.contentData.length; i++) {
			txt[i] = $.trim($(this.contentData[i]).html());
		}
		obj.contentData = txt;
	}
	else {
		obj.contentData = $.trim(this.contentData.html());	
	}
	
	if(this.hasOwnProperty('height') && this.hasOwnProperty('width')) {
		obj.height = this.height;
		obj.width = this.width;
		obj.displayer = this.displayer;
		obj.align = this.align;
		obj.vertalign = this.vertalign;
		obj.imglink = this.imglink;
		obj.widthZone = this.widthZone;
	}

	return obj;
};

Block.prototype.unpersist = function(obj, dz) {
	
	if(obj.hasOwnProperty('height') && obj.hasOwnProperty('width') && obj.hasOwnProperty('displayer')) {
		this.displayer = obj.displayer;
		this.height = obj.height;
		this.width = obj.width;
		this.align = obj.align;
		this.vertalign = obj.vertalign;
		this.imglink = obj.imglink;
		this.widthZone = obj.widthZone;
	}
	
	this.typeBlock = obj.type;
	this.parentBlock = dz;
	
	if(this.typeBlock.search('text') > 0 && this.typeBlock.search('image') > 0) {
		
		var contentText = $('<div class="content-text"></div>');
		contentText = contentText.html(obj.contentData.text);
		
		var contentImage = $('<div class="content-image ' + obj.align + '"></div>');
		contentImage = contentImage.html(obj.contentData.image);
		
		var table = $('<table><tr></tr><table/>');
		
		var imgWidth = (this.parentBlock.$obj.width() != 0) ? this.width * this.parentBlock.$obj.width() / this.widthZone : this.width;
		
		if(this.typeBlock.search('text-image') > 0) {
			var column1 = $('<td/>');
			var column2 = $('<td width="' + imgWidth + '" style="vertical-align: ' + obj.vertalign + ';"></td>');
			
			column1 = column1.append(contentText);
			column2 = column2.append(contentImage);
		}
		else {
			var column1 = $('<td width="' + imgWidth + '" style="vertical-align: ' + obj.vertalign + ';"></td>');
			var column2 = $('<td/>');
			
			column1 = column1.append(contentImage);
			column2 = column2.append(contentText);
		}
		
		table.find('tr').append(column1);
		table.find('tr').append(column2);
		
		contentData = $('<div/>');
		contentData = contentData.append(table);
		
		this.contentData = {image: contentImage, text: contentText};
	}
	else if(this.typeBlock.search('text-mult') > 0) {
		var table = $('<table><tr></tr><table/>');
		
		var multvalues = '';
		var contentvalues = [];
		for(var i = 0; i < obj.contentData.length; i++) {
			var value = $('<div class="content-text full-content" style="float: left;">' + obj.contentData[i] + '</div>')
			contentvalues.push(value);
			table.find('tr').append($('<td style="width:' + Math.floor(100/obj.contentData.length) + '%"/>').append(value));
		}
		
		table.find('tr').append(multvalues);
		
		var contentData = $('<div/>');
		contentData = contentData.html(table);
		
		this.contentData = contentvalues;
		
	}
	else if(this.typeBlock.search('text') > 0) {		
		var table = $('<table><tr><td></td></tr><table/>');
		
		var value = $('<div class="content-text full-content" style="float: left;">' + obj.contentData + '</div>');

		table.find('td').append(value);
		
		var contentData = $('<div/>');
		contentData = contentData.html(table);

		this.contentData = value;
	}
	else if(this.typeBlock.search('image') > 0){
		var contentData = $('<div/>');
		contentData = contentData.html('<div class="content-image full-content ' + obj.align + '">' + obj.contentData + '</div>');
		
		this.contentData = contentData.children();
	}
	else if(this.typeBlock.search('social') > 0) {
		var contentData = $('<div/>');
		contentData = contentData.html('<div class="sub_social_content content_facebook"></div> \n\
										<div class="sub_social_content content_twitter"></div>\n\
										<div class="sub_social_content content_linkedin"></div>\n\
										<div class="sub_social_content content_google_plus"></div>');
	}
	else if(this.typeBlock.search('button') > 0) {
		var contentData = $('<div/>');
		contentData = contentData.html('<span data-toggle="modal" data-backdrop="static" href="#buttonaction" class="content-button pull-center" style="background-image:url(../images/btn-blue.png);border:1px solid #1e3650;border-radius:4px;">Clic Aqui!</span>')
	}
	else {
		var contentData = $('<div/>');
		contentData = contentData.html('<hr>');
		
		this.contentData = contentData.children();
	}
	
	this.htmlData = $('<div/>').html(
						"<div class=\"module " + this.typeBlock + " ui-draggable\" style=\"display: block;\">\n\
						<div class=\"tools\" style=\"float: left;\">\
							<div class=\"handle-tool icon-move tool\"></div>\
							<div class=\"remove-tool icon-trash tool\"></div>\
						</div>\
						<div class=\"content clearfix\"></div></div>").children();
	
	this.htmlData.find('.content').append(contentData.children());

	this.htmlData.data('smobj', this);
	
	if(this.typeBlock.search('social') > 0) {
			
		this.contentData = [];
		
		for(var i = 0; i<obj.contentData.length; i++) {
			
			var newsociblk = new SocialBlock(this.htmlData);
	
			newsociblk.unpersist(obj.contentData[i]);
			newsociblk.createSocialNetHtml();
			
			this.contentData.push(newsociblk);
			
		}
		
		this.createSocialBlocks();
	}
	else if(this.typeBlock.search('button') > 0) {
		
		this.unpersistButton(obj);
	}
	else if(this.typeBlock.search('text-boxed') > 0) {
		
		this.unpersistTextBoxed(obj);
	}
	else if(this.typeBlock.search('text') > 0) {
		this.htmlData.find('.tools').append('<div class="add-column-tool icon-plus tool"></div>	<div class="remove-column-tool icon-minus tool"></div>');
		this.setRowWidth();
		this.createText();
	}
};

Block.prototype.createBlock = function() {
	return this.htmlData;
};

Block.prototype.createImage = function() {
	
	switch (this.parentBlock.width) {
		case 'full-width':
			this.widthZone =  550;
			break;
		case 'half-width':
			this.widthZone = 550/2;
			break;
		case 'third-width':
			this.widthZone = Math.floor(550/3);
			break;
		case 'twothird-width':
			this.widthZone = Math.floor(550*2/3);
			break;
	}
	
	media.setBlock(this);
	media.Selected(this.htmlData.find('img').attr('src'));	
};

Block.prototype.assignDisplayer = function(displayer) {
	
	this.displayer = displayer;
};

Block.prototype.setSizeImage = function(height, width) {
	
	this.height = Math.floor(height);
	this.width = Math.floor(width);
};

Block.prototype.setTableColumn = function(name, value) {
	
	if(this.typeBlock.search('image-only') < 0 ) { 
		
		var realValue = value * this.parentBlock.$obj.width() / this.widthZone;
		
		this.htmlData.find('img').closest('td').attr(name, realValue);
	}
	
};

Block.prototype.setAlignImgBlock = function(align) {
	
	this.align = align;
};

Block.prototype.setLinkToImage = function(link) {
	
	this.imglink = link;
};

Block.prototype.addVerticalAlignToImage = function(vertalign) {
	
	this.htmlData.find('img').closest('td').css('vertical-align', vertalign);
};

Block.prototype.setVerticalAlignImgBlock = function(vertalign) {
	
	this.vertalign = vertalign;
};

Block.prototype.changeAttrImgBlock = function(attr, value) {
	
	if(attr == 'width' || attr == 'height') {
		
		value = value * this.parentBlock.$obj.width() / this.widthZone
	}
	this.htmlData.find('img').attr(attr, value);
};

Block.prototype.unpersistImage = function(obj) {

	if(obj != undefined) {
		
		var objimage = JSON.parse(obj);

		this.height = objimage.height;
		this.width = objimage.width;
		this.align = objimage.align;
		this.vertalign = objimage.vertalign;
		this.imglink = objimage.imglink;
		this.displayer.height = objimage.heightDisplayer;
		this.displayer.width = objimage.widthDisplayer;
		this.displayer.imagesrc = objimage.imagesrc;
		this.displayer.percent = objimage.percent;

		this.changeAttrImgBlock('height', this.height);
		this.changeAttrImgBlock('width', this.width);
		this.changeAttrImgBlock('src', this.displayer.imagesrc);
		this.addClassContentImgBlock(this.align);
		this.addVerticalAlignToImage(this.vertalign);
	}
	else {
		delete this.height;
		delete this.width;
		delete this.align;
		delete this.vertalign;
		delete this.displayer;

		this.htmlData.find('img').removeAttr('height');
		this.htmlData.find('img').removeAttr('width');
		this.htmlData.find('img').removeAttr('src');
		this.htmlData.find('img').removeAttr('alt');
		
		if(this.typeBlock.search('image-only') > 0) {
			
			this.htmlData.find('img').addClass('image-placeholder');
		}
		else {
			this.htmlData.find('img').addClass('image-text-placeholder');
		}
	}
};

Block.prototype.unpersistButton = function(obj) {
	this.btntext = obj.text;
	this.btnlink = obj.link;
	this.btnbgcolor = obj.bgcolor;
	this.btntextcolor = obj.textcolor;
	this.btnwithborderradius = obj.withborderradius;
	this.btnradius = obj.radius;
	this.btnwithbordercolor = obj.withbordercolor;
	this.btnbordercolor = obj.bordercolor;
	this.btnwithbgimage = obj.withbgimage;
	this.btnbgimage = obj.bgimage;
	this.btnwidth = obj.width;
	this.btnheight = obj.height;
	this.btnalign = obj.align;
	this.btnfontsize = obj.fontsize;
	this.btnfontfamily = obj.fontfamily;
	
	var btn = new BtnBlock(this);
	btn.designBtn();
	this.createButtonBlock();
};

Block.prototype.persistImage = function() {
	
	var obj = {	height: this.height,
				width: this.width, 
				align: this.align,
				vertalign: this.vertalign,
				imagesrc: this.displayer.imagesrc,
				imglink: this.imglink,
				heightDisplayer: this.displayer.height, 
				widthDisplayer: this.displayer.width, 
				percent: this.displayer.percent };
	
	return obj;
};

Block.prototype.addClassContentImgBlock = function(value) {
	var content = this.htmlData.find('.content-image');
	
	content.removeClass('pull-center');
	content.removeClass('pull-left');
	content.removeClass('pull-right');
	
	content.addClass(value);
};

Block.prototype.setMediaDisplayer = function() {
	
	if(this.typeBlock.search("image") > 0) {
		
		this.createImage();
	}
	else {
		NoMediaDisplayer();
	}
};

Block.prototype.createSocialBlocks = function() {
	
	if(!$.isArray(this.contentData)) {
		
		var socials = [];
		var socialType = '';
		var socialsNames = ['Facebook', 'Twitter', 'LinkedIn', 'Google Plus'];
		var imagesNames = ['facebook_image', 'twitter_image', 'linkedin_image', 'google_plus_image'];

		if(this.typeBlock.search('share') > 0) {
			socialType = 'share';
		}
		else {
			socialType = 'follow';
		}

		for(var i = 0; i < 4; i++) {
			socials[i] = new SocialBlock(this.htmlData, socialsNames[i], socialType, imagesNames[i]);
			socials[i].createSocialNetHtml();
		}
		this.contentData = socials;
	} 
	
	var t = this;
	
	this.htmlData.on('click', function() {

		$('#socialData').empty();
		
		if(t.contentData != undefined) {
			
			for(var i = 0; i < t.contentData.length; i++) {

				t.contentData[i].showSocialInfo();
			}
		}
	});
};

Block.prototype.createButtonBlock = function() {

	this.btntext = (this.btntext != undefined) ? this.btntext : 'Clic Aqui!';
	this.btnlink = (this.btnlink != undefined) ? this.btnlink : '';
	this.btnbgcolor = (this.btnbgcolor != undefined) ? this.btnbgcolor : '#556270';
	this.btntextcolor = (this.btntextcolor != undefined) ? this.btntextcolor : '#ffffff';;
	this.btnwithborderradius = (this.btnwithborderradius != undefined) ? this.btnwithborderradius : true;
	this.btnradius = (this.btnradius != undefined) ? this.btnradius : 4;
	this.btnwithbordercolor = (this.btnwithbordercolor != undefined) ? this.btnwithbordercolor : true;
	this.btnbordercolor = (this.btnbordercolor != undefined) ? this.btnbordercolor : '#1e3650';
	this.btnwithbgimage = (this.btnwithbgimage != undefined) ? this.btnwithbgimage : true;
	this.btnbgimage = (this.btnbgimage != undefined) ? this.btnbgimage : 'blue';
	this.btnwidth = (this.btnwidth != undefined) ? this.btnwidth : 120;
	this.btnheight = (this.btnheight != undefined) ? this.btnheight : 40;
	this.btnalign = (this.btnalign != undefined) ? this.btnalign : 'center';
	this.btnfontsize = (this.btnfontsize != undefined) ? this.btnfontsize : 14;
	this.btnfontfamily = (this.btnfontfamily != undefined) ? this.btnfontfamily : 'arial';	
	
	var t = this;
	
	this.htmlData.on('click', function() {
		var btn = new BtnBlock(t);
		btn.createFields();
	});
};

Block.prototype.createTextBoxed = function() {
	this.boxedcolor = (this.boxedcolor != undefined) ? this.boxedcolor : '#ebebeb';
	this.boxedbrcolor = (this.boxedbrcolor != undefined) ? this.boxedbrcolor : '#999999';
	this.boxedbrstyle = (this.boxedbrstyle != undefined) ? this.boxedbrstyle : 'solid';
	this.boxedbrwidth = (this.boxedbrwidth != undefined) ? this.boxedbrwidth : 1;
	this.boxedbrradius = (this.boxedbrradius != undefined) ? this.boxedbrradius : 0;
	
	var bxtxt = new BoxedTextBlock(this);
	bxtxt.designBox();

	this.htmlData.find('.edit-box-tool').on('click', function() {
		bxtxt.activateFields();
	});
};

Block.prototype.unpersistTextBoxed = function(obj) {
	this.boxedcolor = obj.boxedcolor;
	this.boxedbrcolor = obj.boxedbrcolor;
	this.boxedbrstyle = obj.boxedbrstyle;
	this.boxedbrwidth = obj.boxedbrwidth;
	this.boxedbrradius = obj.boxedbrradius;
	
	var bxtxt = new BoxedTextBlock(this);
	bxtxt.designBox();
	
	this.htmlData.find('.tools').append('<span data-toggle="modal" href="#boxedtext" class="edit-box-tool icon-pencil tool"></span>')
	
	this.htmlData.find('.edit-box-tool').on('click', function() {
		bxtxt.activateFields();
	});
};

Block.prototype.createText = function() {
	var t = this;
	
	this.htmlData.find('.add-column-tool').on('click', function() {
		
		var contentData = $("<div class='content-text full-content' style='float: left;'><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div>");

		t.contentData.push(contentData[0]);
		
		t.typeBlock = "module module-text-mult ui-draggable";

		t.htmlData.find('.content table tr').append('<td/>');
		t.htmlData.find('.content table tr td:last').append(contentData);
		
		t.htmlData.find('.remove-column-tool').show();
		
		t.setRowWidth();
		
		newRedactor();
	});
	
	this.htmlData.find('.remove-column-tool').on('click', function() {
		t.htmlData.find('.content table tr td:last').remove();
		
		t.contentData.splice(t.contentData.length - 1, 1);
		
		t.setRowWidth();
	});
	
	this.htmlData.find('.add-image-tool').on('click', function() {
		var contentData = $('<div class="content-image full-content pull-left"><img data-toggle="modal" data-backdrop="static" href="#images" class="media-object image-placeholder" /></div>');
		
		t.contentData.push(contentData[0]);
		
		t.typeBlock = "module module-text-mult ui-draggable";

		t.htmlData.find('.content table tr').append('<td/>');
		t.htmlData.find('.content table tr td:last').append(contentData);
		
		t.htmlData.find('.remove-column-tool').show();
		
		t.setRowWidth();
	});
};

Block.prototype.setRowWidth = function() {

	for(var i = 0; i < this.htmlData.find('.content table tr td').length; i++) {
		this.htmlData.find('.content table tr td').css('width', Math.floor(100/this.htmlData.find('.content table tr td').length) + '%');
	}
	

	if(this.htmlData.find('.content-text').length === 5) {
		this.htmlData.find('.add-column-tool').hide();
		this.htmlData.find('.remove-column-tool').show();
		this.htmlData.find('.remove-column-tool').css('left', '32px');
	}
	else if(this.htmlData.find('.content-text').length === 1) {
		this.htmlData.find('.add-column-tool').show();
		this.htmlData.find('.remove-column-tool').hide();
	}
	else {
		this.htmlData.find('.add-column-tool').show();
		this.htmlData.find('.remove-column-tool').css('left', '48px');
		this.htmlData.find('.remove-column-tool').show();
	}
	
};
