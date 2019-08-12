{!!Form::open(array('url'=>'agenda/renovacion/','method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}

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
    <div class="col-sm-10">
      <div class="form-group label-floating">
          <label class="control-label">Nombre...</label>
          <input class="form-control" type="text" name="cliente" id="cliente">
      </div>
    </div>
    <div class="col-sm-2">
      <button class="btn btn-info btn-block" type="submit">Buscar</button>
    </div>
{{Form::close()}}
