/* 
 * Plugins y demás externos
 */

App.ExternalLinkComponent = Ember.Component.extend({
  tagName: "a",
  classNames: [],
  attributeBindings: ["href"],
  href: (function() {
    return this.get('pattern').fmt(this.get('content.id'));
  }).property("content.id")
});

Ember.Handlebars.helper("external-link", App.ExternalLinkComponent);

App.Select2 = Ember.Select.extend({
	didInsertElement: function() {
		$(".select2").select2({
			
		});
	}
});

App.FileUploadComponent = Ember.FileField.extend({
	multiple: true,
			
	filesDidChange: (function() {
		var uploadUrl = AttUrl;
		var files = this.get('files');
		var idM = {idMail: idMail};
		
		$('#attach-file').click(function() {
			var uploader = Ember.Uploader.create({url: uploadUrl});
			if (!Ember.isEmpty(files)) {
				var promise = uploader.upload(files, idM);

				promise.then(function(data) {
					$.gritter.add({title: 'Exito', text: data.message, sticky: false, time: 10000});
					App.attachment = 1;
				}, function(error) {
					$.gritter.add({title: 'Ha ocurrido un error', text: error.message, sticky: false, time: 10000});
					App.attachment = 0;
				});
			}
		});
	}).observes('files')
});

App.TimePicker = Ember.TextField.extend({
	didInsertElement: function() {
		$('.time-picker').timepicker({
			showMeridian: false,
			defaultTime: false,
			showInputs: false
		});
	}
});

App.DatePicker = Ember.TextField.extend({
	didInsertElement: function() {
		var now = moment().format('D/M/YYYY');
		$('.date-picker').datetimepicker({
			language: 'es',
			autoclose: true,
			weekStart: false,
			todayBtn: true,
			startDate: now,
			format: "dd/mm/yyyy",
			todayHighlight: true,
			showMeridian: false,
			startView: 2,
			minView: 2,
			forceParse: 0
		});
	}
});

Ember.RadioButton = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id", "checked"],
    click : function() {
        this.set("selection", this.$().val());
		var selection = this.get("selection");
		$("#programmer").hide();
		$("#schedule").val('');
		
		switch (selection) {
			case "now":
				break;

			case "later":
				$("#programmer").show();
				break;
		}
    },
    checked : function() {
		if (this.get("selection") === 'now') {
			this.set("selection", 'later');
		}

		return this.get("value") === this.get("selection");   
    }.property()
});

App.Target = Ember.View.extend({
	didInsertElement: function() {
		createSelectorTarget();
	}
});