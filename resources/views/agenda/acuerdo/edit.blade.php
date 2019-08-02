@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-xs-12">
    <h3>Editar Resultado de gestión</h3>
    @if (count($errors)>0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <!--revisar route, no direcciona ,['method' => 'PATCH','route'=>['agenda.acuerdo.update',$acuerdo->idAcuerdo]]
  *no es necesario poner la ruta completa y en lugar de PATCH, es PUT-->
    {!!Form::model($acuerdo,['method' => 'PUT','route'=>['acuerdo.update',$acuerdo->idAcuerdo]])!!}
    {{Form::token()}}
    <div class="row">
           <div class="col-xs-4">
             <div class="form-group label-floating">
               <label class="control-label">Tipo de Resultado</label>
                  {!! Form::select('sltIdResultado',$tiposResultado,$acuerdo->idResultado,['class'=>'form-control','id'=>'sltIdResultado']) !!}
             </div>
           </div>
         </div>
    <div class="row">
						<div class="col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Descripción Acuerdo</label>
								<input class="form-control" style="text-transform:uppercase;" type="text" name="txtAcuerdo" value="{{$acuerdo->acuerdo}}" >
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group label-floating">
								<label class="control-label">Monto Acordado</label>
								<input class="form-control" type="number" name="txtMontoAcuerdo" value="{{$acuerdo->montoAcuerdo}}">
							</div>
						</div>
					</div>
          <div class="row">
						<div class="col-sm-4">
							<div class="form-group label-floating">
								<label class="control-label">Fecha Acuerdo</label>
								<input class="datepicker form-control" type="text" name="dtpFechaAcuerdo" data-date-format="yyyy/mm/dd" value="{{$acuerdo->fechaAcuerdo}}">
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
								<input class="form-control" type="hidden" name="sltIdDevengo" readonly value="{{$acuerdo->idDevengo}}">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
              <div class="form-group text-center">
							<button class="btn btn-primary btn-sm" type="submit"><i class="material-icons">save</i>GUARDAR</button>
              <button class="btn btn-danger btn-sm"  type="reset">Cancelar</button>
               <button class="btn btn-primary btn-sm" type="button" onclick="history.back()"><i class="material-icons">arrow_back</i>REGRESAR</button>
              </div>
						</div>
					</div>
    {!!Form::close()!!}
  </div>
</div>

@endsection
