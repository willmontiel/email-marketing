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
       <label for="companyName">Nombre de la cuenta:</label>
		{{ newFormAccount.render('companyName') }}
      </p>
      <p>
       <label for="email">Dirección de correo electronico:</label> 
		{{ newFormAccount.render('email') }}
      </p>

      <p>
       <label for="firstName">Nombre:</label>
       {{ newFormAccount.render('firstName') }}
      </p>

      <p>
       <label for="lastName">Apellido:</label>
       {{ newFormAccount.render('lastName') }}
      </p>

	  <p>
       <label for="username">Nombre de usuario:</label>
       {{ newFormAccount.render('username') }}
      </p>

      <p>
       <label for="password">Contraseña:</label>
       {{ newFormAccount.render('password') }}
      </p>

      <p>
       <label for="password2"> Repita la contraseña:</label>
       {{ newFormAccount.render('password2') }}
      </p>

      <p>
       <label for="fileSpace">Cantidad de espacio para archivos:</label>
       {{ newFormAccount.render('fileSpace') }}
      </p>

      <p>
       <label for="messageQuota">Cantidad de mensajes</label>
       {{ newFormAccount.render('messageQuota') }}
      </p>

      <p>
       <label for="modeUse">Modo de uso:</label>
       {{ newFormAccount.render('modeUse') }}
      </p>

      <p>
       <label for="modeAccounting">Modo de pago:</label>
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