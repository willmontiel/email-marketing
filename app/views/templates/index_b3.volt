{# Nuevo template usando Bootstrap 3 #}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
		<link rel="shortcut icon" type="image/x-icon" href="{{url('')}}images/favicon48x48.ico">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

        {{ get_title() }}

        {{ stylesheet_link('vendors/bootstrap_v3/css/bootstrap.css') }}
        {{ stylesheet_link('vendors/bootstrap_v3/css/font-awesome.css') }}
        {{ stylesheet_link('css/prstyles.css') }}

        {# Para cambiar el tema modificar la ruta en el siguiente enlace#}
        {{ stylesheet_link('themes/' ~ theme.name ~ '/css/styles.css') }}
        {##}

        {{ stylesheet_link('vendors/bootstrap_v3/vendors/css/bootstrap-editable.css') }}
        {{ stylesheet_link('vendors/bootstrap_v3/vendors/css/jquery.gritter.css') }}

		<!--[if lt IE 9]>
		{{ javascript_include('javascripts/vendor/html5shiv.js') }}
		{{ javascript_include('javascripts/vendor/excanvas.js') }}
		<![endif]-->
		
		{% block header_javascript %}
		<script type="text/javascript">
			var MyBaseURL = '{{url('')}}';
		</script>
		{{ javascript_include('vendors/bootstrap_v3/js/jquery-1.9.1.js') }}
		{{ javascript_include('vendors/bootstrap_v3/js/bootstrap.js') }}
		{{ javascript_include('vendors/bootstrap_v3/vendors/js/jquery.sparkline.js') }}
{#		
		{{ javascript_include('b3/vendors/js/spark_auto.js') }}
#}
		{{ javascript_include('vendors/bootstrap_v3/vendors/js/bootstrap-editable.js') }}
		{{ javascript_include('vendors/bootstrap_v3/vendors/js/jquery.gritter.js') }}
		{% endblock %}
		{{ javascript_include('js/indicator_account_loader.js') }}
		<script type="text/javascript">
			loadIndicator("indicator");
		</script>
        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<!-- side bar -->
		<div id="sidebar-background-object" class="col-sm-3 col-md-2 hidden-xs"></div>
		
		<!-- nav bar -->
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
			  <div class="navbar-header">
				  <a class="navbar-brand" href="{{url('')}}">{{theme.logo}}</a>
			  </div>
			  <ul id="top-nav" class="nav navbar-nav navbar-right" style="padding-top: 10px;">
					<li>
						<a href="javascript: void(0);" style="cursor: default;">
							<div id="indicator"></div>
						</a>
					</li>
					{% if chat.enabled %}
					<!-- BEGIN OLARK CHAT LINK -->
					<li>
						<a href="javascript:void(0);" onclick="olark('api.box.expand');">
							Necesita ayuda? <i class="fa fa-comments"></i>
						</a>
					</li>
					<!-- END OLARK CHAT LINK -->
					{% endif %}
					{% if userefective.enable %}
						<li><a href="{{url('session/logoutfromthisaccount')}}">Volver a la sesión original </a></li>
					{% endif %}
					<li><a href="javascript: void(0);" style="cursor: default;">{{ userObject.firstName }} {{ userObject.lastName }}</a></li>
					<li><a href="{{url('session/logout')}}" title="Cerrar sesión"><span class="glyphicon glyphicon-log-out"></span></a></li>
				</ul>	
			</div>
		</nav>
		
<!-- Contenedor principal -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-2 col-md-1 sidebar">
				<div class="principal-menu">
					<!-- Main nav -->
					{{ partial("partials/menu_partial_b3_2") }}
				</div>
			</div>
			<div class="col-sx-12 col-sm-9 col-md-10">
				{# Zona de mensajes #}
				{% if messages !== false%}
					<div class="space"></div>
					<div class="row">
						<div class="col-sm-12">
							{% for msg in messages%}
								<div class="alert alert-{{msg.type}}">
									<button type="button" class="close" data-dismiss="alert">×</button>
									{{msg.message}}
								</div>
							{% endfor %}
						</div>
					</div>
				{% endif %}
				{# Fin de zona de mensajes #}

				<div class="container-fluid">
					<!-- Inicio de contenido -->
					{% block content %}
						<!-- Aqui va el contenido -->
					{% endblock %}
					<!-- Fin de contenido -->
				</div>
			</div>

		</div>
	</div>

		{# OLARK #}
		{% if chat.enabled %}
			<!-- begin olark code -->
			<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
			f[z]=function(){
			(a.s=a.s||[]).push(arguments)};var a=f[z]._={
			},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
			f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
			0:+new Date};a.P=function(u){
			a.p[u]=new Date-a.p[0]};function s(){
			a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
			hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
			return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
			b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
			b.contentWindow[g].open()}catch(w){
			c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
			var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
			b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
			loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
			/* custom configuration goes here (www.olark.com/documentation) */
			olark.identify('1459-326-10-6576');/*]]>*/</script><noscript><a href="https://www.olark.com/site/1459-326-10-6576/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
			<!-- end olark code -->
			<script type="text/javascript">
				// Set user's email address, fullname and nickname
				olark('api.visitor.updateEmailAddress', {
						emailAddress: '{{ userObject.email }}'
				});
				olark('api.visitor.updateFullName', {
						fullName: '{{ userObject.firstName }} {{ userObject.lastName }}'
				});
				olark('api.chat.updateVisitorNickname', {
					snippet: '{{ userObject.firstName }}'
				});
			</script>
		{% else %}
			<!-- No chat available -->
		{% endif %}
		{# /OLARK #}
		<!-- Tooltips -->
		<script type="text/javascript">
			$(function() {
				$('a[data-toggle="tooltip"]').tooltip();
			});
		</script>
    </body>
</html>
