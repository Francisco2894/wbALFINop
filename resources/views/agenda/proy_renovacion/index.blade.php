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
      <table class="table table-condensed table-hover">
          <caption class="text-center"> <p class="h4">Vencimientos</p></caption>
        <thead>
          <tr>
          <th>ID Crédito</th>
          <th>ID Cliente</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Fin</th>
          <th>Max Atraso</th>
          <th>Monto Credito</th>
          <th>Dom. Colonia</th>
          <th>Celular</th>
          <th>Socioeconomico</th>
          <th>&nbsp;</th>
          <th>Cal. Oferta</th>
        </tr>
        </thead>
        @php
            $i=0;
        @endphp
       @foreach ($vencimientos as $vencimiento)
        <tr class="{{ count($vencimiento->actividades) > 0 ? 'bg-success' : '' }}">
          <td>{{$vencimiento->idCredito}}</td>
          <td>{{$vencimiento->idCliente}}</td>
          <td>{{$vencimiento->nomCliente}}</td>
          <td>{{date_format(date_create($vencimiento->fechaFin),'d/m/Y')}}</td>
          <td>{{$vencimiento->maxDiasAtraso}}</td>
          <td>{{'$ '.number_format($vencimiento->montoInicial,2)}}</td>
          <td>{{$vencimiento->colonia}}</td>
          <td>{{$vencimiento->telefonoCelular}}</td>
          <td class="text-center">
              @if (count($vencimiento->actividades) > 0)
              <a href="{{URL::action('SocioeconomicoController@edit',$vencimiento->idCredito)}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">monetization_on</i></button></a>
              @else
              <a href="{{URL::action('SocioeconomicoController@create',['id'=>$vencimiento->idCredito])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">monetization_on</i></button></a>
              @endif 
          </td>
          <td class="text-center">
            @foreach ($actividades as $actividad)
              @if ($vencimiento->idCliente == $actividad->idcliente)
                <a href="{{ route('socioeconomico.show',$vencimiento->idCredito) }}"><button class="btn btn-primary btn-simple tn-xs" name="btnSocioeconomico" rel="tooltip" title="Registrado"><i class="material-icons">done</i></button></a>
              @endif 
            @endforeach
          </td>
          <td class="text-center">
            @foreach ($actividades as $actividad)
              @if ($vencimiento->idCliente == $actividad->idcliente)
                {!!Form::open(['route'=>'califiaroferta','method'=>'POST'])!!}
                  <input type="hidden" value="{{ $vencimiento->idCredito }}" name="idCredito">
                  <button class="btn btn-primary btn-simple btn-xs" type="submit" name="btnSocioeconomico" rel="tooltip" title="¿Calificar?"><i class="material-icons">playlist_add_check</i></button>
                {{Form::close()}}
              @endif 
            @endforeach
          </td>
        </tr>
      @endforeach
      </table>
    </div>
    {{$vencimientos->render()}}
  </div>
</div>
<div class="row">
    <div class="col-xs-12">
      <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <caption class="text-center"> <p class="h4">Ofertas</p></caption>
          <thead>
            <tr>
            <th>ID Crédito</th>
            <th>ID Cliente</th>
            <th>Nombre del Cliente</th>
            <th>Fecha Fin</th>
            <th>Max Atraso</th>
            <th>Monto Credito</th>
            <th>Dom. Colonia</th>
            <th>Celular</th>
            <th>Socioeconomico</th>
            <th>Oferta</th>
            <th>PDF</th>
          </tr>
          </thead>
         @foreach ($vencimientosOfertas as $vencimientoOferta)

            @if (count($vencimientoOferta->ofertas) > 0)
              <tr>
                {{-- <td>{{$liquidado->idCliente}}</td>v --}}
                <td>{{ $vencimientoOferta->idCredito }}</td>
                <td>{{ $vencimientoOferta->idCliente }}</td>
                <td>{{$vencimientoOferta->nomCliente}}</td>
                <td>{{date_format(date_create($vencimientoOferta->fechaFin),'d/m/Y')}}</td>
                <td>{{$vencimientoOferta->maxDiasAtraso}}</td>
                <td>{{'$ '.number_format($vencimientoOferta->montoInicial,2)}}</td>
                <td>{{$vencimientoOferta->colonia}}</td>
                <td>{{$vencimientoOferta->telefonoCelular}}</td>
                <td class="text-center">
                  <a href="{{ route('informacion',$vencimientoOferta->idCredito) }}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">monetization_on</i></button></a>
                </td>
                <td class="text-center">
                  <button class="btn btn-primary btn-simple btn-xs" data-toggle="modal" data-backdrop="false" data-target="#ofertas" onclick="verificarOferta({{ $vencimientoOferta->idCredito }});"><i class="material-icons">info</i></button>
                </td>
                <td class="text-center">
                  <a href="{{ route('pdfrenovacion',['cliente'=>$vencimientoOferta->idCredito,'sucursal'=>$querys]) }}" style="{{ count($vencimientoOferta->oferta)==1?'':'display: none;' }}" id="pdf{{ $vencimientoOferta->idCredito }}"><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Descargar"><i class="material-icons">save_alt</i></button></a>
                </td>
              </tr>
              @break
            @endif 
          
        @endforeach
        </table>
      </div>
      {{$vencimientosOfertas->render()}}
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="ofertas" tabindex="-1" role="dialog" aria-labelledby="oferta" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body">
        <div class="panel panel-success">
          <div class="panel-heading" id="titulo">
          </div>
          <div class="panel-body">
            <div class="responsive">
              <h4 class="title">Productivo</h4>
              <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Plazo</th>
                      <th>Monto</th>
                      <th>Cuota</th>
                      <th>Frecuencia</th>
                      <th style="width: 8%"></th>
                    </tr>
                  </thead>
                  <tbody id="tablaproductivo">
                  </tbody>
                </table>
                <hr>
                <h4 class="title">Vivienda</h4>
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Plazo</th>
                      <th>Monto</th>
                      <th>Cuota</th>
                      <th>Frecuencia</th>
                      <th style="width: 8%"></th>
                    </tr>
                  </thead>
                  <tbody id="tablavivienda">
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
      let fechai, fechaf = "";
      let tipo, plazo = "";
      let idOferta, monto, parcialidad, frecuencia = 0;
      let texto;
      let status = 0;

      function verificarOferta(id)
      {
        $.ajax({
          url     :  "/verificar_oferta/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
            console.log(response['status']);
                if(response['status'] == 1){
                  listarOferta(id)
                }else{
                  ofertas(id);
                }
          },
          error   :   function() {
              alert('error');
          }
        });
      }
      
      function listarOferta(id){
        $.ajax({
          url     :  "/ofertas/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
                if(response.length>0){
                  console.log(response[0]['idto']);
                  $('#tablaproductivo').empty();
                  $('#tablavivienda').empty();
                  for(i=0;i<response.length;i++){
                    idOferta    = response[i]['idoferta'];
                    fechai      = response[i]['fechai'];
                    fechaf      = response[i]['fechaf'];
                    plazo       = response[i]['plazo'];
                    monto       = response[i]['monto'];
                    parcialidad     = response[i]['cuota'];
                    frecuencia      = response[i]['frecuencia'];
                    tipo            = response[i]['idto'];
                    status          = response[i]['status']
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      if (status == 1) {
                        $('#tablaproductivo').append("<tr class='success'>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>check_circle_outline</i></td></tr>"
                        );
                      } else {
                        $('#tablaproductivo').append("<tr>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>cancel</i></td></tr>"
                        );
                      } 
                    } else {
                      if (status == 1) {
                        $('#tablavivienda').append("<tr class='success'>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>check_circle_outline</i></td></tr>"
                        );
                      } else {
                        $('#tablavivienda').append("<tr>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>cancel</i></td></tr>"
                        ); 
                      }
                    }
                }
              }
              $('#titulo').text("Oferta ID Cliente: "+response[0]['idcliente']);
              //$("#miModal").modal("show");
          },
          error   :   function() {
              alert('error');
          }
        });
      }

      function ofertaSeleccionada(id){
        $.ajax({
          url     :  "/oferta_aceptada/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
            console.log(response);
                if(response.length>0){
                  console.log(response[0]['idto']);
                  $('#tablaproductivo').empty();
                  $('#tablavivienda').empty();
                  for(i=0;i<response.length;i++){
                    idOferta    = response[i]['idoferta'];
                    fechai      = response[i]['fechai'];
                    fechaf      = response[i]['fechaf'];
                    plazo       = response[i]['plazo'];
                    monto       = response[i]['monto'];
                    parcialidad     = response[i]['cuota'];
                    frecuencia      = response[i]['frecuencia'];
                    tipo            = response[i]['idto'];
                    status          = response[i]['status'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      if (status == 1) {
                        $('#tablaproductivo').append("<tr class='success'>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>check_circle_outline</i></td></tr>"
                        );
                      } else {
                        $('#tablaproductivo').append("<tr>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>cancel</i></td></tr>"
                        );
                      } 
                    } else {
                      if (status == 1) {
                        $('#tablavivienda').append("<tr class='success'>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>check_circle_outline</i></td></tr>"
                        );
                      } else {
                        $('#tablavivienda').append("<tr>"+
                          "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                          "<i class='material-icons'>cancel</i></td></tr>"
                        ); 
                      }
                    }
                }
              }
              $('#titulo').text("Oferta ID Cliente: "+response[0]['idcliente']);
              $("#miModal").modal("show");
              $('pdf'+id).show();//aqui quede
          },
          error   :   function() {
              alert('error');
          }
        });
      }
      function ofertas(id){
        $.ajax({
          url     :  "/ofertas/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
                if(response.length>0){
                  console.log(response[0]['idto']);
                  $('#tablaproductivo').empty();
                  $('#tablavivienda').empty();
                  for(i=0;i<response.length;i++){
                    idOferta    = response[i]['idoferta'];
                    fechai      = response[i]['fechai'];
                    fechaf      = response[i]['fechaf'];
                    plazo       = response[i]['plazo'];
                    monto       = response[i]['monto'];
                    parcialidad     = response[i]['cuota'];
                    frecuencia      = response[i]['frecuencia'];
                    tipo            = response[i]['idto'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      $('#tablaproductivo').append("<tr>"+
                        "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                        "<button type='button' class='btn btn-primary btn-xs btn-simple' onclick='ofertaSeleccionada("+idOferta+")'><i class='material-icons'>check_circle_outline</i></button> </td></tr>"
                      ); 
                    } else {
                      $('#tablavivienda').append("<tr>"+
                        "<td>"+fechai.substr(0,10)+" - "+fechaf.substr(0,10)+"</td><td>"+plazo+"</td><td>$"+monto+"</td><td>$"+parcialidad+"</td><td>"+texto+"</td><td>"+
                        "<button type='button' class='btn btn-primary btn-xs btn-simple' onclick='ofertaSeleccionada("+idOferta+")'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                      );
                    }
                }
              }
              $('#titulo').text("Oferta ID Cliente: "+response[0]['idcliente']);
              //$("#miModal").modal("show");
          },
          error   :   function() {
              alert('error');
          }
        });        
      }
    </script>
@endpush
