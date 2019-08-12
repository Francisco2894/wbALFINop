@extends('layouts.admin')
@section('contenido')
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Perfil</th>
                    <th>Nivel</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->perfil->descripcion }}</td>
                    <td>{{ $usuario->idNivel }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    {!! Form::model($usuario,['route'=>['usuario.update',$usuario->id],'method'=>'PATCH']) !!}
        @include('layouts.formularios.FormPassword')
    {{ Form::close() }}
@endsection