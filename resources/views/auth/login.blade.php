@extends('layouts.app')

@section('content')
    <div class="signup-page">
    <div class="wrapper">
  <div class="container">
  				<div class="row">
  					<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
  						<div class="card card-signup">
  							<form class="form" method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}
  								<div class="header header-primary text-center">
  									<h4>Inicio de sesión</h4>
  								</div>
  								<p class="text-divider">Ingrese sus datos</p>
  								<div class="content">

  									<div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}" >
  										<span class="input-group-addon">
  											<i class="material-icons">face</i>
  										</span>
  										<input id="email" type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                         @endif
  									</div>
  									<div class="input-group{{ $errors->has('password') ? ' has-error' : '' }}">
  										<span class="input-group-addon">
  											<i class="material-icons">lock_outline</i>
  										</span>
  										<input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required/>
                      @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                      @endif
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
