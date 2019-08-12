@extends('layouts.admin')
@section('contenido')
<br>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h1 class="panel-title">Usuarios - Cambiar Password</h1>
    </div>
    <div class="panel-body">
        {!!Form::open(['route'=>'usuario.index','method'=>'GET'])!!}
            <div class="col-sm-10">
                <div class="form-group label-floating">
                    <label class="control-label">Nombre...</label>
                    <input class="form-control" type="text" name="name" id="cliente">
                </div>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-info btn-block" type="submit">Buscar</button>
            </div>
        {!! Form::close() !!}
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Perfil</th>
                            <th>Nivel</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->perfil->descripcion }}</td>
                                <td>{{ $user->idNivel }}</td>
                                <td width="5%">
                                    <a class='btn btn-danger btn-simple btn-xs' href="{{ route('usuario.edit',$user->id) }}" rel='tooltip' title='¿Cambiar Password?'><i class='material-icons'>vpn_key</i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->render() }}
            </div>
        </div>
    </div>
</div>
@endsection