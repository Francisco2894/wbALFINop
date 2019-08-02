@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Promoción</h3>
    @include('agenda.promocion.search')
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Promoción de los ultimos 30 días</p></caption>
        <thead>
          <tr>
          <th>Folio Consulta</th>
          <th>Nombre Completo</th>
          <th>Score</th>
          <th>Fecha Consulta</th>
          <th>Prospecto</th>
          <th>Tipo Credito</th>
          <th>Producto</th>
          <th>Monto Solicitado</th>
          <th>Estatus</th>
        </tr>
        </thead>
        @foreach ($prospectos as $prospecto)
        <tr>          
          <td>{{$prospecto->folio}}</td>
          <td>{{$prospecto->nombre}}</td>
          <td>{{$prospecto->score}}</td>
          <td>{{date_format(date_create($prospecto->fechaConsulta),'d/m/Y')}}</td>
          <td>{{$prospecto->tipoProspecto}}</td>
          <td>{{$prospecto->tipoCliente}}</td>
          <td>{{$prospecto->tipoProducto}}</td>
          <td>{{'$ '.number_format($prospecto->montoSolicitud,2)}}</td>
          <td>{{$prospecto->estatus}}</td>
          <td>
            <a href="{{URL::action('ProspectobcController@edit',['id'=>$prospecto->folio])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnedit" rel="tooltip" title="Editar"><i class="material-icons">edit</i></button></a>
          </td>
          <td>
            <a href="{{url('agenda/promocion', ['id'=>$prospecto->folio])}}" data-method="delete" data-confirm="¿Desea eliminar el registro?">
              <button rel="tooltip" title="Eliminar" class="btn btn-primary btn-danger btn-xs"><i class="material-icons">delete</i></button>
            </a>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
