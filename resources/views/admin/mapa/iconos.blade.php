<div class="modal fade" id="iconModal1" tabindex="-1" aria-labelledby="iconModal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar icono</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{route('decomisoarma.store')}}" id="form_create_arma">
                <div class="modal-body">
                    <select id="selepur" class="image-picker show-html">
                        <option data-img-src="/mis_iconos/marihuana-01-01.png" data-img-class="first" value="/mis_iconos/marihuana-01-01.png">  Page 1  </option>
                        <option data-img-src="/mis_iconos/pastilla-01.png" data-img-alt="Page 2" value="/mis_iconos/pastilla-01.png">  Page 2  </option>
                        <option data-img-src="/mis_iconos/puro-01.png" data-img-alt="Page 3" value="/mis_iconos/puro-01.png">  Page 3  </option>
                        <option data-img-src="/mis_iconos/arma-01.png" data-img-alt="Page 3" value="/mis_iconos/arma-01.png">  Page 3  </option>
                        <option data-img-src="/mis_iconos/jeringa-01.png" data-img-alt="Page 3" value="/mis_iconos/jeringa-01.png">  Page 3  </option>
                        <option data-img-src="/mis_iconos/carro-01.png" data-img-alt="Page 3" value="/mis_iconos/carro-01.png">  Page 3  </option>
                        <option data-img-src="/mis_iconos/ladron-01.png" data-img-alt="Page 3" value="/mis_iconos/ladron-01.png">  Page 3  </option>
                        <option data-img-src="/mis_iconos/quimico-01.png" data-img-alt="Page 3" value="/mis_iconos/quimico-01.png">  Page 3  </option>
                        <option data-img-src="/mis_iconos/municion-01.png" data-img-alt="Page 3" value="/mis_iconos/municion-01.png">  Page 3  </option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>