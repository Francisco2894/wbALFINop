@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Resumen de Cartera al {{date("d/m/Y")}}</p></caption>
        <thead class="thead-inverse">
          <tr>
          <th></th>
          <th></th>
          <th colspan="2" class="text-center">Mes Anterior</th>
           <th colspan="2" class="text-center">Mes Actual</th>
          <th class="text-center"></th>
        </tr>
          <tr>
          <th>Agrupador</th>
          <th>Concepto</th>
          <th class="text-center">Numero</th>
          <th class="text-center">Monto</th>
          <th class="text-center danger">Numero</th>
          <th class="text-center danger">Monto</th>
          <th class="text-center">Porcentaje</th>
        </tr>
        </thead>
        @foreach ($rscartera as $r)
        <tr>
          <td>{{$r->Agrupador}}</td>
          <td>{{$r->Concepto}}</td>
          <td class="text-center">{{$r->ConteoMA}}</td>
          <td class="text-right">{{'$'.number_format($r->MontoMA,2)}}</td>
           @if ($r->id==4)
          <td class="text-center"><a href="{{URL::action('ExcelController@downNoAbonado')}}"> {{$r->Conteo}}</a></td>
        @elseif ($r->id==5)
          <td class="text-center"><a href="{{URL::action('ExcelController@downInactivo')}}"> {{$r->Conteo}}</a></td>
        @elseif ($r->id==15)
          <td class="text-center">{{$r->Conteo}}</td>
        @elseif ($r->id==16)
          <td class="text-center"><a href="{{URL::action('ExcelController@downDevVenc')}}"> {{$r->Conteo}}</a></td>
        @elseif ($r->id==17)
          <td class="text-center">{{$r->Conteo}}</td>
        @elseif ($r->id==18)
          <td class="text-center"><a href="{{URL::action('ExcelController@downDevParc')}}"> {{$r->Conteo}}</a></td>
          @else
            <td class="text-center">{{$r->Conteo}}</td>
           @endif
           <td class="text-right">{{'$'.number_format($r->Monto,2)}}</td>
           <td class="text-center">{{number_format($r->Normalidad,2).' %'}}</td>
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
          <caption class="text-center"> <p class="h4">Detalle de Cartera al {{date("d/m/Y")}}</p></caption>
        <thead>
          <tr>
          <th>Sucursal</th>
          @if (Auth::user()->idNivel>3)
          <th>Cve Vendedor</th>
          <th>Nombre Vendedor</th>
          @endif
          <th>Concepto</th>
          <th class="text-center">Numero</th>
          <th class="text-center">Monto</th>
          <th class="text-center">Porcentaje</th>
        </tr>
        </thead>
        @foreach ($cartera as $r)
          @if ($r->descripcion=='TOTAL CLIENTES')
            <tr class="success">
            @else
            <tr>
          @endif

          <td>{{$r->sucursal}}</td>
          @if (Auth::user()->idNivel>3)
          <td>{{$r->idPerfil}}</td>
          <td>{{$r->nombre}}</td>
          @endif
          <td>{{$r->descripcion}}</td>
          <td class="text-center">{{$r->Conteo}}</td>
          <td class="text-right">{{'$'.number_format($r->Monto,2)}}</td>
          <td class="text-center">{{number_format($r->Normalidad,2).' %'}}</td>
        </tr>
        @endforeach
      </table>
    </div>
    {{--{{$devengos->render()}}--}}
  </div>
</div>

@endsection
