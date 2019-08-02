<div class="modal fade" aria-hidden="true" role="dialog" tabindex="-1" id="modal-del-{{$prospecto->folio}}">
  {{Form::Open(array('action'=>array('ProspectobcController@destroy',$prospecto->folio),'method'=>'delete'))}}
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          &times;
        </button>
        <h4 class="modal-title">Precaución</h4>
      </div>
      <div class="modal-body">
        <p>¿Deseas eliminar el prospecto?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
  {{Form::Close()}}
</div>
