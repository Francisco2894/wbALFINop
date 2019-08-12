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
            Se importaron <strong>{{$records}}</strong>
        </div>
      </div>
    </div>
 @endif
  <form class="form-horizontal" method="POST" action="{{action('ExcelController@importBlackList')}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-xs-6">
        <div class="title">
          <h3>Black List</h3>
        </div>
        <div class="card card-nav">
          <div class="content">
            <label for="scafile">Situacion de cartera</label>
            <input class="btn btn-simple" type="file" name="blklst" accept=".csv" value="">
            <button class="btn btn-primary btn-sm" type="submit"><i class="material-icons">save</i>  GUARDAR EN BD</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form class="form-horizontal" method="POST" action="{{action('ExcelController@importInfoCred')}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-xs-6">
        <div class="title">
          <h3>Informaci&oacute;n Crediticia</h3>
        </div>
        <div class="card card-nav">
          <div class="content">
            <label for="rptliqc">Reporte Liquidados y Cerrados</label>
            <input class="btn btn-simple" type="file" name="infc" accept=".csv" value="">
            <button class="btn btn-primary btn-sm" type="submit"><i class="material-icons">save</i>  GUARDAR EN BD</button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
