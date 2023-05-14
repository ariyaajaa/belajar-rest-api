<?php

namespace App\Observers;

use App\Models\Tool;
use App\Models\Log;

class ToolObserver
{
    /**
     * Handle the Tool "created" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function created(Tool $tool)
    {
        Log::create([
            'module'=>'tambah alat',
            'action'=>'tambah alat untuk id resep '.$tool->resep_idresep. ' dengan nama alat '.$tool->nama_alat,
            'useraccess'=>'-'
        ]);
    }

    /**
     * Handle the Tool "updated" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function updated(Tool $tool)
    {
        //
    }

    /**
     * Handle the Tool "deleted" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function deleting(Tool $tool)
    {
        Log::create([
            'module'=>'hapus alat',
            'action'=>'hapus alat untuk id resep '.$tool->resep_idresep,
            'useraccess'=>'-'
        ]);
    }

    /**
     * Handle the Tool "restored" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function restored(Tool $tool)
    {
        //
    }

    /**
     * Handle the Tool "force deleted" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function forceDeleted(Tool $tool)
    {
        //
    }
}
