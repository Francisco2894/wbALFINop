@extends('layouts.admin')
@section('contenido')
    <br>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading"><h1 class="panel-title">Agregar Usuario</h1></div>
            <div class="panel-body">
                {!! Form::open(['route'=>'usuario.store', 'method'=>'POST', 'autocomplete'=>'off']) !!}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Nombre</label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="{{ $perfil->persona->nombre }} {{ $perfil->persona->paterno }} {{ $perfil->persona->materno }}" required autofocus>
                    </div>
                </div>
    
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
    
                <div class="form-group{{ $errors->has('perfil') ? ' has-error' : '' }}">
                    <label for="perfil" class="col-md-4 control-label">Clave Asesor</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="idPerfil" value="{{ $perfil->idPerfil }}" readonly required autofocus>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('nivel') ? ' has-error' : '' }}">
                    <label for="nivel" class="col-md-4 control-label"> ID Nivel</label>
                    <div class="col-md-6">
                        <select name="idNivel" class="form-control">
                            @for ($i = 1; $i < '8'; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-4">
                    <button type="submit" class="btn btn-block btn-primary text-center" onclick="inputs()">Guardar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection