if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};

RedactorPlugins.advanced = {
	init: function()
	{
		this.buttonAdd('advanced', 'Advanced', this.showMyModal);
//		this.buttonAwesome('advanced', 'fa-bullhorn');
	},
	showMyModal: function()
    {
        var callback = $.proxy(function()
        {
            this.selectionSave();
            $('#redactor_modal #mymodal-insert').click($.proxy(this.insertFromMyModal, this));

        }, this);

        // modal call
        this.modalInit('Advanced', '#forms-update', 500, callback);
    },
    insertFromMyModal: function(html)
    {
        this.selectionRestore();
        this.insertHtml('some');
        this.modalClose();
    }
}