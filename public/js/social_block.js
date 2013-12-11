function SocialBlock (parentBlock, socialName, socialType, imageName) {	
	this.parentBlock = parentBlock;
	this.socialName = socialName;
	this.imageName = imageName;
	this.linktext = socialName;
	this.socialType = socialType;
	this.selected = true;
}

SocialBlock.prototype.setParentBlock = function(parentBlock) {
	this.parentBlock = parentBlock;
};

SocialBlock.prototype.persist = function() {
	var obj = {};
	
	obj.linktext = this.linktext;
	obj.selected = this.selected;
	obj.socialName = this.socialName;
	obj.imageName = this.imageName;
	obj.socialType = this.socialType;
	obj.url = this.url;
	
	return obj;
};

SocialBlock.prototype.unpersist = function(obj) {
	
	this.linktext = obj.linktext;
	this.selected = obj.selected;
	this.socialName = obj.socialName;
	this.imageName = obj.imageName;
	this.socialType = obj.socialType;
	this.url = obj.url;

};

SocialBlock.prototype.createSocialNetHtml = function() {
	
	var text = '';
	
	if(this.socialType == 'share') {
		
		text = $("<div class=\"soc_net_share button_" + this.socialName + " pull-center\"><img data-toggle=\"modal\" href=\"#socialnetwork\" class=\"media-object\" src=\"" + config.imagesUrl + "/" + this.imageName + "_" + this.socialType + ".png\" alt=\"64x64\" /></div>");
	}
	else {
		text = $("<div class=\"soc_net_follow button_" + this.socialName + " pull-center\"><img data-toggle=\"modal\" href=\"#socialnetwork\" class=\"media-object\" src=\"" + config.imagesUrl + "/" + this.imageName + "_" + this.socialType + ".png\" alt=\"64x64\" /></div><div class=\"link_text\">" + this.linktext + "</div>");
	}	
	
	this.htmlData = text;
	
	if(this.selected) {
		var forcontent = this.socialName.replace(" ","_");
		this.parentBlock.find('.content_' + forcontent).empty();
		this.parentBlock.find('.content_' + forcontent).append(text);
	}
};

SocialBlock.prototype.showSocialInfo = function() {

	if(this.url == undefined) {
		
		this.setUrlByDefault();
	}	
	
	if(this.socialType == 'follow') {
		
		var htmlTitle = ('<h3>Social Follow</h3>')
		var htmlText = this.showSocialFollow();
	}
	else {
		
		var htmlTitle = ('<h3>Social Share</h3>')
		var htmlText = this.showSocialShare();
	}
	
	$('#socialData').append(htmlText);
	
	$('#social_title').empty();
	$('#social_title').append(htmlTitle);
};

SocialBlock.prototype.showSocialFollow = function() {
	
	var checked = '';
	
	if(this.selected) {
		checked = 'checked';
	}
		
	var htmlText = $("<div class=\"socialComponent clearfix\">\n\
						<div class=\"imageSocial\">\n\
							<img src=\"" + config.imagesUrl + "/" + this.imageName + "_" + this.socialType + ".png\" /><label>" + this.socialName.charAt(0).toUpperCase() + this.socialName.slice(1) + "</label>\n\
						</div>\n\
						<div class=\"infoSocial\">\n\
							<div class=\"titleSocial\"><label>Texto del Link</label><input class=\"titlelink\" type=\"text\" value=\"" + this.linktext + "\"></div>\n\
							<div class=\"urlSocial\"><label>Url de Pagina</label><input class=\"urllink\" type=\"text\" value=\"" + this.url + "\"></div>\n\
						</div>\n\
						<div class=\"asignSocial\">\n\
							<label class=\"checkbox\"><input " + checked + " class=\"target\" type=\"checkbox\"></label>\n\
						</div>\n\
					</div>");
	
	var t = this;
	
	$(htmlText.find('.titlelink')).on('change', function(){
		
		var forcontent = t.socialName.replace(" ","_");
		
		var contentText = t.parentBlock.find('.content_' + forcontent + ' .link_text');
		
		contentText.empty();
		
		contentText.append($(this).val());
		
		t.linktext = $(this).val();
	});	
	
	$(htmlText.find('.urllink')).on('change', function(){
		
		t.url = $(this).val();
	});	
	
	this.selectSocial(htmlText);
	
	return htmlText;
};

SocialBlock.prototype.showSocialShare = function() {
	
	var checked = '';
	
	if(this.selected) {
		checked = 'checked';
	}
	
	var htmlText = $("<div class=\"socialComponent clearfix\">\n\
						<div class=\"imageSocial\">\n\
							<img src=\"" + config.imagesUrl + "/" + this.imageName + "_" + this.socialType + ".png\" />\n\
						</div>\n\
						<div class=\"infoSocial\">\n\
							<div class=\"titleSocial\">" + this.linktext + "</div>\n\
						</div>\n\
						<div class=\"asignSocial\">\n\
							<label class=\"checkbox\"><input " + checked + " class=\"target\" type=\"checkbox\"></label>\n\
						</div>\n\
					</div>");
	
	this.selectSocial(htmlText);
	
	return htmlText;
};

SocialBlock.prototype.selectSocial = function(htmlText) {
	
	var t = this;
	
	$(htmlText.find('.target')).on('change', function(){
		
		if($(this).is(':checked')) {
			
			var forcontent = t.socialName.replace(" ","_");
			
			t.parentBlock.find('.content_' + forcontent).append(t.htmlData);
			
			t.selected = true;
		}
		else {

			t.htmlData.remove();
			
			t.selected = false;
		}
	});
};

SocialBlock.prototype.setUrlByDefault = function() {
	
	var url = "";
	
	switch(this.socialName)
	{
		case 'Facebook':
			url = "http://www.facebook.com";
			break;
		case 'Twitter':
			url = "http://www.twitter.com";
			break;
		case 'Google Plus':
			url = "http://plus.google.com";
			break;
		case 'LinkedIn':
			url = "http://www.linkedin.com";
			break;	
	}
	
	this.url = url;
};
