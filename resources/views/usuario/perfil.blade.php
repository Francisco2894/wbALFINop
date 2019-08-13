@extends('layouts.admin')
@section('contenido')
<br>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h1 class="panel-title">Perfiles - Agregar Usuario</h1>
    </div>
    <div class="panel-body">
        {!!Form::open(['route'=>'listarPerfiles','method'=>'GET'])!!}
            <div class="col-sm-10">
                <div class="form-group label-floating">
                    <label class="control-label">Nombre...</label>
                    <input class="form-control" type="text" name="name">
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
                            <th>Perfil</th>
                            <th>Sucursal</th>
                            <th>Correo</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perfiles as $user)
                            <tr class="text-center">
                                <td>{{ $user->nombre }} {{ $user->paterno }} {{ $user->materno }}</td>
                                <td>{{ $user->idPerfil }}</td>
                                <td>{{ $user->sucursal }}</td>
                                <td>{{ is_null($user->usuario)?'':$user->usuario->email }}</td>
                                <td>{!! is_null($user->usuario)?'':$user->usuario->status==0?'<span class="label label-danger">Inactivo</span>':'<span class="label label-success">Activo</span>' !!}</td>
                                <td width="5%">
                                    @if (is_null($user->usuario))
                                        <a class='btn btn-primary btn-simple btn-xs' href="{{ route('usuario.create',['idPerfil'=>$user->idPerfil]) }}" rel='tooltip' title='¿Agregar Usuario?'><i class='material-icons'>verified_user</i></a>
                                    @else
                                    {!! Form::model($user->usuario,['route' => ['usuario.destroy',$user->usuario->id],'method'=>'DELETE']) !!}
                                        @if ($user->usuario->status == 1)
                                            <button type="submit" class='btn btn-danger btn-simple btn-xs' rel='tooltip' title='¿Bloquear?' value="activar"><i class='material-icons'>lock</i></button>
                                        @else
                                            <button type="submit" class='btn btn-success btn-simple btn-xs' rel='tooltip' title='¿Desbloquear?' value="desactivar"><i class='material-icons'>lock_open</i></button>
                                        @endif
                                    {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $perfiles->render() }}
            </div>
        </div>
    </div>
</div>
@endsection