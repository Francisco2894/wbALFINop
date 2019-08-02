@extends('layouts.admin')
@section('contenido')
  @if ($records>0 || $updaterecords>0)
    <h2></h2>
    <div class="row">
      <div class="alert alert-success col-xs-6">
       <strong> {{$proceso}}</strong>
        <div class="container-fluid">
          <div class="alert-icon">
            <i class="material-icons">check</i>
          </div>
            Se importaron <strong>{{$records}}</strong> pagos nuevos
        </div>
        <div class="container-fluid">
          <div class="alert-icon">
            <i class="material-icons">check</i>
          </div>
            Se actualizaron <strong>{{$updaterecords}}</strong> pagos
        </div>
      </div>
    </div>
 @endif
 @if ($records2>0 || $updaterecords2>0)
   <h2></h2>
   <div class="row">
     <div class="alert alert-success col-xs-6">
      <strong> {{$proceso2}}</strong>
       <div class="container-fluid">
         <div class="alert-icon">
           <i class="material-icons">check</i>
         </div>
           Se importaron <strong>{{$records2}}</strong> pagos nuevos
       </div>
       <div class="container-fluid">
         <div class="alert-icon">
           <i class="material-icons">check</i>
         </div>
           Se actualizaron <strong>{{$updaterecords2}}</strong> pagos
       </div>
     </div>
   </div>
@endif
   <form class="form-horizontal" method="POST" action="{{action('ExcelController@importPago')}}" files=”true” enctype="multipart/form-data">
      {{ csrf_field() }}
     <div class="row">
          <div class="col-xs-6">
            <div class="title">
              <h3>Importar Aplicación de Pagos</h3>
            </div>
            <div class="card card-nav">
            <div class="content">
              <label for="apfile">Reporte Aplicación de Pagos</label>
            <input class="btn btn-simple" type="file" name="apfile" accept=".csv" value="Enviar">
             <button class="btn btn-primary btn-sm" type="submit"><i class="material-icons">save</i>  GUARDAR EN BD</button>
             </div>
            </div>
            </div>
        </div>
      </form>

@endsection
