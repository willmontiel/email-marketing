Ember.MixinPagination = Ember.Mixin.create({
	totalrecords: 0,
	currentpage: 0,
	recordsperpage: 0,

	// Metodo que toma la informacion y llena las variables
	setPagination: function () {
		// Verificar paginacion
		try {
			var p = this.getModelMetadata();

			this.set('totalrecords', p.metadata.pagination.total);
			this.set('availablepages',p.metadata.pagination.availablepages);
			this.set('currentpage', p.metadata.pagination.page);
			this.set('recordsperpage', p.metadata.pagination.limit);
		}
		catch (e) {
			
		}
		
	}.observes('model.isLoaded', 'content.firstObject.isLoaded'),
	availablePages: function(){
//		var available = parseInt(this.get("totalrecords"));
//		for (i=0; i=available; i++){
//			
//		}
	}.property(),
			
	nextPage: function(){
		var currentpage=parseInt(this.get("currentpage"));
		var availablepages=parseInt(this.get("availablepages"));
		
		if(currentpage >= availablepages){
//				Hacer nada
		}
		else{
			var page=parseInt(this.get("currentpage"))+1;
			var obj = {
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
				page: availablepages,
				limit: this.get("recordsperpage")
			};
			this.refreshModel(obj);
		}
	}
	
		
});

