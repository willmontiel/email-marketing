	{{ content() }}
     <form action = "/emarketing/account/edit/<?php echo $allAccount->idAccount; ?>" id="registerAccount" method="post">


      <p>
       Nombre de la cuenta: 
	   {{ editFormAccount.render('companyName') }}
      </p>

      <p>
       Modo de uso:
       {{ editFormAccount.render('modeUse') }}
      </p>

      <p>
       Espacio de archivos:
       {{ editFormAccount.render('fileSpace') }}
      </p>

	  <p>
       Cuota de mensajes:
       {{ editFormAccount.render('messageQuota') }}
      </p>

      <p>
       Modo de Pago: 
       {{ editFormAccount.render('modeAccounting') }}
      </p>

      <p>
       {{ submit_button("Registrar", 'class' : "btn btn-success") }}
      </p>
     </form>