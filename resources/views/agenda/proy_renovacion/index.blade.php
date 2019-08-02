@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Agenda de renovaciones</h3>

    @include('agenda.proy_renovacion.search')

  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Vencimiento en plazo</p></caption>
        <thead>
          <tr>
          <th>ID Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Fin</th>
          <th>Max Atraso</th>
          <th>Monto Credito</th>
          <th>Dom. Colonia</th>
          <th>Celular</th>
          <th>Oferta</th>
          <th>Monto Oferta</th>
        </tr>
        </thead>
       @foreach ($vencimientos as $vencimiento)
        <tr>
          {{-- <td>{{$liquidado->idCliente}}</td>v --}}
          <td>{{$vencimiento->idCredito}}</td>
          <td>{{$vencimiento->nomCliente}}</td>
          <td>{{date_format(date_create($vencimiento->fechaFin),'d/m/Y')}}</td>
          <td>{{$vencimiento->maxDiasAtraso}}</td>
          <td>{{'$ '.number_format($vencimiento->montoInicial,2)}}</td>
          <td>{{$vencimiento->colonia}}</td>
          <td>{{$vencimiento->telefonoCelular}}</td>
          {{-- <td>{{$vencimiento->oferta}}</td>
          <td>{{'$ '.number_format($vencimiento->monto,2)}}</td> --}}
          <td>
            <a href="{{URL::action('SocioeconomicoController@create',['id'=>$vencimiento->idCredito])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">monetization_on</i></button></a>
          </td>
          <td>
            {{-- @if ()
            <button class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-backdrop="false" data-target="#ofertas"><i class="material-icons">info</i></button>                
            @else
                
            @endif --}}
            <button class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-backdrop="false" data-target="#ofertas" onclick="ofertas({{ $vencimiento->idCredito }});"><i class="material-icons">info</i></button>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    {{$vencimientos->render()}}
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ofertas" tabindex="-1" role="dialog" aria-labelledby="oferta" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="panel panel-success">
          <div class="panel-heading">

          </div>
          <div class="panel-body">
            <div class="responsive">

            </div>
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Plazo</th>
                  <th>Monto</th>
                  <th>Parcialidad</th>
                  <th>Frecuencia</th>
                </tr>
              </thead>
              <tbody id="tabla">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- <div class="row">
  <div class="col-xs-12">
    <div class="table-responsive">
      <table class="table table-striped table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Creditos Liquidados ult 6 meses</p></caption>
        <thead>
          <tr>
          <th>ID Cliente</th>
          <th>ID Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Liquidación</th>
          <th>Max Atraso</th>
          <th>Monto Credito</th>
          <th>Dom. Colonia</th>
          <th>Celular</th>
          <th>Oferta</th>
          <th>Monto oferta</th>
        </tr>
        </thead>
        @foreach ($liquidados as $liquidado)
        <tr>
          <td>{{$liquidado->idCliente}}</td>v
          <td>{{$liquidado->idCredito}}</td>
          <td>{{$liquidado->nomCliente}}</td>
          <td>{{date_format(date_create($liquidado->fechaFin),'d/m/Y')}}</td>
          <td>{{$liquidado->maxDiasAtraso}}</td>
          <td>{{'$ '.number_format($liquidado->montoInicial,2)}}</td>
          <td>{{$liquidado->colonia}}</td>
          <td>{{$liquidado->telefonoCelular}}</td>
          <td>{{$liquidado->oferta}}</td>
          <td>{{'$ '.number_format($liquidado->monto,2)}}</td>
          <td>
            <a href="{{URL::action('socioeconomicoController@create',['id'=>$liquidado->idCredito])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">check_circle</i></button></a>
          </td>
          <td>
            <a href="#"><button rel="tooltip" title="Mapa" class="btn btn-primary btn-simple btn-xs"><i class="material-icons">place</i></button></a>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    {{$liquidados->render()}}
  </div>
</div> --}}

@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
      let fechai, fechaf = "";
      let tipo, plazo = "";
      let monto, parcialidad, frecuencia = 0;
      function ofertas(id){
        $.ajax({
          url     :  "/ofertas/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
              console.log(response[0]['fechai']);
              $('#tabla').empty();
              for(i=0;i<response.length;i++){
                fechai      = response[i]['fechai'];
                fechaf      = response[i]['fechaf'];
                plazo       = response[i]['plazo'];
                monto       = response[i]['monto'];
                parcialidad    = response[i]['parcialidad'];
                frecuencia     = response[i]['frecuencia'];           

                $('#tabla').append("<tr>"+
                    "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>"+monto+"</td><td>"+parcialidad+"</td><td>"+frecuencia+" </td></tr>"
                );
              }
              //$("#miModal").modal("show");
          },
          error   :   function() {
              alert('error');
          }
        });
      }
    </script>
@endpush
