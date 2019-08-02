@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Editar prospecto</h3>
    @if (count($errors)>0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif
    {!!Form::model($prospecto,['method' => 'PUT','route'=>['promocion.update',$prospecto->folio]])!!}
    {{Form::token()}}
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group label-floating">
          <label class="control-label">Folio Consulta</label>
          <input class="form-control" type="number" name="folio" value="{{$prospecto->folio}}" readonly>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group label-floating">
          <label class="control-label">Score</label>
          <input class="form-control" type="number" name="score" value="{{$prospecto->score}}" >
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group label-floating">
          <label class="control-label">Fecha Consulta</label>
          <input class="datepicker form-control" type="text" name="fechaConsulta" data-date-format="yyyy/mm/dd" value="{{$prospecto->fechaConsulta}}">
          <script type="text/javascript">
          $('.fechaConsulta').datepicker({
             autoclose:true,
           });
          </script>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group label-floating">
          <label class="control-label">Nombre</label>
          <input class="form-control" style="text-transform:uppercase;" type="text" name="nombre" value="{{$prospecto->nombre}}">
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group label-floating">
          <label class="control-label">Paterno</label>
          <input class="form-control" style="text-transform:uppercase;" type="text" name="paterno" value="{{$prospecto->paterno}}">
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group label-floating">
          <label class="control-label">Materno</label>
          <input class="form-control" style="text-transform:uppercase;" type="text" name="materno" value="{{$prospecto->materno}}">
        </div>
      </div>
     </div>
     <div class="row">
       <div class="col-sm-3">
         <div class="form-group label-floating">
         <label class="control-label">Tipo de Prospecto</label>
         {{Form::select('tipoProspecto',$tiposProspecto,$prospecto->idTipoProspecto,['class'=>'form-control','id'=>'tipoProspecto'])}}
         </div>
       </div>
       <div class="col-sm-3">
         <div class="form-group label-floating">
         <label class="control-label">Tipo de Cliente</label>
         {{Form::select('tipoCliente',$tiposCliente,$prospecto->idTipoCliente,['class'=>'form-control','id'=>'tipoCliente'])}}
         </div>
       </div>
       <div class="col-sm-3">
         <div class="form-group label-floating">
         <label class="control-label">Tipo de Producto</label>
         {{Form::select('tipoProducto',$tiposProducto,$prospecto->idTipoProducto,['class'=>'form-control','id'=>'tipoProducto'])}}
         </div>
       </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group label-floating">
          <label class="control-label">Asesor</label>
           {!! Form::select('perfil',$vendedores,$prospecto->idPerfil,['class'=>'form-control','id'=>'perfil']) !!}
           </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group label-floating">
            <label class="control-label">Monto Solicitud</label>
            <input class="form-control" type="number" name="montoSolicitud" value="{{$prospecto->montoSolicitud}}" >
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group label-floating">
          <label class="control-label">Estatus</label>
           {!! Form::select('estatus',$estatus,$prospecto->idEstatus,['class'=>'form-control','id'=>'estatus']) !!}
           </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group text-center">
          <button class="btn btn-primary btn-sm" type="submit"><i class="material-icons">save</i>GUARDAR</button>
          <button class="btn btn-danger btn-sm"  type="reset">CANCELAR</button>
          <button class="btn btn-primary btn-sm" type="button" onclick="history.back()"><i class="material-icons">arrow_back</i>REGRESAR</button>
          </div>
        </div>
      </div>
    {!!Form::close()!!}
  </div>
</div>

@endsection
