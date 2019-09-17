<!-- Modal -->
<div class="modal fade" data-backdrop="" id="calificarDatos" role="dialog" tabindex="-1" aria-labelledby="calificarDatos" aria-hidden="true">
    <div class="modal-dialog" role="document">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Solo podra Calificar esta Oferta 1 vez
            <br>
            <strong>¿Esta Seguro de Calificarla Ahora?</strong>
          </h4>
        </div>
        <div class="modal-body">
            <div class="row">
              <input type="hidden" id="idCalificar">
              <div class="col-sm-6">
                <button type="button" class="btn btn-success btn-block" id="aceptar" onclick="verificarCalificacion();">Calificar</button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-default btn-block" id="cancelar" data-dismiss="modal">Close</button>
              </div>
            </div>
        </div>
      </div>
      
    </div>
  </div>