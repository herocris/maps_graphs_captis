<div class="card card-primary" id="grap_op">
    <div class="card-header">
        -
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>
        {{csrf_field()}}
        <div class="card-body">
            <div class="form-group row">
                <label for="fecha_ini" class="col-sm-4 col-form-label">Fecha inicio</label>
                <div class="col-sm-8">
                    <input type="text" required  autocomplete="off" name="fecha_ini" class="form-control" id="fecha_ini"
                        data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
                    {{--  <span style="color:red">
                            <strong id="fec_valid" style="display:none;">"La fecha inicial es requerida"</strong>
                    </span>  --}}
                </div>
            </div>
            <div class="form-group row">
                <label for="fecha_fin" class="col-sm-4 col-form-label">Fecha final</label>
                <div class="col-sm-8">
                    <input type="text" required  autocomplete="off" name="fecha_fin" class="form-control" id="fecha_fin"
                        value="" data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
                    {{--  <span style="color:red">
                            <strong id="fec2_valid" style="display:none;">"La fecha final es requerida"</strong>
                    </span>  --}}
                </div>
            </div>
            
                {{--  <button type="button" class="btn btn-outline-primary" id="departamentos" data-toggle="tooltip" data-placement="top" title="Mapa por departamanetos"><i class="fas fa-globe-americas"></i></button>
                <button type="button" class="btn btn-outline-primary" id="ubicaciones" data-toggle="tooltip" data-placement="top" title="Mapa por ubicaciones"><i class="fas fa-map-marker-alt"></i></button>
                <button type="button" class="btn btn-outline-primary" id="calor" data-toggle="tooltip" data-placement="top" title="Mapa de calor"><i class="fas fa-fire"></i></button>            --}}
                @include('admin.partials.tipo_mapas')
                @include('admin.partials.opciones_parametro2')
                
                <div class="card">
                    <div class="card-body">
                    @include('admin.mapa.droga')
                    @include('admin.mapa.precursor')
                    @include('admin.mapa.arma')
                    @include('admin.mapa.municion')
                    @include('admin.mapa.detenido')
                    @include('admin.mapa.transporte')
                    </div>
                </div>            
                <div>
                    <label for="parametro" class="col-sm-5 col-form-label">Parametro</label>
                    <input type="checkbox" id="cantpor" unchecked data-toggle="toggle" data-size="sm"
                        data-on='<i class="fas fa-percentage" data-toggle="tooltip" data-placement="right" title="Porcentaje"></i>'
                        data-off='<i class="fas fa-sort-amount-up-alt" data-toggle="tooltip" data-placement="right" title="Cantidad"></i>'
                        data-onstyle="primary" data-offstyle="info">
                </div> 
            
        </div>
                        
        <!-- /.card-body -->
        
        <!-- /.card-footer -->
        <div class="card-footer d-flex">
            <button type="button" class="btn btn-primary ml-auto" id="guardado">Generar</button>
            
        </div>
</div>