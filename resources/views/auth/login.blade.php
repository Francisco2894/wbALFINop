@extends('layouts.app')

@section('content')
    <div class="signup-page">
    <div class="wrapper">
  <div class="container">
  				<div class="row">
  					<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
  						<div class="card card-signup">
  							<form class="form" method="POST" action="{{ route('iniciarSesion') }}">
                  {{ csrf_field() }}
  								<div class="header header-primary text-center">
  									<h4>Inicio de sesión</h4>
                  </div>
                  @if (session('error'))
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <ul>
                        <li>{!! session('error') !!}</li>
                        </ul>
                    </div>
                  @endif
  								<p class="text-divider">Ingrese sus datos</p>
  								<div class="content">

  									<div class="input-group" >
  										<span class="input-group-addon">
  											<i class="material-icons">face</i>
  										</span>
  										<input id="email" type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
  									</div>
  									<div class="input-group">
  										<span class="input-group-addon">
  											<i class="material-icons">lock_outline</i>
  										</span>
  										<input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required/>
  									</div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordar
                        </label>
                    </div>
                    <div class="form-group text-center">
                       <button type="submit" class="btn btn-primary">
                                      Ingresar
                       </button>
                    </div>
  								</div>
                       <a href="{{ route('password.request') }}">
                                    Olvidaste tu password?
                       </a>
  							</form>
  						</div>
  					</div>
  				</div>
  			</div>
        </div>
        </div>
@endsection
