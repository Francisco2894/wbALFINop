@extends('layouts.admin')
@section('contenido')
    {!!Form::open(['route'=>'cambiar_password.store','method'=>'POST'])!!}
        @include('layouts.formularios.FormPassword')
    {{Form::close()}}
@endsection