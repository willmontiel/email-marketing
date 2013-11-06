function SocialBlock (parentBlock, socialName, socialType) {
	this.parentBlock = parentBlock;
	this.socialName = socialName;
	this.socialType = socialType;
	this.selected = true;
	
	this.createSocialNetHtml();
}

SocialBlock.prototype.createSocialNetHtml = function() {
	
	var text = '';
	this.linktext = this.socialName;
	if(this.socialType == 'share') {
		text = $("<div class=\"soc_net_share button_" + this.socialName + "\"><img data-toggle=\"modal\" href=\"#socialnetwork\" class=\"media-object\" src=\"" + config.imagesUrl + "/" + this.socialName + "_button\" alt=\"64x64\" /></div><div class=\"link_text\">" + this.linktext + "</div>");
	}
	else {
		text = $("<div class=\"soc_net_follow button_" + this.socialName + "\"><img data-toggle=\"modal\" href=\"#socialnetwork\" class=\"media-object\" src=\"" + config.imagesUrl + "/" + this.socialName + "\" alt=\"64x64\" /></div>");
	}	
	
	this.htmlData = text;
	
	this.parentBlock.find('.content_' + this.socialName).append(text);
};

SocialBlock.prototype.showSocialInfo = function() {

	this.setUrlByDefault();
	
	if(this.socialType == 'follow') {
		
		var htmlText = this.showSocialFollow();
	}
	else {
		var htmlText = this.showSocialShare();
	}
	
	$('#socialData').append(htmlText);
};

SocialBlock.prototype.showSocialFollow = function() {
	
	var htmlText = $("<div class=\"title\">" + this.socialName + "</div><div><span>URL</span><div class=\"url_name\">" + this.url + "</div></div><div><span>Texto de Link</span><div class=\"link-text\">" + this.socialName + "</div></div>");
	
	return htmlText;
};

SocialBlock.prototype.showSocialShare = function() {
	
	var checked = '';
	
	if(this.selected) {
		checked = 'checked';
	}
	
	var htmlText = $("<div class=\"socialComponent clearfix\">\n\
						<div class=\"imageSocial\">\n\
							<img src=\"" + config.imagesUrl + "/" + this.socialName + "\" />\n\
						</div>\n\
						<div class=\"titleSocial\">" + this.socialName + "</div>\n\
						<div class=\"asignSocialShare\">\n\
							<label class=\"checkbox\"><input " + checked + " class=\"target\" type=\"checkbox\"></label>\n\
						</div>\n\
					</div>");
	
	var t = this;
	
	$(htmlText).on('change', function(){
		
		if($(this).find('.target').is(':checked')) {
			
			t.parentBlock.find('.content_' + t.socialName).append(t.htmlData);
			
			t.selected = true;
		}
		else {
			
			t.htmlData.remove();
			
			t.selected = false;
		}
	});
	
	
	return htmlText;
};

SocialBlock.prototype.setUrlByDefault = function() {
	
	var url = "";
	
	switch(this.socialName)
	{
		case 'facebook':
			url = "http://www.facebook.com";
			break;
		case 'twitter':
			url = "http://www.twitter.com";
			break;
		case 'google_plus':
			url = "http://plus.google.com";
			break;
		case 'linkedin':
			url = "http://www.linkedin.com";
			break;	
	}
	
	this.url = url;
};