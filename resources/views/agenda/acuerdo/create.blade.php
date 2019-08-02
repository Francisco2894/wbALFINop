@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Resultado de gestión</h3>
    @if (count($errors)>0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif
    {!!Form::open(array('url'=>'agenda/acuerdo','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::token()}}
    <div class="row">
           <div class="col-xs-4">
             <div class="form-group label-floating">
               <label class="control-label">Tipo de Resultado</label>
                  {!! Form::select('sltIdResultado',$tiposResultado,0,['class'=>'form-control','id'=>'sltIdResultado']) !!}
             </div>
           </div>
         </div>
    <div class="row">
						<div class="col-xs-6">
							<div class="form-group label-floating">
								<label class="control-label">Descripción o comentarios</label>
								<input class="form-control" style="text-transform:uppercase;" type="text" name="txtAcuerdo" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group label-floating">
								<label class="control-label">Monto</label>
								<input class="form-control" type="number" name="txtMontoAcuerdo" value="0.00" >
							</div>
						</div>
					</div>
          <div class="row">
						<div class="col-sm-4">
							<div class="form-group label-floating">
								<label class="control-label">Fecha</label>
								<input class="datepicker form-control" type="text" name="dtpFechaAcuerdo" data-date-format="yyyy/mm/dd" value="{{date("Y/m/d")}}">
                <script type="text/javascript">
                $('.dtpFechaAcuerdo').datepicker({
                   autoclose:true,
                 });
                </script>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
								<input class="form-control" type="hidden" name="sltIdDevengo" value="{{$id}}">
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
