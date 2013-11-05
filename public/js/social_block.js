function SocialBlock (parentBlock, socialType) {
	this.parentBlock = parentBlock;
	this.socialType = socialType;
	
	this.createSocialNetHtml();
}

SocialBlock.prototype.createSocialNetHtml = function() {
	var text = $("<div class=\"soc_net button_" + this.socialType + "\"><img data-toggle=\"modal\" href=\"#socialnetwork\" class=\"media-object\" src=\"" + config.imagesUrl + "/" + this.socialType + "\" alt=\"64x64\" /></div>");

	this.htmlData = text;
	
	this.parentBlock.find('.content').append(text);
};

SocialBlock.prototype.showSocialInfo = function() {

	this.setUrlByDefault();
	
	var htmlText = $("<div class=\"title\">" + this.socialType + "</div><div><span>URL</span><div class=\"url_name\">" + this.url + "</div></div><div><span>Texto de Link</span><div class=\"link-text\">" + this.socialType + "</div></div>");
	
	$('#socialData').append(htmlText);
};

SocialBlock.prototype.setUrlByDefault = function() {
	
	var url = "";
	
	switch(this.socialType)
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