<!-- Modal -->
<div class="modal fade" data-backdrop="" id="ofertaAceptada" role="dialog" tabindex="-1" aria-labelledby="ofertaAceptada" aria-hidden="true">
    <div class="modal-dialog" role="document">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Solo podra seleccionar una unica Oferta
            <br>
            <strong>¿Esta seguro de selecionar esta? Solo podra Calificar esta Oferta 1 vez</strong>
          </h4>
        </div>
        <div class="modal-body">
            <div class="row">
              <input type="hidden" id="idOfertaAceptada">
              <div class="col-sm-6">
                <button type="button" class="btn btn-success btn-block" id="aceptarOferta" onclick="ofertaAceptada();">Aceptar</button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-default btn-block" id="cancelarOferta" data-dismiss="modal">Rechazar</button>
              </div>
            </div>
        </div>
      </div>
      
    </div>
  </div>