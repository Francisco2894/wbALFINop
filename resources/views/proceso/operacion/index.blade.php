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
            Se crearon <strong>{{$records}}</strong> registros nuevos
        </div>
        <div class="container-fluid">
          <div class="alert-icon">
            <i class="material-icons">check</i>
          </div>
            Se actualizaron <strong>{{$updaterecords}}</strong> registros
        </div>
      </div>
    </div>
   @endif
     <div class="row">
          <div class="col-xs-6">
            <div class="title">
              <h3>Proceso resumen Operaciones</h3>
            </div>
            <div class="card card-nav">
            <div class="content">
              <div class="form-group col-md-2">
             <a href="{{URL::action('DashOperacionController@execDashOper')}}" > <button type="button" class="btn btn-primary">EJECUTAR PROCESO <i class="material-icons">sync</i></button></a>
           </div>
            </div>
            </div>
            </div>
        </div>
        <div class="row">
          <div class="col-xs-6">
            <div class="title">
              <h3>Proceso resumen Operaciones por producto</h3>
            </div>
            <div class="card card-nav">
            <div class="content">
              <div class="form-group col-md-2">
             <a href="{{URL::action('DashOperacionController@execDashOperSuc')}}" > <button type="button" class="btn btn-primary">EJECUTAR PROCESO <i class="material-icons">sync</i></button></a>
           </div>
            </div>
            </div>
            </div>
        </div>
        {!!Form::open(array('url'=>'agenda/dataexcel/downloadOpe','method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}

        <div class="row">
             <div class="col-xs-6">
               <div class="title">
                 <h3>Descargar resumen Operaciones</h3>
               </div>
               <div class="card card-nav">
               <div class="content">
                 <div class="row">
                   <div class="col-sm-4">
                     <div class="form-group label-floating">
                       <label class="control-label">De:   </label>
                       <input class="datepicker form-control text-center" type="text" name="dtpFechaIni" data-date-format="yyyy/mm/dd">
                       <script type="text/javascript">
                       $('.dtpFechaIni').datepicker({
                          autoclose:true,
                        });
                       </script>
                     </div>
                   </div>
                   <div class="col-sm-4">
                     <div class="form-group label-floating">
                       <label class="control-label">A:  </label>
                       <input class="datepicker form-control text-center" type="text" name="dtpFechaFin" data-date-format="yyyy/mm/dd">
                       <script type="text/javascript">
                       $('.dtpFechaFin').datepicker({
                          autoclose:true,
                        });
                       </script>
                     </div>
                   </div>
                 </div>
                 <div class="form-group col-md-2">
                <button type="submit" class="btn btn-primary">DESCARGAR RESUMEN <i class="material-icons">file_download</i></button></a>
              </div>
               </div>
               </div>
               </div>
           </div>
           {{Form::close()}}
@endsection
