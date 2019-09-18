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
          @if (auth()->user()->idNivel !=4 && auth()->user()->idNivel !=3)
          <th>Cal. Oferta</th>              
          @endif
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
          @if (auth()->user()->idNivel !=4 && auth()->user()->idNivel !=3)
            <td class="text-center">
              @if (count($vencimiento->actividades) > 0)
                <a href="{{URL::action('SocioeconomicoController@edit',$vencimiento->idCredito)}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">monetization_on</i></button></a>
              @else
                <a href="{{URL::action('SocioeconomicoController@create',['id'=>$vencimiento->idCredito])}}" ><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Socioeconomicos"><i class="material-icons">monetization_on</i></button></a>
              @endif 
            </td>
          @endif
          <td class="text-center">
            @foreach ($actividades as $actividad)
              @if ($vencimiento->idCliente == $actividad->idcliente)
                <a href="{{ route('socioeconomico.show',$vencimiento->idCredito) }}"><button class="btn btn-primary btn-simple tn-xs" name="btnSocioeconomico" rel="tooltip" title="Registrado"><i class="material-icons">done</i></button></a>
              @endif 
            @endforeach
          </td>
          @if (auth()->user()->idNivel !=4 && auth()->user()->idNivel !=3)
            <td class="text-center">
              @foreach ($actividades as $actividad)
                @if ($vencimiento->idCliente == $actividad->idcliente)
                  {!!Form::open(['route'=>'califiaroferta','method'=>'POST', 'id'=>"calificar$vencimiento->idCredito"])!!}
                    <input type="hidden" value="{{ $vencimiento->idCredito }}" name="idCredito">
                    <button class="btn btn-primary btn-simple btn-xs" type="button" onclick="calificar({{ $vencimiento->idCredito }});" name="btnSocioeconomico" rel="tooltip" title="¿Calificar?"><i class="material-icons">playlist_add_check</i></button>
                  {{Form::close()}}
                @endif 
              @endforeach
            </td>
          @endif 
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
                  <button class="btn btn-primary btn-simple btn-xs" onclick="verificarOferta({{ $vencimientoOferta->idCredito }});"><i class="material-icons">info</i></button>
                </td>
                <td class="text-center">
                  <a href="{{ route('pdfrenovacion',['cliente'=>$vencimientoOferta->idCredito,'sucursal'=>$querys]) }}" style="{{ count($vencimientoOferta->oferta)==1?'':'display: none;' }}" id="pdf{{ $vencimientoOferta->idCredito }}"><button class="btn btn-primary btn-simple btn-xs" name="btnSocioeconomico" rel="tooltip" title="Descargar"><i class="material-icons">save_alt</i></button></a>
                </td>
              </tr>
            @endif 
        @endforeach
        </table>
      </div>
      {{$vencimientosOfertas->render()}}
    </div>
  </div>

  
  @include('agenda.proy_renovacion.partials.modalCalificar')
  @include('agenda.proy_renovacion.partials.modalOfertas')
  @include('agenda.proy_renovacion.partials.modalOfertaAceptada')
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ url('/assets/js/moment.js') }}"></script>
    <script>
      let fechai, fechaf = "";
      let tipo, plazo = "";
      let idOferta, monto, parcialidad, frecuencia, garantia = 0;
      let texto;
      let status = 0;
      let panel = 0;

      function verificarOferta(id)
      {
        panel = id;
        $("#ofertas").appendTo("body").modal('show');
        $('#tablaproductivo').empty();
        $('#tablavivienda').empty();
        $.ajax({
          url     :  "{{ url('verificar_oferta') }}/"+id,
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
      
      function calificar(credito){
        $('#idCalificar').val(credito);
        $("#calificarDatos").appendTo("body").modal('show');
      }
      function verificarCalificacion(){
        var credito = $('#idCalificar').val();
        $('#aceptar').prop('disabled', true);
        $('#cancelar').prop('disabled', true);
        console.log(credito)
        //var calificar = confirm('Solo podra Calificar esta Oferta 1 vez, ¿Esta seguro de Calificarla Ahora?')
        // if (calificar == true) {
        $('#calificar'+credito).submit(); 
        // }
      }

      function listarOferta(id){
        $.ajax({
          url     :  "{{ url('ofertas') }}/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
                if(response.ofertas.length>0){
                  console.log(response.ofertas[0]['idto']);
                  $('#tablaproductivo').empty();
                  $('#tablavivienda').empty();
                  console.log(response)
                  for(i=0;i<response.ofertas.length;i++){
                    idOferta    = response.ofertas[i]['idoferta'];
                    fechai      = response.ofertas[i]['fechai'];
                    fechaf      = response.ofertas[i]['fechaf'];
                    plazo       = response.ofertas[i]['plazo'];
                    frecuencia      = response.ofertas[i]['frecuencia'];
                    monto           = response.ofertas[i]['monto'];
                    garantia        = monto*0.10;
                    parcialidad     = response.ofertas[i]['cuota'];
                    tipo            = response.ofertas[i]['idto'];
                    status          = response.ofertas[i]['status'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      if (status == 1) {
                        $('#tablaproductivo').append("<tr class='success'>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                            "<button type='button' style='border:0px;margin:0;' class='btn btn-success btn-xs btn-simple'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                        );
                      } else {
                        $('#tablaproductivo').append("<tr>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-xs btn-simple'><i class='material-icons'>cancel</i></button></td></tr>"
                        );
                      } 
                    } else {
                      if (status == 1) {
                        $('#tablavivienda').append("<tr class='success'>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-success btn-xs btn-simple'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                        );
                      } else {
                        $('#tablavivienda').append("<tr>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-xs btn-simple'><i class='material-icons'>cancel</i></button></td></tr>"
                        ); 
                      }
                    }
                }
              }
              $('#titulo').text("Oferta ID Cliente: "+response.ofertas[0]['idcliente']+' Nombre del Cliente: '+response.nombre);
              //$("#miModal").modal("show");
          },
          error   :   function() {
              alert('error');
          }
        });
      }
      
      function ofertaAceptada(){
        var id = $('#idOfertaAceptada').val();
        $('#aceptarOferta').prop('disabled', true);
        $('#cancelarOferta').prop('disabled', true);
        $("#ofertaAceptada").modal('hide');
        var bd = $('<div class="modal-backdrop"></div>');
        bd.appendTo(document.body);
        setTimeout(function () {
          bd.remove();
          $("#ofertas").modal("show");
        }, 1000);

        $('#aceptarOferta').prop('disabled', false);
        $('#cancelarOferta').prop('disabled', false);
        console.log(id);

        $('#tablaproductivo').empty();
        $('#tablavivienda').empty();
        $.ajax({
          url     :  "{{ url('oferta_aceptada') }}/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
            console.log(response);
                if(response.ofertas.length>0){
                  console.log(response.ofertas[0]['idto']);
                  $('#tablaproductivo').empty();
                  $('#tablavivienda').empty();
                  for(i=0;i<response.ofertas.length;i++){
                    idOferta    = response.ofertas[i]['idoferta'];
                    fechai      = response.ofertas[i]['fechai'];
                    fechaf      = response.ofertas[i]['fechaf'];
                    plazo       = response.ofertas[i]['plazo'];
                    frecuencia      = response.ofertas[i]['frecuencia'];
                    monto       = response.ofertas[i]['monto'];
                    garantia    = monto*0.10;
                    parcialidad     = response.ofertas[i]['cuota'];
                    tipo            = response.ofertas[i]['idto'];
                    status          = response.ofertas[i]['status'];
                    idCredito       = response.ofertas[i]['idcredito'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      if (status == 1) {
                        $('#tablaproductivo').append("<tr class='success'>"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-success btn-xs btn-simple'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                        );
                      } else {
                        $('#tablaproductivo').append("<tr>"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-xs btn-simple'><i class='material-icons'>cancel</i></button></td></tr>"
                        );
                      } 
                    } else {
                      if (status == 1) {
                        $('#tablavivienda').append("<tr class='success'>"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-success btn-xs btn-simple'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                        );
                      } else {
                        $('#tablavivienda').append("<tr>"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-xs btn-simple'><i class='material-icons'>cancel</i></button></td></tr>"
                        ); 
                      }
                    }
                }
              }
              $('#titulo').text("Oferta ID Cliente: "+response.ofertas[0]['idcliente']+' Nombre del Cliente: '+response.nombre);
              $("#miModal").modal("show");
              console.log('#pdf'+idCredito)
              $('#pdf'+idCredito).show();
          },
          error   :   function() {
              alert('error');
          }
        });
      }

      function ofertaSeleccionada(id){
        $('#idOfertaAceptada').val(id);
        $("#ofertas").modal('hide');
        $("#ofertaAceptada").appendTo("body").modal('show');
      }

      function ofertas(id){
        $.ajax({
          url     :  "{{ url('ofertas') }}/"+id,
          type    :  'get',
          dataType:  'json',
          success :   function (response) {
                if(response.ofertas.length>0){
                  console.log(response);
                  $('#tablaproductivo').empty();
                  $('#tablavivienda').empty();
                  for(i=0;i<response.ofertas.length;i++){
                    idOferta    = response.ofertas[i]['idoferta'];
                    fechai      = response.ofertas[i]['fechai'];
                    fechaf      = response.ofertas[i]['fechaf'];
                    plazo       = response.ofertas[i]['plazo'];
                    frecuencia      = response.ofertas[i]['frecuencia'];
                    monto       = response.ofertas[i]['monto'];
                    garantia    = monto*0.10;
                    parcialidad     = response.ofertas[i]['cuota'];
                    tipo            = response.ofertas[i]['idto'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if ({{ auth()->user()->idNivel }} !=4 && {{ auth()->user()->idNivel }} !=3) {
                      if (tipo == 1) {
                        $('#tablaproductivo').append("<tr >"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-primary btn-xs btn-simple' onclick='ofertaSeleccionada("+idOferta+")'><i class='material-icons'>check_circle_outline</i></button> </td></tr>"
                        ); 
                      } else {
                        $('#tablavivienda').append("<tr >"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<button type='button' style='border:0px;margin:0;' class='btn btn-primary btn-xs btn-simple' onclick='ofertaSeleccionada("+idOferta+")'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                        );
                      } 
                    }else{
                      if (tipo == 1) {
                        $('#tablaproductivo').append("<tr >"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"
                        ); 
                      } else {
                        $('#tablavivienda').append("<tr >"+
                          "<td style='padding: 0;'>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(garantia)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"
                        );
                      }
                    }
                }
              }
              $('#titulo').text("Oferta ID Cliente: "+response.ofertas[0]['idcliente']+' Nombre del Cliente: '+response.nombre);
              //$("#miModal").modal("show");
          },
          error   :   function() {
              alert('error');
          }
        });        
      }
    </script>
@endpush
