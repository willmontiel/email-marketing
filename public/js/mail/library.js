/* 
 * Plugins y demás externos
 */
App.Select2 = Ember.Select.extend({
	didInsertElement: function() {
		$(".select2").select2({
			
		});
	}
});

App.TimePicker = Ember.TextField.extend({
	didInsertElement: function() {
		$('.time-picker').timepicker({
			showMeridian: false,
			defaultTime: false
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

Ember.RadioButtonTarget = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id", "checked"],
    click : function() {
        $("#db").hide();
		$("#list").hide();
		$("#seg").hide();
		
		this.set('controller.dbaselist', []);
		this.set('controller.list', []);
		this.set('controller.segmentlist', []);
		
		var value = this.$().val();
		
		switch (value) {
			case "dataBase":
				$("#db").show();
				break;
			case "contactList":
				$("#list").show();
				break;
			case "segment":
				$("#seg").show();
				break;
		}
    }
});

Ember.RadioFilter = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "id", "checked"],
    click : function() {
		$("#mail").hide();
		$("#open").hide();
		$("#click").hide();
		$("#exclude").hide();
		
		this.set('controller.filterByEmail', '');
		this.set('controller.open', []);
		this.set('controller.click', []);
		this.set('controller.exclude', []);
	
		$("#sendByMail").val('');
		$('#sendOpen').val('');
		$('#sendClick').val('');
		$('#sendExclude').val('');
		
		var value = this.$().val();
		
		switch (value) {
			case "byMail":
				$("#mail").show();
				break;
			case "byOpen":
				$("#open").show();
				break;
			case "byClick":
				$("#click").show();
				break;
			case "byExclude":
				$("#exclude").show();
				break;
		}
    }	
});


