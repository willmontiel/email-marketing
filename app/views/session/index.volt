<div class="container-fluid">
<div class="row-fluid">
 <div class="span12">
  <div id="specific" class="hero-unit">
   <h1>Mail Gorilla</h1>
  </div>
 </div>

 <div class="span12 offset4" >
  <h3>Registrar una Cuenta</h3>
 </div>
 
 <div class="span6 offset2">
  {{ content() }}
  
  <div id="form-login">
   {{ form('session/login', 'id': 'sessionlogin') }}
  
   <p>            
    {{ text_field("username", 'class': 'input-xlarge', 'type': "text", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Nombre de Usuario o E-mail") }}
   </p>

   <p> 
    {{ password_field('pass', 'class': 'input-xlarge', 'type': "email", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Contraseña") }}
   </p>
  
   <p>
   {{ submit_button("Ingresar", 'class' : "btn btn-success") }}
   {{ check_field('online') }} Recuerdame
   </p>
   </form>
  </div>  
 </div>

 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>