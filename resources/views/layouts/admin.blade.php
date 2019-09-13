<!doctype html>
<html lang="es">

<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	
	<link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/logo.png')}}">
	<link rel="icon" type="image/png" href="{{asset('assets/img/logo.png')}}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Agenda</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
	@stack('scripts')
	<!-- CSS Files -->
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}"/>
	<link rel="stylesheet" href="{{asset('assets/css/material-kit.css')}}"/>
	@stack('styles')

</head>

<body class="frmdata-page">
	<nav class="navbar navbar-transparent navbar-fixed-top navbar-color-on-scroll">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
            		<span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
        		</button>
				<a class="navbar-brand">
					<img style="max-width:100px; margin-top: -7px;"
              src="{{asset('assets/img/logo.png')}}"></a>
			</div>
			<div class="collapse navbar-collapse" id="navigation-example">
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::user()->idNivel<3)
						<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">work</i>
							ASESOR<span class="caret"></span>
					</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{{URL::action('DevengoController@index')}}"><i class="material-icons">view_agenda</i>Cobranza</a>
				</li>
				<li>
					<a href="{{URL::action('VencimientoController@index')}}"><i class="material-icons">hourglass_empty</i>Retencion</a>
				</li>
				<li>
					<a href="{{URL::action('ProspectobcController@index')}}"><i class="material-icons">attach_money</i>Promoci&oacute;n</a>
				</li>
			</ul>
			</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">assignment</i>
							GESTOR<span class="caret"></span>
					</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{{URL::action('GestorController@index')}}"><i class="material-icons">view_agenda</i>Cobranza</a>
				</li>
			</ul>
			</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">multiline_chart</i>
								PROCESOS<span class="caret"></span>
							</a>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="{{URL::action('DashOperacionController@devengo')}}"><i class="material-icons">sync</i>Devengos</a>
						</li>
						<li>
							<a href="{{URL::action('DashOperacionController@index')}}"><i class="material-icons">sync</i>Operaciones</a>
						</li>
						<li>
							<a href="{{URL::action('ComisionController@index')}}"><i class="material-icons">sync</i>Comisiones</a>
						</li>
					</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">multiline_chart</i>
							REPORTES OP<span class="caret"></span>
						</a>
				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="{{URL::action('RptbiController@index')}}"><i class="material-icons">show_chart</i>Venc-Renov BI</a>
					</li>
					<li>
						<a href="{{URL::action('RptbiController@rptSesion')}}"><i class="material-icons">list</i>Sesiones</a>
					</li>
					<li>
						<a href="{{URL::action('RptbiController@rptAgenda')}}"><i class="material-icons">insert_chart</i>Agenda BI</a>
					</li>
					<li>
						<a href="{{ route('renovaciones_totales') }}"><i class="material-icons">show_chart</i>Renovaciones</a>
					</li>
					<li>
						<a href="{{ route('ofertas_totales') }}"><i class="material-icons">show_chart</i>Ofertas</a>
					</li>
				</ul>
				</li>
				
				
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings_applications</i>
								<span class="caret"></span>
							</a>
					<ul class="dropdown-menu" role="menu">
					    <li>
							<a href="{{route('usuario.index')}}"><i class="material-icons">enhanced_encryption</i>Admin. Contraseña</a>
						</li>
						<li>
							<a href="{{route('listarPerfiles')}}"><i class="material-icons">person_add</i>Agregar Usuario</a>
						</li>
						<li>
							<a href="{{route('perfil.create')}}"><i class="material-icons">toc</i>Agregar Perfiles</a>
						</li>
						<li>
							<a href="{{URL::action('ExcelController@index')}}"><i class="material-icons">import_export</i>Creditos</a>
						</li>
						<li>
							<a href="{{URL::action('ExcelController@viewDevengo')}}"><i class="material-icons">import_export</i>Devengos</a>
						</li>
						<li>
							<a href="{{URL::action('ExcelController@viewPago')}}"><i class="material-icons">import_export</i>Pagos</a>
						</li>
					</ul>
					</li>
				@endif
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">multiline_chart</i>
						REPORTES<span class="caret"></span>
					</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{{URL::action('RptbiController@rptCartera')}}"><i class="material-icons">show_chart</i>Cartera</a>
				</li>
				<li>
					<a href="{{URL::action('RptbiController@rptDevengo')}}"><i class="material-icons">show_chart</i>Devengos</a>
				</li>
				<li>
					<a href="{{URL::action('RptbiController@rptSesion')}}"><i class="material-icons">list</i>Sesiones</a>
				</li>
			</ul>
			</li>
				@if (Auth::user()->idNivel<>6 || Auth::user()->idNivel<>5)
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">assignment</i>
							GESTOR<span class="caret"></span>
					</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{{URL::action('GestorController@index')}}"><i class="material-icons">view_agenda</i>Cobranza</a>
				</li>
			</ul>
			</li>
			@endif
				@if (Auth::user()->idNivel<>6 || Auth::user()->idNivel<>7)
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">work</i>
							ASESOR<span class="caret"></span>
					</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{{URL::action('DevengoController@index')}}"><i class="material-icons">view_agenda</i>Cobranza</a>
				</li>
				<li>
					<a href="{{URL::action('VencimientoController@index')}}"><i class="material-icons">hourglass_empty</i>Retencion</a>
				</li>
				<li>
					<a href="{{URL::action('ProspectobcController@index')}}"><i class="material-icons">attach_money</i>Promoción</a>
				</li>
			</ul>
			</li>
			@endif
			@if (auth()->user()->idNivel==1 || auth()->user()->idNivel==6 || auth()->user()->idNivel==3 || auth()->user()->idNivel==4)
				{{-- <li class="nav-item">
					<a class="nav-link" href="{{route('renovacion.index')}}">Renovacion</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{route('datosrenovacion')}}">Datos de Renovacion</a>
				</li>	 --}}
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">account_box</i>
						Renovaci&oacute;n<span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
						<li class="nav-item">
							<a class="nav-link" href="{{route('renovacion.index')}}"><i class="material-icons">attach_money</i> Socioeconomico</a>
						</li>
						@if (auth()->user()->idNivel !=4 && auth()->user()->idNivel !=4)
							<li class="nav-item">
								<a class="nav-link" href="{{route('resumenAvance')}}"><i class="material-icons">timeline</i> Resumen de Avance</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{route('datosrenovacion')}}"><i class="material-icons">import_export</i> Datos de Renovacion</a>
							</li>
						@endif
					</ul>
				</li>
				@endif
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">account_box</i>
								{{ Auth::user()->name }} <span class="caret"></span>
						</a>

						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();"><i class="material-icons">lock_outline</i>
									Cerrar sesión
								</a>
								<a href="{{ route('cambiar_password.create') }}"><i class="material-icons">vpn_key</i> Cambiar Contraseña</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
								</form>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="wrapper">
		<div class="header header-filter" style="background-image:url({{asset('assets/img/Wback.jpg')}});"></div>

		<div class="main main-raised">
			<div class="profile-content">
				<div class="container">
					<div class="row">
						@include('layouts.notificacionCambioPassword')
						@include('layouts.notificacionError')
    					@yield('contenido')
          			</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<div class="container">
			<nav class="pull-left">
				<ul>
					<li>
						<a href="http://www.alfin.mx">
							ALFIN - Servicios Financieros
						</a>
					</li>
				</ul>
			</nav>

		</div>
	</footer>

</body>
<!--   Core JS Files   -->
<script src="{{asset('assets/js/jquery-2.1.0.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/dropdown.js')}}" type="text/javascript"></script>
<script src="{{asset('js/checkbox.js')}}" type="text/javascript"></script>
<script src="{{asset('js/utildb.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/material.min.js')}}"></script>

<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="{{asset('assets/js/nouislider.min.js')}}" type="text/javascript"></script>

<!--  Plugin for the Datepicker, full documentation here: http://www.eyecon.ro/bootstrap-datepicker/ -->
<script src="{{asset('assets/js/bootstrap-datepicker.js')}}" type="text/javascript"></script>

<!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
<script src="{{asset('assets/js/material-kit.js')}}" type="text/javascript"></script>

</html>
