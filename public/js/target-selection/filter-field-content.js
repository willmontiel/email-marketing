function FilterFieldContent() {
	this.select = '';
}

FilterFieldContent.prototype = new FilterContent;

FilterFieldContent.prototype.createContent = function() {
	var content = $('<div class="sgm-filter-select">\n\
						 <input style="width: 100%;" type="text"/>\n\
					 </div>');
	
	this.parent.find('.sgm-filter-content-body').append(content);
};