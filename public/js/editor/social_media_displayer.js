//------------------------//------------------------//------------------------//
//------------------------//-Social-Media-Displayer-//------------------------//
//------------------------//------------------------//------------------------//


function SocialMediaDisplayer(block, gallery, image) {
	this.gallery = gallery;
	this.block = block;
	this.image = image;
}

var media = new SocialMediaDisplayer();

SocialMediaDisplayer.prototype.setGallery = function(gallery) {
	this.gallery = gallery;	
};

SocialMediaDisplayer.prototype.imageSelected = function() {
	$('#accept_change').off('click');
	var t = this;
	$('#accept_change').on('click', function() {
		$('#fb-share-image').attr('src', t.gallery.srcImage);
		App.fbimage = t.gallery.id;
//		$('#fbimagepublication').val(t.gallery.id);
	});
};

//Dropzone.autoDiscover = false;

$(function() {
	var src = config.imagesUrl + "/post_default.png";
	
	if($('#fbimagepublication').val() !== 'default' && $('#fbimagepublication').val() !== '') {
		src = config.assetsUrl + "/" + $('#fbimagepublication').val();
	}
	
	$('#fb-share-image').attr('src', src);
	
//	var myDropzone = new Dropzone("#my-dropzone");
//	myDropzone.on("success", function(file, response) {
//		var newMedia = new Gallery(response.thumb, response.filelink, response.title, response.id);
//		newMedia.createMedia();
//		newMedia.mediaSelected();
//		media.setGallery(newMedia);
//		media.imageSelected(response.filelink, response.title);
//	});	
//	
//	if($('#accounts_facebook')[0].selectedOptions.length > 0){
//		$('.fbdescription').show();
//		$('.setup_socials_container').show();
//	}	
//	if($('#accounts_twitter')[0].selectedOptions.length > 0){
//		$('.twdescription').show();
//		$('.setup_socials_container').show();
//	}
	$('#accounts_facebook').on('change', function() {
		if($(this)[0].selectedOptions.length > 0) {
			$('.fbdescription').show();
		}
		else {
			$('.fbdescription').hide();
		}
	});

	$('#accounts_twitter').on('change', function() {
		if($(this)[0].selectedOptions.length > 0) {
			$('.twdescription').show();
		}
		else {
			$('.twdescription').hide();
		}
	});

	$('#tweet-char-number').text($('#twpublicationcontent').attr('maxlength'));
	$('#twpublicationcontent').keyup(function() {
		var text = $(this).val();
		$('#tweet-char-number').text($(this).attr('maxlength') - text.length);
	});
});

function showsocials(){
	var container = $('.setup_socials_container');
	if (container.css('display') === 'none') {
		container.show();
	}
	else {
		container.hide();
	}
}

function new_sn_account(redirect){
	$.ajax({
		url: config.baseUrl + "mail/savetmpdata",
		type: "POST",			
		data: $('#setupform').serialize(),
		success: function(){
			window.location.href = redirect;
		}
	});
}