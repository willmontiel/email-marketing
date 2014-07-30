function writenewcategory() {
	$('.selectcategory').hide();
	$('.btnNewCategory').hide();
	$('.selectcategory').find('#category').removeAttr('id');

	$('.newcategory').show();
	$('.btnSelectCategory').show();
	$('.newcategory').find('input').attr('id', "category");
};

function selectcategory() {
	$('.newcategory').hide();
	$('.btnSelectCategory').hide();
	$('.newcategory').find('input').removeAttr('id');

	$('.selectcategory').show();
	$('.btnNewCategory').show();
	$('.selectcategory').find('select').attr('id', "category");
};