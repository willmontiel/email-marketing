<div class="container-fluid">
<div class="row-fluid">
 <div class="span12">
  <div id="specific">
   <h1>Mail Gorilla</h1>
  </div>
 </div>
 <br>
 <div class="span9">
  {{ content() }}
  
  <div id="form-signin">
   {{ form('account/new', 'id': 'registerAccount', 'method': 'Post') }}
      <p>
       Nombre de la cuenta: 
       {{ form.render('companyName') }}
      </p>

      <p>
       Dirección de correo electronico: {{ form.render('email') }}
      </p>

      <p>
       Nombre:
       {{ form.render('firstName') }}
      </p>

      <p>
       Apellido:
       {{ form.render('lastName') }}
      </p>

	  <p>
       Nombre de usuario:
       {{ form.render('username') }}
      </p>

      <p>
       Contraseña: 
       {{ form.render('password') }}
      </p>

      <p>
       Repita la contraseña:
       {{ form.render('password2') }}
      </p>

      <p>
       Cantidad de espacio para archivos:
       {{ form.render('fileSpace') }}
      </p>

      <p>
       Cantidad de mensajes
       {{ form.render('messageQuota') }}
      </p>

      <p>
       Modo de uso:
       {{ form.render('modeUse') }}
      </p>

      <p>
       Modo de pago:
       {{ form.render('modeAccounting') }}
      </p>
      <p>
       {{ submit_button('Registrar', 'class':"btn btn-inverse dropdown-toggle", 'data-toggle':"dropdown") }}
      </p>
   
   </form>
  </div>  
 </div>

 {{ link_to('session', 'class': "btn btn-info dropdown-toggle", 'data-toggle': "dropdown", "login") }}
 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>