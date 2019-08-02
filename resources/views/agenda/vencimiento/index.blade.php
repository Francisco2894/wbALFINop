@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Agenda de Vencimientos del Asesor</h3>

    @include('agenda.vencimiento.search')

  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Vencimiento en plazo del mes</p></caption>
        <thead>
          <tr>
          <th>ID Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Fin</th>
          <th>Max Atraso</th>
          <th>Monto credito</th>
          <th>Dom. Colonia</th>
          <th>Celular</th>
          <th>¿Renueva?</th>
          <th>Monto que renovará</th>
        </tr>
        </thead>
        @foreach ($vencimientos as $vencimiento)
        <tr>
          <td>{{$vencimiento->idCredito}}</td>
          <td>{{$vencimiento->nomCliente}}</td>
          <td>{{date_format(date_create($vencimiento->fechaFin),'d/m/Y')}}</td>
          <td>{{$vencimiento->maxDiasAtraso}}</td>
          <td>{{'$ '.number_format($vencimiento->montoInicial,2)}}</td>
          <td>{{$vencimiento->colonia}}</td>
          <td>{{$vencimiento->telefonoCelular}}</td>
          <td>{{$vencimiento->renueva}}</td>
          <td>{{'$ '.number_format($vencimiento->montoRenovacion,2)}}</td>
          <td>
            <a href="{{URL::action('RenovacionController@create',['id'=>$vencimiento->idCredito])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnRenovacion" rel="tooltip" title="¿Renueva?"><i class="material-icons">check_circle</i></button></a>
          </td>
          <td>
            <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    {{$vencimientos->render()}}
  </div>
</div>

@endsection
