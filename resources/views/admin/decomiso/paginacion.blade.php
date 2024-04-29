

    <!-- /.card-header -->
    <div class="card-body">
        @include('admin.partials.fechas')
        <table id="crud-table" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead>
                <tr>

                    <th width="50%">fecha</th>
                    <th>observacion</th>
                    <th>dirección</th>
                    <th>departamento</th>
                    <th>municipio</th>
                    <th>creador</th>
                    <th>actualizador</th>
                    <th>institucion</th>

                    <th width="8%">Acciones</th>
                </tr>
                <tr>

                    <td width="8%">
                        <input type="text"  class="form-control filter-input" data-column="0" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="1" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="2" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="3" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="4" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="5" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="6" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="7" />
                    </td>

                    <th width="8%"></th>
                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>

        {{--  {{ $decomisos->links() }}
        {{"Mostrando " . $decomisos->count() . " de " . $decomisos->total() . " registros"}}  --}}
    </div>
    <!-- /.card-body -->





