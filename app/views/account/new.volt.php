<div class="container-fluid">
<div class="row-fluid">
 <div class="span12">
  <div id="specific">
   <h1>Mail Gorilla</h1>
  </div>
 </div>
 <br>
 <div class="span11">
  {{ content() }}
   <div class="row-fluid">
    <div class="span4">
		{{ form('account/new', 'id': 'registerAccount', 'method': 'Post') }}
      <p>
       Nombre de la cuenta: {{ newFormAccount.render('companyName') }}
      </p>
      <p>
       Dirección de correo electronico: {{ newFormAccount.render('email') }}
      </p>

      <p>
       Nombre:
       {{ newFormAccount.render('firstName') }}
      </p>

      <p>
       Apellido:
       {{ newFormAccount.render('lastName') }}
      </p>

	  <p>
       Nombre de usuario:
       {{ newFormAccount.render('username') }}
      </p>

      <p>
       Contraseña: 
       {{ newFormAccount.render('password') }}
      </p>

      <p>
       Repita la contraseña:
       {{ newFormAccount.render('password2') }}
      </p>

      <p>
       Cantidad de espacio para archivos:
       {{ newFormAccount.render('fileSpace') }}
      </p>

      <p>
       Cantidad de mensajes
       {{ newFormAccount.render('messageQuota') }}
      </p>

      <p>
       Modo de uso:
       {{ newFormAccount.render('modeUse') }}
      </p>

      <p>
       Modo de pago:
       {{ newFormAccount.render('modeAccounting') }}
      </p>
      <p>
       {{ submit_button("Registrar", 'class' : "btn btn-success") }}
      </p>
   </form>
	</div>
   </div>
  </div>  
 
 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>