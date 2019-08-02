@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Agenda de Actividades del Gestor {{date("d/m/Y")}}</h3>

    @include('agenda.gestor.search')

  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Créditos que pagan en los sig. 3 días</p></caption>
        <thead>
          <tr>
          <th><input type="checkbox" id="chkall"></th>
          <th>Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha prox devengo</th>
          <th>Cuota devengo</th>
          <th>Saldo a pagar</th>
          <th>Colonia</th>
          <th>Celular</th>
          <th>F Acuerdo</th>
          <th>Monto Acuerdo</th>
          <th>Sucursal</th>
          <!--<th>ACUERDO LOGRADO</th>-->
        </tr>
        </thead>
        @foreach ($devengos as $devengo)
        <tr>
          <td><input type="checkbox" data-id={{$devengo->idDevengo}} class="sub_chk" @if ($devengo->estatus > 0) {{ 'checked' }} @endif></td>
          <td>{{$devengo->idCredito}}</td>
          <td>{{$devengo->nomCliente}}</td>
          <td>{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
          <td>{{'$'.number_format($devengo->cuota,2)}}</td>
          <td>{{'$'.number_format($devengo->saldo,2)}}</td>
          <td>{{$devengo->colonia}}</td>
          <td>{{$devengo->telefonoCelular}}</td>
          @if (is_null($devengo->fechaAcuerdo))
            <td></td>
          @else
            <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
          @endif
          <td>{{'$'.number_format($devengo->montoAcuerdo,2)}}</td>
          <td>{{$devengo->sucursal}}</td>
          <td>
            <a href="{{URL::action('AcuerdoController@create',['id'=>$devengo->idDevengo])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnAcuerdo" rel="tooltip" title="Resultado"><i class="material-icons">check_circle</i></button></a>
          </td>
          <td>
            <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
          </td>
        </tr>
        @endforeach
        @foreach ($devengosV as $devengo)
        <tr class="danger">
          <td><input type="checkbox" data-id={{$devengo->idDevengo}} class="sub_chk" @if ($devengo->estatus > 0) {{ 'checked' }} @endif></td>
          <td>{{$devengo->idCredito}}</td>
          <td>{{$devengo->nomCliente}}</td>
          <td>{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
          <td>{{'$'.number_format($devengo->cuota,2)}}</td>
          <td>{{'$'.number_format($devengo->saldo,2)}}</td>
          <td>{{$devengo->colonia}}</td>
          <td>{{$devengo->telefonoCelular}}</td>
          @if (is_null($devengo->fechaAcuerdo))
            <td></td>
          @else
            <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
          @endif
          <td>{{'$'.number_format($devengo->montoAcuerdo,2)}}</td>
          <td>{{$devengo->sucursal}}</td>
          <td>
            <a href="{{URL::action('AcuerdoController@create',['id'=>$devengo->idDevengo])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnAcuerdo" rel="tooltip" title="Resultado"><i class="material-icons">check_circle</i></button></a>
          </td>
          <td>
            <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    {{--{{$devengos->render()}}--}}
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
        <caption class="text-center"><p class="h4">Créditos de 1 a 90 días de atraso</p></caption>
        <thead>
          <tr>
          <th><input type="checkbox" id="chkall1-30"></th>
          <th>Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Devengo</th>
          <th>Dias atraso</th>
          <th>Monto Riesgo</th>
          <th>Cuota devengo</th>
          <th>Monto exigible</th>
          <th>Colonia</th>
          <th>Celular</th>
          <th>F Acuerdo</th>
          <th>Monto Acuerdo</th>
          <th>Sucursal</th>
          <!--<th>ACUERDO LOGRADO</th>-->
        </tr>
        </thead>
        @foreach ($devengos1_90 as $devengo)
          @if ($devengo->mostrar ==1)
            <tr>
              <td><input type="checkbox" data-id={{$devengo->idDevengo}} class="sub_chk2" @if ($devengo->estatus == 1) {{ 'checked' }} @endif></td>
              <td>{{$devengo->idCredito}}</td>
              <td>{{$devengo->nomCliente}}</td>
              <td>{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
              <td>{{$devengo->diasAtraso}}</td>
              <td>{{'$'.number_format($devengo->montoRiesgo,2)}}</td>
              <td>{{'$'.number_format($devengo->cuota,2)}}</td>
              <td>{{'$'.number_format($devengo->montoExigible,2)}}</td>
              <td>{{$devengo->colonia}}</td>
              <td>{{$devengo->telefonoCelular}}</td>
              @if (is_null($devengo->fechaAcuerdo))
                <td></td>
              @else
                <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
              @endif
              <td>{{'$'.number_format($devengo->montoAcuerdo,2)}}</td>
              <td>{{$devengo->sucursal}}</td>
              <td>
                <a href="{{URL::action('AcuerdoController@create',['id'=>$devengo->idDevengo])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnAcuerdo" rel="tooltip" title="Resultado"><i class="material-icons">check_circle</i></button></a>
              </td>
              <td>
                <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
              </td>
            </tr>

          @endif
        @endforeach
        @foreach ($devengosV1_90 as $devengo)
        <tr class="danger">
          <td><input type="checkbox" data-id={{$devengo->idDevengo}} class="sub_chk2" @if ($devengo->estatus == 1) {{ 'checked' }} @endif></td>
          <td>{{$devengo->idCredito}}</td>
          <td>{{$devengo->nomCliente}}</td>
          <td>{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
          <td>{{$devengo->diasAtraso}}</td>
          <td>{{'$'.number_format($devengo->montoRiesgo,2)}}</td>
          <td>{{'$'.number_format($devengo->cuota,2)}}</td>
          <td>{{'$'.number_format($devengo->saldoExigible,2)}}</td>
          <td>{{$devengo->colonia}}</td>
          <td>{{$devengo->telefonoCelular}}</td>
          @if (is_null($devengo->fechaAcuerdo))
            <td></td>
          @else
            <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
          @endif
          <td>{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
          <td>{{$devengo->sucursal}}</td>
          <td>
            <a href="{{URL::action('AcuerdoController@create',['id'=>$devengo->idDevengo])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnAcuerdo" rel="tooltip" title="Resultado"><i class="material-icons">check_circle</i></button></a>
          </td>
          <td>
            <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    {{--{{$devengos1_7->render()}}--}}
  </div>

</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
        <caption class="text-center"><p class="h4">Créditos de más de 90 días de atraso</p></caption>
        <thead>
         <tr>
          <th><input type="checkbox" id="chkall31-90"></th>
          <th>Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Devengo</th>
          <th>Dias atraso</th>
          <th>Monto Riesgo</th>
          <th>Cuota devengo</th>
          <th>Monto exigible</th>
          <th>Colonia</th>
          <th>Celular</th>
          <th>F Acuerdo</th>
          <th>Monto Acuerdo</th>
          <th>Sucursal</th>
          <!--<th>ACUERDO LOGRADO</th>-->
        </tr>
        </thead>
        @foreach ($devengos_mas90 as $devengo)
          @if ($devengo->mostrar ==1)
            <tr>
              <td><input type="checkbox" data-id={{$devengo->idDevengo}} class="sub_chk3" @if ($devengo->estatus > 0) {{ 'checked' }} @endif></td>
              <td>{{$devengo->idCredito}}</td>
              <td>{{$devengo->nomCliente}}</td>
              <td>{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
              <td>{{$devengo->diasAtraso}}</td>
              <td>{{'$'.number_format($devengo->montoRiesgo,2)}}</td>
              <td>{{'$'.number_format($devengo->cuota,2)}}</td>
              <td>{{'$'.number_format($devengo->montoExigible,2)}}</td>
              <td>{{$devengo->colonia}}</td>
              <td>{{$devengo->telefonoCelular}}</td>
              @if (is_null($devengo->fechaAcuerdo))
                <td></td>
              @else
                <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
              @endif
              <td>{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
              <td>{{$devengo->sucursal}}</td>
              <td>
                <a href="{{URL::action('AcuerdoController@create',['id'=>$devengo->idDevengo])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnAcuerdo" rel="tooltip" title="Resultado"><i class="material-icons">check_circle</i></button></a>
              </td>
              <td>
                <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
              </td>
            </tr>
          @endif
        @endforeach
        @foreach ($devengosV_mas90 as $devengo)
        <tr class="danger">
          <td><input type="checkbox" data-id={{$devengo->idDevengo}} class="sub_chk3" @if ($devengo->estatus > 0) {{ 'checked' }} @endif></td>
          <td>{{$devengo->idCredito}}</td>
          <td>{{$devengo->nomCliente}}</td>
          <td>{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
          <td>{{$devengo->diasAtraso}}</td>
          <td>{{'$'.number_format($devengo->montoRiesgo,2)}}</td>
          <td>{{'$'.number_format($devengo->cuota,2)}}</td>
          <td>{{'$'.number_format($devengo->saldoExigible,2)}}</td>
          <td>{{$devengo->colonia}}</td>
          <td>{{$devengo->telefonoCelular}}</td>
          @if (is_null($devengo->fechaAcuerdo))
            <td></td>
          @else
            <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
          @endif
          <td>{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
          <td>{{$devengo->sucursal}}</td>
          <td>
            <a href="{{URL::action('AcuerdoController@create',['id'=>$devengo->idDevengo])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnAcuerdo" rel="tooltip" title="Resultado"><i class="material-icons">check_circle</i></button></a>
          </td>
          <td>
            <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    {{--{{$devengos8_90->render()}}--}}
  </div>
</div>

@endsection
