<?php

namespace App\Listeners;

use App\Models\Decomiso;
//use App\Models\Bitacora_decomiso;
use App\Events\DecomisoBitacorae;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DecomisoBitacoral implements ShouldQueue //implementación de ShouldQueue para dejar proceso en segundo plano
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DecomisoBitacorae  $event
     * @return void
     */
    public function handle(DecomisoBitacorae $event)
    {
        //dd($event->decomiso);
        $decomiso_=$event->decomiso;
        $decomiso_->user_update=Auth::user()->id;
        $decomiso_->save();
        // $bitacora = Bitacora_decomiso::where('decomiso_id', $event->decomiso->id)->first();

        // if ($bitacora !== null) {
        //     $bitacora->user_id_update=Auth::user()->id;
        //     $bitacora->save();
        // } else {
        //     $bitacora_nueva=new Bitacora_decomiso;
        //     $bitacora_nueva->decomiso_id=$event->decomiso->id;
        //     $bitacora_nueva->user_id_create=Auth::user()->id;
        //     $bitacora_nueva->save();
        // }
    }
}
