<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
    data-accordion="false">


    @can('manejar decomisos')
    <li class="nav-item {{Route::is('decomiso.index','decomiso.create','decomiso.edit')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-boxes"></i>
        <p>
          Decomisos
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        @canany(['ver decomisos','crear decomiso'])
        <li class="nav-item {{Route::is('decomiso.index','decomiso.create','decomiso.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-box"></i>
            <p>
              Decomisos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver decomisos')
            <li class="nav-item">
              <a href="{{route('decomiso.index')}}" class="nav-link {{Route::is('decomiso.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver decomisos</p>
              </a>
            </li>
            @endcan
            @can('crear decomiso')
            <li class="nav-item">
              <a href="{{route('decomiso.create')}}" class="nav-link {{Route::is('decomiso.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear decomiso</p>
              </a>
            </li>
            @endcan
            @can('importar data')
            <li class="nav-item">
              <a href="{{route('decomiso.decomiso_importar_ver')}}" class="nav-link {{Route::is('decomiso.decomiso_importar_ver')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Importar data</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>
    </li>
    @endcan

    @can('ver informacion')
    <li class="nav-item {{Route::is('mostrarGraficassBitacora','decomisodetenido.index','decomisotransporte.index','decomisomunicion.index','decomisoarma.index','decomisoprecursor.index','decomisodroga.index','mapa.mostrar_mapa','mapa.generar_droga','mapa.generar_precursor','mapa.generar_arma','mapa.generar_municion','mapa.generar_detenido','mapa.generar_transporte','mostrar','generar_droga','generar_precursor','generar_arma','generar_municion','generar_detenido','generar_transporte')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-chart-bar"></i>
        <p>
          Consultar información
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        @can('crear graficas')
        <li class="nav-item {{Route::is('mostrarGraficassBitacora','mostrar','ver grafica de bitacora','generar_droga','generar_precursor','generar_arma','generar_municion','generar_detenido','generar_transporte')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-user-friends"></i>
            <p>
              Gráficas
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            {{--  @can('ver usuarios')
            <li class="nav-item">
              <a href="{{route('user.index')}}" class="nav-link {{Route::is('user.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver graficas</p>
              </a>
            </li>
            @endcan  --}}
            {{--se copiaron rutas de graficas de bitacora que estaban en la parte administrativa para poder ser usadas por no administradores 03/07/2023--}}
            <li class="nav-item">
              <a href="{{route('mostrar')}}" class="nav-link {{Route::is('mostrar')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear gráfica</p>
              </a>
            </li>
            @can('ver grafica de bitacora')
            <li class="nav-item">
              <a href="{{route('mostrarGraficassBitacora')}}" class="nav-link {{Route::is('mostrarGraficassBitacora')?'active':''}}">
                <i class="far fa-chart-bar"></i>
                <p>Graficas de bitacora</p>
              </a>
            </li>
            @endcan

          </ul>
        </li>
        @endcan
      </ul>

      <ul class="nav nav-treeview">
        @can('crear mapas')
        <li class="nav-item {{Route::is('mapa.mostrar_mapa','mapa.generar_droga','mapa.generar_precursor','mapa.generar_arma','mapa.generar_municion','mapa.generar_detenido','mapa.generar_transporte')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-map-marked"></i>
            <p>
              Mapas
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            {{--  @can('ver roles')
            <li class="nav-item">
              <a href="{{route('role.index')}}" class="nav-link {{Route::is('role.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver mapas</p>
              </a>
            </li>
            @endcan  --}}

            <li class="nav-item">
              <a href="{{route('mapa.mostrar_mapa')}}" class="nav-link {{Route::is('mapa.mostrar_mapa','mapa.generar_droga','mapa.generar_precursor','mapa.generar_arma','mapa.generar_municion','mapa.generar_detenido','mapa.generar_transporte')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear mapa</p>
              </a>
            </li>

          </ul>
        </li>
        @endcan
      </ul>

      <ul class="nav nav-treeview">
        @can('crear informes')
        <li class="nav-item {{Route::is('decomisodetenido.index','decomisotransporte.index','decomisomunicion.index','decomisoarma.index','decomisodroga.index','decomisoprecursor.index')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-file-alt"></i>
            <p>
              Informes
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver decomisos de droga')
            <li class="nav-item">
              <a href="{{route('decomisodroga.index')}}" class="nav-link {{Route::is('decomisodroga.index')?'active':''}}">
                <i class="fas fa-pills"></i>
                <p>Drogas</p>
              </a>
            </li>
            @endcan

            @can('ver decomisos de precursores')
            <li class="nav-item">
              <a href="{{route('decomisoprecursor.index')}}" class="nav-link {{Route::is('decomisoprecursor.index')?'active':''}}">
                <i class="fas fa-flask"></i>
                <p>Precursores químicos</p>
              </a>
            </li>
            @endcan

            @can('ver decomisos de armas')
            <li class="nav-item">
              <a href="{{route('decomisoarma.index')}}" class="nav-link {{Route::is('decomisoarma.index')?'active':''}}">
                <i class="fas fa-crosshairs"></i>
                <p>Armas</p>
              </a>
            </li>
            @endcan

            @can('ver decomisos de municiones')
            <li class="nav-item">
              <a href="{{route('decomisomunicion.index')}}" class="nav-link {{Route::is('decomisomunicion.index')?'active':''}}">
                <i class="fas fa-parachute-box"></i>
                <p>Municiones</p>
              </a>
            </li>
            @endcan

            @can('ver decomisos de transportes')
            <li class="nav-item">
              <a href="{{route('decomisotransporte.index')}}" class="nav-link {{Route::is('decomisotransporte.index')?'active':''}}">
                <i class="fas fa-truck"></i>
                <p>Transportes</p>
              </a>
            </li>
            @endcan

            @can('ver detenidos en decomisos')
            <li class="nav-item">
              <a href="{{route('decomisodetenido.index')}}" class="nav-link {{Route::is('decomisodetenido.index')?'active':''}}">
                <i class="fas fa-user-ninja"></i>
                <p>Detenidos</p>
              </a>
            </li>
            @endcan



          </ul>
        </li>
        @endcan
      </ul>


    </li>
    @endcan


    <li class="nav-item">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fas fa-book"></i>
        <p>
          Manual
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        {{--  @canany(['ver instituciones','crear institucion'])  --}}
        <li class="nav-item">
          <a href="/Manual_de_usuario_plataforma_Captis_versión_patente.pdf" target="_blank" class="nav-link">
            <i class="fas fa-book-open"></i>
            <p>
              Manual de usuario
              {{--  <i class="right fas fa-angle-left"></i>  --}}
            </p>
          </a>
          {{--  <ul class="nav nav-treeview">
            @can('ver instituciones')
            <li class="nav-item">
              <a href="/Fundamentos de TI.pdf" class="nav-link" target="_blank">
                <i class="fas fa-book"></i>
                <p>Ver</p>
              </a>
            </li>
            @endcan
          </ul>  --}}
        {{--  </li>
        @endcanany  --}}
      </ul>
    </i>

    @can('ver parametros')
    <li class="nav-header">PARAMETROS</li>
    <li class="nav-item {{Route::is('tipodroga.index','tipodroga.create','tipodroga.edit','presentaciondroga.index','presentaciondroga.create','presentaciondroga.edit','precursor.index','precursor.create','precursor.edit','presentacionprecursor.index','presentacionprecursor.create','presentacionprecursor.edit','tipoParametro.index','tipoParametro.create','tipoParametro.edit','parametro.index','parametro.create','parametro.edit','droga.index','droga.create','droga.edit')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-cogs"></i>
        <p>
          Parámetros de droga
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>

      <ul class="nav nav-treeview">
        @canany(['ver drogas','crear droga'])
        <li class="nav-item {{Route::is('droga.index','droga.create','droga.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-pills"></i>
            <p>
              Droga
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver drogas')
            <li class="nav-item">
              <a href="{{route('droga.index')}}" class="nav-link {{Route::is('droga.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver drogas</p>
              </a>
            </li>
            @endcan
            @can('crear droga')
            <li class="nav-item">
              <a href="{{route('droga.create')}}" class="nav-link {{Route::is('droga.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear droga</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver tipo de droga','crear Tipo de droga'])
        <li class="nav-item {{Route::is('tipodroga.index','tipodroga.create','tipodroga.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-syringe"></i>
            <p>
              Tipo de droga
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver tipo de droga')
            <li class="nav-item">
              <a href="{{route('tipodroga.index')}}" class="nav-link {{Route::is('tipodroga.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver tipos de droga</p>
              </a>
            </li>
            @endcan
            @can('crear tipo de droga')
            <li class="nav-item">
              <a href="{{route('tipodroga.create')}}" class="nav-link {{setActiveRoute('admin/tipodroga/create')}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear tipo de droga</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver presentaciones de droga','crear presentaciones de droga'])
        <li class="nav-item {{Route::is('presentaciondroga.index','presentaciondroga.create','presentaciondroga.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-cannabis"></i>
            <p>
              Presentaciones droga
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver presentaciones de droga')
            <li class="nav-item">
              <a href="{{route('presentaciondroga.index')}}"
                class="nav-link {{Route::is('presentaciondroga.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver presentaciones</p>
              </a>
            </li>
            @endcan
            @can('crear presentaciones de droga')
            <li class="nav-item">
              <a href="{{route('presentaciondroga.create')}}"
                class="nav-link {{Route::is('presentaciondroga.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear presentacion</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver presentaciones de precursor','crear presentaciones de precursor'])
        <li class="nav-item {{Route::is('presentacionprecursor.index','presentacionprecursor.create','presentacionprecursor.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-prescription-bottle"></i>
            <p>
              Presentaciones precursores
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver presentaciones de precursor')
            <li class="nav-item">
              <a href="{{route('presentacionprecursor.index')}}"
                class="nav-link {{Route::is('presentacionprecursor.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver presentaciones</p>
              </a>
            </li>
            @endcan
            @can('crear presentaciones de precursor')
            <li class="nav-item">
              <a href="{{route('presentacionprecursor.create')}}"
                class="nav-link {{Route::is('presentacionprecursor.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear presentacion</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver precursor','crear precursor'])
        <li class="nav-item {{Route::is('precursor.index','precursor.create','precursor.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-flask"></i>
            <p>
              Precursores químicos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver precursor')
            <li class="nav-item">
              <a href="{{route('precursor.index')}}" class="nav-link {{Route::is('precursor.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver precursores</p>
              </a>
            </li>
            @endcan
            @can('crear precursor')
            <li class="nav-item">
              <a href="{{route('precursor.create')}}" class="nav-link {{Route::is('precursor.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear precursor</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>








      {{--  <ul class="nav nav-treeview">
        @canany(['ver permisos','crear permisos'])
        <li
          class="nav-item {{Route::is('tipoParametro.index','tipoParametro.create','tipoParametro.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-university"></i>
            <p>
              Tipo de Parametros
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver permisos')
            <li class="nav-item">
              <a href="{{route('tipoParametro.index')}}"
                class="nav-link {{Route::is('tipoParametro.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver tipos de parametros</p>
              </a>
            </li>
            @endcan
            @can('crear permisos')
            <li class="nav-item">
              <a href="{{route('tipoParametro.create')}}"
                class="nav-link {{Route::is('tipoParametro.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear tipo parametro</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>  --}}

      {{--  <ul class="nav nav-treeview">
        @canany(['ver permisos','crear permisos'])
        <li class="nav-item {{Route::is('parametro.index','parametro.create','parametro.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-university"></i>
            <p>
              Parametros
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver permisos')
            <li class="nav-item">
              <a href="{{route('parametro.index')}}" class="nav-link {{Route::is('parametro.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver parametros</p>
              </a>
            </li>
            @endcan
            @can('crear permisos')
            <li class="nav-item">
              <a href="{{route('parametro.create')}}" class="nav-link {{Route::is('parametro.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear parametro</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>  --}}



      {{--  <ul class="nav nav-treeview">
        @canany(['ver permisos','crear permisos'])
        <li class="nav-item {{Route::is('decomiso.index','decomiso.create','decomiso.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-university"></i>
            <p>
              Decomisos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver permisos')
            <li class="nav-item">
              <a href="{{route('decomiso.index')}}" class="nav-link {{Route::is('decomiso.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver decomisos</p>
              </a>
            </li>
            @endcan
            @can('crear permisos')
            <li class="nav-item">
              <a href="{{route('decomiso.create')}}" class="nav-link {{Route::is('decomiso.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear decomiso</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>  --}}


    </li>

    <li class="nav-item {{Route::is('arma.index','arma.create','arma.edit','tipomunicion.index','tipomunicion.create','tipomunicion.edit')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-cogs"></i>
        <p>
          Parámetros de armas
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        @canany(['ver armas','crear arma'])
        <li class="nav-item {{Route::is('arma.index','arma.create','arma.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-crosshairs"></i>
            <p>
              Armas
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver armas')
            <li class="nav-item">
              <a href="{{route('arma.index')}}" class="nav-link {{Route::is('arma.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver armas</p>
              </a>
            </li>
            @endcan
            @can('crear arma')
            <li class="nav-item">
              <a href="{{route('arma.create')}}" class="nav-link {{Route::is('arma.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear arma</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver municiones','crear municion'])
        <li
          class="nav-item {{Route::is('tipomunicion.index','tipomunicion.create','tipomunicion.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-parachute-box"></i>
            <p>
              Municiones
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver municiones')
            <li class="nav-item">
              <a href="{{route('tipomunicion.index')}}"
                class="nav-link {{Route::is('tipomunicion.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver municiones</p>
              </a>
            </li>
            @endcan
            @can('crear municion')
            <li class="nav-item">
              <a href="{{route('tipomunicion.create')}}"
                class="nav-link {{Route::is('tipomunicion.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear munición</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

    </i>

    <li class="nav-item {{Route::is('identificacion.index','identificacion.create','identificacion.edit','ocupacion.index','ocupacion.create','ocupacion.edit','estructura.index','estructura.create','estructura.edit','estado.index','estado.create','estado.edit')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-cogs"></i>
        <p>
          Parámetros de detenido
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>

      <ul class="nav nav-treeview">
        @canany(['ver estados civiles','crear estados civiles'])
        <li class="nav-item {{Route::is('estado.index','estado.create','estado.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-id-card"></i>
            <p>
              Estados civiles
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver estados civiles')
            <li class="nav-item">
              <a href="{{route('estado.index')}}" class="nav-link {{Route::is('estado.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver estados</p>
              </a>
            </li>
            @endcan
            @can('crear estados civiles')
            <li class="nav-item">
              <a href="{{route('estado.create')}}" class="nav-link {{Route::is('estado.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear estado</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver identificaciones','crear identificacion'])
        <li
          class="nav-item {{Route::is('identificacion.index','identificacion.create','identificacion.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="far fa-id-badge"></i>
            <p>
              Identificaciones
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver identificaciones')
            <li class="nav-item">
              <a href="{{route('identificacion.index')}}"
                class="nav-link {{Route::is('identificacion.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver identificaciones</p>
              </a>
            </li>
            @endcan
            @can('crear identificacion')
            <li class="nav-item">
              <a href="{{route('identificacion.create')}}"
                class="nav-link {{Route::is('identificacion.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear identificación</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver ocupaciones','crear ocupacion'])
        <li class="nav-item {{Route::is('ocupacion.index','ocupacion.create','ocupacion.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-hard-hat"></i>
            <p>
              Ocupaciones
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver ocupaciones')
            <li class="nav-item">
              <a href="{{route('ocupacion.index')}}" class="nav-link {{Route::is('ocupacion.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver ocupaciones</p>
              </a>
            </li>
            @endcan
            @can('crear ocupacion')
            <li class="nav-item">
              <a href="{{route('ocupacion.create')}}" class="nav-link {{Route::is('ocupacion.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear ocupación</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver estructuras','crear estructura'])
        <li class="nav-item {{Route::is('estructura.index','estructura.create','estructura.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-user-ninja"></i>
            <p>
              Estructuras criminales
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver estructuras')
            <li class="nav-item">
              <a href="{{route('estructura.index')}}" class="nav-link {{Route::is('estructura.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver estructura</p>
              </a>
            </li>
            @endcan
            @can('crear estructura')
            <li class="nav-item">
              <a href="{{route('estructura.create')}}" class="nav-link {{Route::is('estructura.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear estructura</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>
    </i>

    <li class="nav-item {{Route::is('institucion.index','institucion.create','institucion.edit')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-cogs"></i>
        <p>
          Otros parámetros
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        @canany(['ver instituciones','crear institucion'])
        <li class="nav-item {{Route::is('institucion.index','institucion.create','institucion.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-university"></i>
            <p>
              Instituciones
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver instituciones')
            <li class="nav-item">
              <a href="{{route('institucion.index')}}" class="nav-link {{Route::is('institucion.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver instituciones</p>
              </a>
            </li>
            @endcan
            @can('crear institucion')
            <li class="nav-item">
              <a href="{{route('institucion.create')}}"
                class="nav-link {{Route::is('institucion.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear institución</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>
    </i>
    @endcan

    @can('ver seguridad')
    <li class="nav-item {{Route::is('user.index','mostrarGraficasBitacora','user.create','user.edit','role.index','role.create','role.edit','permission.index','permission.create','permission.edit','bitacora','respaldo.generar','api.TokensPersonales','api.ClientesApi','api.ClientesAutorizadosApi')?'menu-open':''}}">
      <a href="#" class="nav-link bg-gradient-dark">
        <i class="fas fa-lock"></i>
        <p>
          Seguridad
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>

      <ul class="nav nav-treeview">
        @canany(['ver usuarios','crear usuarios'])
        <li class="nav-item {{Route::is('user.index','user.create','user.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-user-friends"></i>
            <p>
              Usuarios
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver usuarios')
            <li class="nav-item">
              <a href="{{route('user.index')}}" class="nav-link {{Route::is('user.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver usuarios</p>
              </a>
            </li>
            @endcan
            @can('crear usuarios')
            <li class="nav-item">
              <a href="{{route('user.create')}}" class="nav-link {{setActiveRoute('admin/user/create')}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear usuarios</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver roles','crear roles'])
        <li class="nav-item {{Route::is('role.index','role.create','role.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-address-book"></i>
            <p>
              Roles
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver roles')
            <li class="nav-item">
              <a href="{{route('role.index')}}" class="nav-link {{Route::is('role.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver roles</p>
              </a>
            </li>
            @endcan
            @can('crear roles')
            <li class="nav-item">
              <a href="{{route('role.create')}}" class="nav-link {{Route::is('role.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear rol</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver permisos','crear permisos'])
        <li class="nav-item {{Route::is('permission.index','permission.create','permission.edit')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-fingerprint"></i>
            <p>
              Permisos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver permisos')
            <li class="nav-item">
              <a href="{{route('permission.index')}}" class="nav-link {{Route::is('permission.index')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver permisos</p>
              </a>
            </li>
            @endcan
            @can('crear permisos')
            <li class="nav-item">
              <a href="{{route('permission.create')}}" class="nav-link {{Route::is('permission.create')?'active':''}}">
                <i class="fas fa-plus-circle"></i>
                <p>Crear permiso</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
      </ul>

      <ul class="nav nav-treeview">
      @canany(['ver grafica de bitacora','ver detalles de bitacora'])
        <li class="nav-item {{Route::is('bitacora','mostrarGraficasBitacora')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-business-time"></i>
              <p>
                Bitácora
                <i class="right fas fa-angle-left"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
            @can('ver detalles de bitacora')
            <li class="nav-item">
              <a href="{{route('bitacora')}}" class="nav-link {{Route::is('bitacora')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Ver bitácora</p>
              </a>
            </li>
            @endcan












          </ul>
        </li>
      @endcanany
      </ul>

      <ul class="nav nav-treeview">
        @canany(['ver gestion de api'])
          <li class="nav-item {{Route::is('api.TokensPersonales','api.ClientesApi','api.ClientesAutorizadosApi')?'menu-open':''}}">
            <a href="#" class="nav-link bg-gradient-primary">
              <i class="fas fa-cloud-download-alt"></i>
                <p>
                  Api
                  <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              @can('ver gestion de api')
              <li class="nav-item">
                <a href="{{route('api.TokensPersonales')}}" class="nav-link {{Route::is('api.TokensPersonales')?'active':''}}">
                  <i class="fas fa-key"></i>
                  <p>Tokens personales</p>
                </a>
              </li>
              @endcan
              @can('ver gestion de api')
              <li class="nav-item">
                <a href="{{route('api.ClientesApi')}}" class="nav-link {{Route::is('api.ClientesApi')?'active':''}}">
                  <i class="fas fa-user-alt"></i>
                  <p>Clientes</p>
                </a>
              </li>
              @endcan
              @can('ver gestion de api')
              <li class="nav-item">
                <a href="{{route('api.ClientesAutorizadosApi')}}" class="nav-link {{Route::is('api.ClientesAutorizadosApi')?'active':''}}">
                  <i class="fas fa-user-check"></i>
                  <p>Clientes autorizados</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
        @endcanany
        </ul>



      {{--  <ul class="nav nav-treeview">
      @can('ver respaldo')
        <li class="nav-item {{Route::is('respaldo.generar')?'menu-open':''}}">
          <a href="#" class="nav-link bg-gradient-primary">
            <i class="fas fa-business-time"></i>
            <p>
              Respaldo
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('respaldo.generar')}}" class="nav-link {{Route::is('respaldo.generar')?'active':''}}">
                <i class="far fa-eye"></i>
                <p>Crear copia de base de datos</p>
              </a>
            </li>
          </ul>
        </li>
      @endcan
      </ul>  --}}

    </li>
    @endcan

  </ul>
</nav>
