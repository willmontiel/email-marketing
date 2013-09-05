<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('css/style.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
        {{ stylesheet_link ('css/prstyles.css') }}
		{{ stylesheet_link ('css/style.css') }}
		{{ stylesheet_link ('css/emarketingstyle.css') }}
    </head>
    <body>
		{{txterror}}
		<table class="table table-bordered">
			<tr>
				<td>
					Contacto
				</td>
				<td>
					{{contact.name}}
				</td>
				<td>
					{{contact.lastName}}
				</td>
				<td>
					{{contact.idContact}}
				</td>
			</tr>
			<tr>
				<td>
					Asociacion
				</td>
				<td>
					{{associate.idContactlist}}
				</td>
				<td>
					{{associate.idContact}}
				</td>
			</tr>
		</table>
	</body>
</html>