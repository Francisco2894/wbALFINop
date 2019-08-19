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
                {!!Form::open(['route'=>'califiaroferta','method'=>'POST', 'id'=>"calificar$vencimiento->idCredito"])!!}
                  <input type="hidden" value="{{ $vencimiento->idCredito }}" name="idCredito">
                  <button class="btn btn-primary btn-simple btn-xs" type="button" onclick="calificar({{ $vencimiento->idCredito }});" name="btnSocioeconomico" rel="tooltip" title="¿Calificar?"><i class="material-icons">playlist_add_check</i></button>
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
                      <th>Fecha de Vigencia</th>
                      <th>Plazo</th>
                      <th>Frecuencia</th>
                      <th>Monto</th>
                      <th>Cuota</th>
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
    <script src="/./assets/js/moment.js"></script>
    <script>
      let fechai, fechaf = "";
      let tipo, plazo = "";
      let idOferta, monto, parcialidad, frecuencia = 0;
      let texto;
      let status = 0;

      function verificarOferta(id)
      {
        $('#tablaproductivo').empty();
        $('#tablavivienda').empty();
        $.ajax({
          url     :  "/./verificar_oferta/"+id,
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
        var calificar = confirm('Solo podra Calificar esta Oferta 1 vez, ¿Esta seguro de Calificarla Ahora?')
        if (calificar == true) {
          $('#calificar'+credito).submit(); 
        }
      }

      function listarOferta(id){
        $.ajax({
          url     :  "/./ofertas/"+id,
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
                    parcialidad     = response.ofertas[i]['cuota'];
                    tipo            = response.ofertas[i]['idto'];
                    status          = response.ofertas[i]['status'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      if (status == 1) {
                        $('#tablaproductivo').append("<tr class='success'>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<i class='material-icons'>check_circle_outline</i></td></tr>"
                        );
                      } else {
                        $('#tablaproductivo').append("<tr>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<i class='material-icons'>cancel</i></td></tr>"
                        );
                      } 
                    } else {
                      if (status == 1) {
                        $('#tablavivienda').append("<tr class='success'>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<i class='material-icons'>check_circle_outline</i></td></tr>"
                        );
                      } else {
                        $('#tablavivienda').append("<tr>"+
                          "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                          "<i class='material-icons'>cancel</i></td></tr>"
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

      function ofertaSeleccionada(id){
        console.log(id);
        var confirmacion;
        confirmacion = confirm('Solo podra seleccionar una unica Oferta, ¿Esta seguro de selecionar esta?')
        if (confirmacion == true) {
          $('#tablaproductivo').empty();
          $('#tablavivienda').empty();
          $.ajax({
            url     :  "/./oferta_aceptada/"+id,
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
                            "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                            "<i class='material-icons'>check_circle_outline</i></td></tr>"
                          );
                        } else {
                          $('#tablaproductivo').append("<tr>"+
                            "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                            "<i class='material-icons'>cancel</i></td></tr>"
                          );
                        } 
                      } else {
                        if (status == 1) {
                          $('#tablavivienda').append("<tr class='success'>"+
                            "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                            "<i class='material-icons'>check_circle_outline</i></td></tr>"
                          );
                        } else {
                          $('#tablavivienda').append("<tr>"+
                            "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                            "<i class='material-icons'>cancel</i></td></tr>"
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
      }
      function ofertas(id){
        $.ajax({
          url     :  "/./ofertas/"+id,
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
                    parcialidad     = response.ofertas[i]['cuota'];
                    tipo            = response.ofertas[i]['idto'];
                    if (frecuencia == 1) {
                      texto = 'Mensual';
                    }

                    if (tipo == 1) {
                      $('#tablaproductivo').append("<tr>"+
                        "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                        "<button type='button' class='btn btn-primary btn-xs btn-simple' onclick='ofertaSeleccionada("+idOferta+")'><i class='material-icons'>check_circle_outline</i></button> </td></tr>"
                      ); 
                    } else {
                      $('#tablavivienda').append("<tr>"+
                        "<td>"+moment(fechai.substr(0,10)).format("DD-MM-YYYY")+" - "+moment(fechaf.substr(0,10)).format("DD-MM-YYYY")+"</td><td>"+plazo+"</td><td>"+texto+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(monto)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(parcialidad)+"</td><td>"+
                        "<button type='button' class='btn btn-primary btn-xs btn-simple' onclick='ofertaSeleccionada("+idOferta+")'><i class='material-icons'>check_circle_outline</i></button></td></tr>"
                      );
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
