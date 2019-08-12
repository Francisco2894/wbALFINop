@extends('layouts.admin')
@section('contenido')
<br>
{!!Form::open(['route'=>'resumenAvance','method'=>'GET'])!!}

    @if (Auth::user()->idNivel===5)
      <input id="searchTxt" name="searchTxt" type="hidden" value={{Auth::user()->idPerfil}}>
    @elseif ((Auth::user()->idNivel===4) || (Auth::user()->idNivel===6))
      <div class="form-group col-md-6">
       <label for="searchTxt">ASESOR</label>
       {!! Form::select('searchTxt',$vendedores,$searchTxt,['class'=>'form-control', 'onchange'=>"this.form.submit()",'id'=>'searchTxt']) !!}
     </div>
    @else
      <div class="form-group col-md-6">
        <label for="searchTxts">SUCURSAL</label>
        {{Form::select('searchTxts',$sucursales,$searchTxts,['class'=>'form-control','id'=>'searchTxts'])}}
      </div>
      <div class="form-group col-md-6">
      <label for="searchTxt">ASESOR</label>
      {!! Form::select('searchTxt',$vendedores,$searchTxt,['class'=>'form-control', 'onchange'=>"this.form.submit()",'id'=>'searchTxt']) !!}
    </div>
        
    @endif
{{Form::close()}}

    <div class="col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title">Vencimientos Por Recopilar</h1>
            </div>
            <div class="panel-body">
                <h1 class="text-center">{{ $porRecopilar }}</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title">Vencimientos Recopilados</h1>
            </div>
            <div class="panel-body">
                <h1 class="text-center">{{ $recopilados }}</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title">Vencimentos Calificados</h1>
            </div>
            <div class="panel-body">
                <h1 class="text-center">{{ $calificados }}</h1>
            </div>
        </div>        
    </div>
@endsection