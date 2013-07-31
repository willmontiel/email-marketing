{% extends "templates/signin.volt" %}
{% block content %}
	{{ content() }}
	
		<div class="row-fluid">
			<div class="span9">
				{{ form('field/new', 'id': 'newField', 'method': 'Post') }}
					<table border="0">
						<tr>
							<td>
								Nombre del campo:
							</td>
							<td>
								{{ NewFieldForm.render('name') }}
							</td>

							<td>
								Tipo:
							</td>
							<td>
								{{ NewFieldForm.render('type') }}
							</td>

							<td>
								<label class="checkbox" for="required">
								<span class="icons">
									<span class="first-icon fui-checkbox-unchecked"></span>
									<span class="second-icon fui-checkbox-checked"></span>
								</span>
								{{ NewFieldForm.render('required') }} 
								Recuerdame
							</label>
								
							</td>
						</tr>
					</table>
				</form>
			</div>	
		</div>
		
{% endblock %}
