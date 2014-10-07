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
	multiple: false,
			
	filesDidChange: (function() {
		var uploadUrl = AttUrl;
		var files = this.get('files');
		var idM = {idMail: idMail};
		
		$('#input-file-decorator').val(files[0].name);
		
		$('#attach-file').click(function() {
			var uploader = Ember.Uploader.create({url: uploadUrl});
			if (!Ember.isEmpty(files)) {
				var promise = uploader.upload(files, idM);

				promise.then(function(data) {
					$.gritter.add({title: '<i class="glyphicon glyphicon-paperclip"></i> Exitoso', text: data.message, sticky: false, time: 10000});
					App.controller.refreshAttachment();
				}, function(error) {
					var msg = error.responseText;
					console.log(msg);
					$.gritter.add({title: '<i class="glyphicon glyphicon-remove-sign"></i> Ha ocurrido un error', text: msg, sticky: false, time: 10000});
					App.controller.refreshAttachment();
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