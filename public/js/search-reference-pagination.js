/*
 * Para utilizar el Mixin solo se debe 
 * crear un atributo en el controlador con la clase del modelo que utiliza
 * EJ:
 * modelClass : App.contact
 */

Ember.MixinSearchReferencePagination = Ember.Mixin.create({
	totalrecords: 0,
	currentpage: 0,
	recordsperpage: 0,
	availablepages: 0,
	canprev: false,
	cannext: false,

	// Metodo que toma la informacion y llena las variables
	setPagination: function () {
		// Verificar paginacion
		try {
			var p = this.store.typeMapFor(this.modelClass).metadata;

			this.set('totalrecords', p.pagination.total);
			this.set('availablepages',p.pagination.availablepages);
			this.set('currentpage', p.pagination.page);
			this.set('recordsperpage', p.pagination.limit);
		}
		catch (e) {
		}
		var cp = parseInt(this.get('currentpage'));
		var lp = parseInt(this.get('availablepages'));
		if (cp <= 1) {
			this.set('canprev', false);
		}
		else {
			this.set('canprev', true);
		}
		
		if (cp >= lp) {
			this.set('cannext', false);
		}
		else {
			this.set('cannext', true);
		}
		
	}.observes('content.length'),
			
	refreshModel: function (obj) {
		this.set('content', this.store.find(this.modelClass, obj));
	}
	,
	actions: {
		nextPage: function(){
			var currentpage=parseInt(this.get("currentpage"));
			var availablepages=parseInt(this.get("availablepages"));

			if(currentpage >= availablepages){
	//				Hacer nada
			}
			else{
				var page=parseInt(this.get("currentpage"))+1;
				var obj = {
					searchCriteria: this.criteria,
					page: page,
					limit: this.get("recordsperpage")
				};
				this.refreshModel(obj);
			}
		},
	
		prevPage: function(){
			var currentpage=parseInt(this.get("currentpage"));

			if(currentpage == 1){
	//				Hacer nada
			}
			else{
				var page=parseInt(this.get("currentpage"))-1;

				var obj = {
					searchCriteria: this.criteria,
					page: page,
					limit: this.get("recordsperpage")
				};
				this.refreshModel(obj);
			}
		},

		firstPage: function(){
			var currentpage=parseInt(this.get("currentpage"));

			if(currentpage == 1){
	//				Hacer nada
			}
			else{
				var obj = {
					searchCriteria: this.criteria,
					page: 1,
					limit: this.get("recordsperpage")
				};
				this.refreshModel(obj);
			}
		},

		lastPage: function(){
			var currentpage=parseInt(this.get("currentpage"));
			var availablepages=parseInt(this.get("availablepages"));

			if(currentpage == availablepages){
	//				Hacer nada
			}
			else{
				var obj = {
					searchCriteria: this.criteria,
					page: availablepages,
					limit: this.get("recordsperpage")
				};
				this.refreshModel(obj);
			}
		}
	
	}
		
});

