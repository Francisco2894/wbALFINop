{!!Form::open(array('url'=>'agenda/devengo/','method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}

    @if (Auth::user()->idNivel>4)
      <input id="searchTxt" name="searchTxt" type="hidden" value={{Auth::user()->idPerfil}}>
    @elseif (Auth::user()->idNivel===4)
      <div class="form-group col-md-6">
       <label for="searchTxt">ASESOR</label>
       {!! Form::select('searchTxt',$vendedores,$searchTxt,['class'=>'form-control', 'onchange'=>"this.form.submit()",'id'=>'searchTxt']) !!}
     </div>
       @else
         <div class="form-group col-md-4">
           <label for="searchTxts">SUCURSAL</label>
           {{Form::select('searchTxts',$sucursales,$searchTxts,['class'=>'form-control','id'=>'searchTxts'])}}
         </div>
         <div class="form-group col-md-4">
          <label for="searchTxt">ASESOR</label>
          {!! Form::select('searchTxt',$vendedores,$searchTxt,['class'=>'form-control', 'onchange'=>"this.form.submit()",'id'=>'searchTxt']) !!}
        </div>
    @endif
      <div class="form-group col-md-2">
      <button type="button" class="btn btn-primary btnagendar" data-url="{{ url('agenda/agendadiaria') }}">Agendar <i class="material-icons">assignment</i></button>
      </div>
       <div class="form-group col-md-2">
      <a href="{{URL::action('PdfController@getPdfagnd',['searchTxt'=> $searchTxt])}}" > <button type="button" class="btn btn-primary">PDF <i class="material-icons">file_download</i></button></a>
    </div>
{{Form::close()}}
