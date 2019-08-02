@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Renovaciones</h3>
    @if (count($errors)>0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif
    {!!Form::open(array('url'=>'agenda/renovacion','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::token()}}
    <div class="row">
           <div class="col-xs-4">
             <div class="form-group label-floating">
               <label class="control-label">¿Renueva?</label>
                  {!! Form::select('sltRenueva',$renueva,0,['class'=>'form-control','id'=>'sltRenueva']) !!}
             </div>
           </div>
         </div>
         <div class="row">
           <div class="col-sm-4">
             <div class="form-group label-floating">
               <label class="control-label">Monto que renovará</label>
               <input class="form-control" type="number" name="txtMontoRenovacion" value="0.00">
             </div>
           </div>
         </div>
         <div class="row">
						<div class="col-xs-6">
							<div class="form-group label-floating">
								<label class="control-label">Descripción o comentarios</label>
								<input class="form-control"style="text-transform:uppercase;" type="text" name="txtDescripcion" >
							</div>
						</div>
					</div>
          <div class="row">
						<div class="col-sm-4">
							<div class="form-group label-floating">
								<label class="control-label">Fecha</label>
								<input class="datepicker form-control" type="text" name="dtpFechaRenovacion" data-date-format="yyyy/mm/dd" value="{{date("Y/m/d")}}">
                <script type="text/javascript">
                $('.datepicker').datepicker({
                   autoclose:true
                 });
                </script>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
								<input class="form-control" type="hidden" name="sltIdCredito" value="{{$id}}">
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
