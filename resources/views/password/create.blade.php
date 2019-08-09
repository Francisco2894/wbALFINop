@extends('layouts.admin')
@section('contenido')
    @if ($errors->any())
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {!!Form::open(['route'=>'cambiar_password.store','method'=>'POST'])!!}
    <br>
        <div class="panel panel-info">
            <div class="panel-heading">
                Cambiar Contraseña
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="">Nueva Contraseña</label>
                    <input type="password" name="password" id="" class="form-control" placeholder="" aria-describedby="helpId">
                </div>
                <div class="form-group">
                    <label for="">Confirmar Contraseña</label>
                    <input type="password" name="current_password" id="" class="form-control" placeholder="" aria-describedby="helpId">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Cambiar</button>
            </div>
        </div>
    {{Form::close()}}
@endsection