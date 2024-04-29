<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Arma
 *
 * @property int $id
 * @property string $descripcion Descripción del arma
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha del último borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Arma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Arma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Arma onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Arma query()
 * @method static \Illuminate\Database\Eloquent\Builder|Arma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arma whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arma whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arma whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arma withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Arma withoutTrashed()
 */
	class Arma extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Decomiso
 *
 * @property int $id
 * @property string $fecha Fecha del decomiso
 * @property string $observacion Descripción del decomiso
 * @property string $direccion Dirección del decomiso
 * @property int $municipio_id Municipio donde se hizo el decomiso
 * @property int $institucion_id Institución que coordino el decomiso
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property float $latitud Latitud del decomiso
 * @property float $longitud Longitud del decomiso
 * @property int $user_create
 * @property int $user_update
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Arma> $armas
 * @property-read int|null $armas_count
 * @property-read \App\Models\Departamento|null $departamentos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_detenido> $detenidos
 * @property-read int|null $detenidos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Droga> $drogas
 * @property-read int|null $drogas_count
 * @property-read \App\Models\Institucion $institucion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Institucion> $instituciones
 * @property-read int|null $instituciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TipoMunicion> $municiones
 * @property-read int|null $municiones_count
 * @property-read \App\Models\Municipio $municipio
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Precursor> $precursores
 * @property-read int|null $precursores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_transporte> $transportes
 * @property-read int|null $transportes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso query()
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereInstitucionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereUserCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso whereUserUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Decomiso withoutTrashed()
 */
	class Decomiso extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DecomisoPresentacion
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DecomisoPresentacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DecomisoPresentacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DecomisoPresentacion query()
 */
	class DecomisoPresentacion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Departamento
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $pais_id
 * @property float $latitud
 * @property float $longitud
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Municipio> $municipios
 * @property-read int|null $municipios_count
 * @property-read \App\Models\Pais|null $pais
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento wherePaisId($value)
 */
	class Departamento extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Droga
 *
 * @property int $id
 * @property string $descripcion Drescripción del arma
 * @property int $tipo_droga_id Tipo de droga
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @property-read \App\Models\TipoDroga $tipo_droga
 * @method static \Illuminate\Database\Eloquent\Builder|Droga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Droga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Droga onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Droga query()
 * @method static \Illuminate\Database\Eloquent\Builder|Droga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Droga whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Droga whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Droga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Droga whereTipoDrogaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Droga whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Droga withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Droga withoutTrashed()
 */
	class Droga extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EstadoCivil
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_detenido> $detenidos
 * @property-read int|null $detenidos_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil withoutTrashed()
 */
	class EstadoCivil extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EstructuraCriminal
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_detenido> $detenidos
 * @property-read int|null $detenidos_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|EstructuraCriminal withoutTrashed()
 */
	class EstructuraCriminal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Grafica
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Grafica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grafica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grafica query()
 */
	class Grafica extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Identificacion
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_detenido> $detenidos
 * @property-read int|null $detenidos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Identificacion withoutTrashed()
 */
	class Identificacion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Institucion
 *
 * @property int $id
 * @property string $nombre
 * @property string $contacto
 * @property string $telefono
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos_
 * @property-read int|null $decomisos__count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion withoutTrashed()
 */
	class Institucion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Municipio
 *
 * @property int $id
 * @property string $nombre
 * @property int $departamento_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @property-read \App\Models\Departamento $departamento
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio query()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereNombre($value)
 */
	class Municipio extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ocupacion
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_detenido> $detenidos
 * @property-read int|null $detenidos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ocupacion withoutTrashed()
 */
	class Ocupacion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Pais
 *
 * @property int $id
 * @property string $nombre
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Departamento> $departamentos
 * @property-read int|null $departamentos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_detenido> $detenidos
 * @property-read int|null $detenidos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\decomiso_transporte> $transportes
 * @property-read int|null $transportes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pais newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pais newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pais query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pais whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pais whereNombre($value)
 */
	class Pais extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Parametro
 *
 * @property int $id
 * @property string $descripcion
 * @property int $tipo_parametro_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Tipo_parametro $tipo_parametro
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro query()
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereTipoParametroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parametro whereUpdatedAt($value)
 */
	class Parametro extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Precursor
 *
 * @property int $id
 * @property string $descripcion Descripción del precursor químico
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Precursor withoutTrashed()
 */
	class Precursor extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Presentacion
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentacion whereUpdatedAt($value)
 */
	class Presentacion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TipoDroga
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Droga> $drogas
 * @property-read int|null $drogas_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoDroga withoutTrashed()
 */
	class TipoDroga extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TipoMunicion
 *
 * @property int $id
 * @property string $descripcion Nombre de munición
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoMunicion withoutTrashed()
 */
	class TipoMunicion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tipo_parametro
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Parametro> $parametros
 * @property-read int|null $parametros_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tipo_parametro whereUpdatedAt($value)
 */
	class Tipo_parametro extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $oscuro
 * @property int|null $institucion_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Institucion|null $institucion
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInstitucionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOscuro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\decomiso_arma
 *
 * @property int $id
 * @property int $cantidad Cantidad del arma decomisada
 * @property int $decomiso_id Decomiso donde se incauto el arma
 * @property int $arma_id Nombre del arma decomisada
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma query()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereArmaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereDecomisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_arma withoutTrashed()
 */
	class decomiso_arma extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\decomiso_detenido
 *
 * @property int $id
 * @property string|null $nombre Nombre del detenido
 * @property string|null $alias Alias del detenido
 * @property string|null $identidad Numero de identificación del detenido
 * @property int|null $edad Edad del detenido
 * @property int $decomiso_id Decomiso donde se detuvo el detenido
 * @property int $identificacion_id Tipo de identificación del detenido
 * @property int|null $estructura_id Estructura criminal a la cual pertenese el detenido
 * @property int|null $ocupacion_id Ocupación del detenido
 * @property int|null $estado_civil_id Estado civil del detenido
 * @property int|null $pais_id Nacionalidad del detenido
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property string $genero Genero por nacimiento del detenido
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Decomiso $decomiso
 * @property-read \App\Models\EstadoCivil|null $estado_civil
 * @property-read \App\Models\EstructuraCriminal|null $estructura
 * @property-read \App\Models\Identificacion $identificacion
 * @property-read \App\Models\Ocupacion|null $ocupacion
 * @property-read \App\Models\Pais|null $pais
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido query()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereDecomisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereEdad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereEstadoCivilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereEstructuraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereIdentidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereIdentificacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereOcupacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido wherePaisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_detenido withoutTrashed()
 */
	class decomiso_detenido extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\decomiso_droga
 *
 * @property int $id
 * @property int $cantidad Cantidad de la droga decomisada
 * @property float $peso Peso total de la droga decomisada
 * @property int $decomiso_id Decomiso donde se decomiso la droga
 * @property int $droga_id Nombre de la droga decomisada
 * @property int $presentacion_droga_id Presentación de la droga decomisada
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga query()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereDecomisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereDrogaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga wherePresentacionDrogaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_droga withoutTrashed()
 */
	class decomiso_droga extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\decomiso_municion
 *
 * @property int $id
 * @property int $cantidad Cantidad de munición decomisada
 * @property int $decomiso_id Decomiso donde se incauto la munición
 * @property int $tipo_municion_id Nombre de munición
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion query()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereDecomisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereTipoMunicionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_municion withoutTrashed()
 */
	class decomiso_municion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\decomiso_precursor
 *
 * @property int $id
 * @property int $cantidad Cantidad del precursor decomisado
 * @property float $volumen Volumen en litros del precursor decomisado
 * @property int $decomiso_id Decomiso en el que se incauto el precursor químico
 * @property int $precursor_id Nombre del precursor químico
 * @property int $presentacion_precursor_id Nombre de presentación del precursor
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor query()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereDecomisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor wherePrecursorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor wherePresentacionPrecursorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor whereVolumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_precursor withoutTrashed()
 */
	class decomiso_precursor extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\decomiso_transporte
 *
 * @property int $id
 * @property string $tipo Tipo de transporte
 * @property string|null $marca Marca del transporte
 * @property string|null $modelo Modelo del transporte
 * @property string $color Color del transporte
 * @property string|null $placa Placa del transporte
 * @property int $decomiso_id Decomiso donde se incauto el transporte
 * @property int|null $pais_pro_id País de procedencia
 * @property int|null $pais_des_id País de destino
 * @property int|null $dep_pro_id Departamento de procedencia
 * @property int|null $dep_des_id Departamento de destino
 * @property int|null $mun_pro_id Municipio de procedencia
 * @property int|null $mun_des_id Municipio de destino
 * @property \Illuminate\Support\Carbon|null $created_at Fecha de creación de registro
 * @property \Illuminate\Support\Carbon|null $updated_at Fecha de última edición de registro
 * @property \Illuminate\Support\Carbon|null $deleted_at Fecha de última borrado de registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Decomiso $decomiso
 * @property-read \App\Models\Departamento $departamento
 * @property-read \App\Models\Municipio $municipio
 * @property-read \App\Models\Pais $pais
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte query()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereDecomisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereDepDesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereDepProId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereMarca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereModelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereMunDesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereMunProId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte wherePaisDesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte wherePaisProId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte wherePlaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|decomiso_transporte withoutTrashed()
 */
	class decomiso_transporte extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\presentacionDroga
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Decomiso> $decomisos
 * @property-read int|null $decomisos_count
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga query()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionDroga withoutTrashed()
 */
	class presentacionDroga extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\presentacionPrecursor
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor query()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|presentacionPrecursor withoutTrashed()
 */
	class presentacionPrecursor extends \Eloquent {}
}

