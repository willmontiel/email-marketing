App.Form = DS.Model.extend({});

App.FormsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('form');
	}
});

App.FormsIndexController = Ember.ObjectController.extend({});

App.FormsNewRoute = Ember.Route.extend({});

App.FormsNewController = Ember.ObjectController.extend({
	init : function() {
		$(function(){
			formeditor.startEvents();
		});
	}
});

App.FormsSetupRoute = Ember.Route.extend({});

App.FormsSetupController = Ember.ObjectController.extend({
	init : function() {
		$(function(){
			$('#double-optin').on('click', function() {
				$('.form-setup-content').hide();
				$('.here-comes-frame').html('<iframe id="iframeEditor" src="' + config.baseUrl + 'mail/editor_frame" width="100%" onload="iframeResize()" seamless></iframe>');
			});
		});
	}
});