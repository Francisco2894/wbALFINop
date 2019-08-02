@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Acuerdos</h3>
    @include('agenda.acuerdo.search')
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Acuerdos de 30 días</p></caption>
        <thead>
          <tr>
          <th>IdCredito</th>
          <th>Nombre Cliente</th>
          <th>Fecha Devengo</th>
          <th>Exigible</th>
          <th>Fecha Acuerdo</th>
          <th>Monto Acuerdo</th>
          <th>Tipo Acuerdo</th>
          <th>Domicilio Credito</th>
          <th>Celular</th>
          <th>Fecha Resultado</th>
          <th>Monto Resultado</th>
          <th>Resultado</th>
        </tr>
        </thead>
        @foreach ($acuerdos as $acuerdo)
        <tr>
          <td>{{$acuerdo->folio}}</td>
          <td>{{$acuerdo->nombre}}</td>
          <td>{{$acuerdo->score}}</td>
          <td>{{date_format(date_create($acuerdo->fechaConsulta),'d/m/Y')}}</td>
          <td>{{$acuerdo->tipoProspecto}}</td>
          <td>{{$acuerdo->tipoCliente}}</td>
          <td>{{$acuerdo->tipoProducto}}</td>
          <td>{{'$ '.number_format($acuerdo->montoSolicitud,2)}}</td>
          <td>{{$acuerdo->estatus}}</td>
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
