@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Sesiones del día {{date("d/m/Y")}}</p></caption>
        <thead>
          <tr>
          <th>Regional</th>
          <th>Sucursal</th>
          <th>Nombre del Usuario</th>
          <th>Hora de inicio</th>
          <th>Hora de Salida</th>
          <th>Tiempo de sesión</th>
        </tr>
        </thead>
        @foreach ($sessions as $sesion)
        <tr>
          <td>{{$sesion->descripcion}}</td>
          <td>{{$sesion->sucursal}}</td>
          <td>{{$sesion->nombre}}</td>
          <td>{{$sesion->f_login}}</td>
          <td>{{$sesion->f_logout}}</td>
          <td>{{$sesion->horas}}</td>
        </tr>
        @endforeach
      </table>
    </div>
    {{--{{$devengos->render()}}--}}
  </div>
</div>

@endsection
