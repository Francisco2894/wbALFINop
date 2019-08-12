@extends('layouts.admin')
@section('contenido')
<br>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h1 class="panel-title">Agregar Perfil</h1>
    </div>
    <div class="panel-body">
        {!! Form::open(['route'=>'perfil.store','method'=>'POST']) !!}
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Nombre</label>
                    {!! Form::text('nombre', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Apellido Paterno</label>
                    {!! Form::text('paterno', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Apellido Materno</label>
                    {!! Form::text('materno', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">ID Perfil</label>
                    {!! Form::text('idPerfil', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Descripci&oacute;n</label>
                    {!! Form::text('descripcion', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Sucursal</label>
                    {!! Form::select('idSucursal', $sucursales, null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4 col-xs-offset-4">
                <button type="submit" class="btn btn-block btn-primary text-center" onclick="inputs()">Guardar</button>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection